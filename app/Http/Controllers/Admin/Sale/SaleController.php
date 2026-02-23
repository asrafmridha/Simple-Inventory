<?php

namespace App\Http\Controllers\Admin\Sale;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\LedgerEntry;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SalePayment;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{

    public function index(Request $request)
    {
        $query = Sale::with(['customer','payment'])
            ->latest();

        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('date', [
                $request->from,
                $request->to
            ]);
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        $sales = $query->paginate(20);

        $customers = Customer::select('id', 'name', 'phone')->get();

        return view('admin.components.sale.index', compact('sales', 'customers'));
    }
    public function create()
    {
        $products = Product::select('id', 'name', 'sell_price')->with('stock')->get();
        return view('admin.components.sale.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_phone' => 'required|string|max:20',
            'customer_name'  => 'required|string|max:255',

            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|numeric|min:1',

            'discount' => 'nullable|numeric|min:0',
            'vat' => 'nullable|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',

            'payment_method' => 'required|in:Cash,Bank,Mobile',
        ]);

        DB::beginTransaction();

        try {

            $customer = Customer::firstOrCreate(
                ['phone' => $validated['customer_phone']],
                [
                    'name' => $validated['customer_name'],
                    'address' => $request->customer_address
                ]
            );

            $productIds = collect($validated['items'])->pluck('product_id');

            $products = Product::whereIn('id', $productIds)
                ->with('stock')
                ->get()
                ->keyBy('id');

            $subTotal = 0;

            foreach ($validated['items'] as $item) {

                $product = $products->get($item['product_id']);

                if (!$product) {
                    throw new \Exception("Product not found.");
                }

                $stock = Stock::firstOrCreate(
                    ['product_id' => $product->id],
                    ['quantity' => 0]
                );

                if ($stock->quantity < $item['qty']) {
                    throw new \Exception(
                        "Insufficient stock for product: " . $product->name
                    );
                }

                $subTotal += $product->sell_price * $item['qty'];
            }

            $discount = $validated['discount'] ?? 0;
            $vatPercent = $validated['vat'] ?? 0;

            $afterDiscount = $subTotal - $discount;
            $vatAmount = ($afterDiscount * $vatPercent) / 100;
            $grandTotal = $afterDiscount + $vatAmount;

            $paid = $validated['paid_amount'] ?? 0;
            $due = max($grandTotal - $paid, 0);

            $sale = Sale::create([
                'customer_id'   => $customer->id,
                'sub_total'     => $subTotal,
                'discount'      => $discount,
                'vat_percent'   => $vatPercent,
                'vat_amount'    => $vatAmount,
                'total_amount'  => $grandTotal,
                'date'          => now()
            ]);

            foreach ($validated['items'] as $item) {

                $product = $products->get($item['product_id']);


                SaleItem::create([
                    'sale_id'    => $sale->id,
                    'product_id'  => $product->id,
                    'quantity'    => $item['qty'],
                    'price'       => $product->sell_price,
                ]);

                // Safe Stock Decrement
                $stock = Stock::where('product_id', $product->id)->first();

                $stock->decrement('quantity', $item['qty']);
            }

            SalePayment::create([
                'sale_id'    => $sale->id,
                'total_amount'  => $grandTotal,
                'paid_amount'  => $paid,
                'due_amount'    => $due,
                'payment_method' => $validated['payment_method'],
                'date'      => now()


            ]);

            // Sales Revenue (Full Amount)
            LedgerEntry::create([
                'account'   => 'Sales Revenue',
                'debit'     => 0,
                'credit'    => $grandTotal,
                'reference' => 'sale_' . $sale->id,
                'date'      => now()
            ]);

            // Paid Amount
            if ($paid > 0) {

                LedgerEntry::create([
                    'account'   => 'Cash',
                    'debit'     => $paid,
                    'credit'    => 0,
                    'reference' => 'sale_' . $sale->id,
                    'date'      => now()
                ]);
            }

            // Due Entry
            if ($due > 0) {

                LedgerEntry::create([
                    'account'   => 'Accounts Receivable',
                    'debit'     => $due,
                    'credit'    => 0,
                    'reference' => 'sale_' . $sale->id,
                    'date'      => now()
                ]);
            }

            DB::commit();

            return redirect()->route('sales.create')
                ->with('success', 'Sale Created Successfully');
        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function search(Request $request)
    {
        $customer = Customer::where('phone', $request->phone)->first();

        if ($customer) {
            return response()->json([
                'status' => 'found',
                'customer' => $customer
            ]);
        }

        return response()->json([
            'status' => 'not_found'
        ]);
    }
}
