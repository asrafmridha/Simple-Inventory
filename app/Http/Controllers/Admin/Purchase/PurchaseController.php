<?php

namespace App\Http\Controllers\Admin\Purchase;

use App\Http\Controllers\Controller;
use App\Models\LedgerEntry;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Purchase::with('items.product')->latest();

        // Date Filter
        if ($request->from && $request->to) {
            $query->whereBetween('date', [
                $request->from,
                $request->to
            ]);
        }

        $purchases = $query->get();

        return view('admin.components.purchase.index', compact('purchases'));
    }
    public function create()
    {

        $products = Product::select('id', 'name')->get();

        return view('admin.components.purchase.create', compact('products'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'supplier_name' => 'required',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.purchase_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {

            // ================= Purchase Create =================
            $purchase = Purchase::create([
                'purchase_no' => generatePurchaseNumber(),
                'supplier_name' => $request->supplier_name,
                'total_amount'  => $request->total_amount,
                'date' => now()
            ]);

            // ================= Bulk Purchase Items =================
            $purchaseItems = [];
            $productQuantities = [];

            foreach ($request->items as $item) {

                $purchaseItems[] = [
                    'purchase_id'   => $purchase->id,
                    'product_id'     => $item['product_id'],
                    'quantity'       => $item['quantity'],
                    'purchase_price'  => $item['purchase_price'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (!isset($productQuantities[$item['product_id']])) {
                    $productQuantities[$item['product_id']] = 0;
                }

                $productQuantities[$item['product_id']] += $item['quantity'];
            }
            PurchaseItem::insert($purchaseItems);

            foreach ($productQuantities as $productId => $qty) {

                Stock::updateOrCreate(
                    ['product_id' => $productId],
                    ['quantity' => DB::raw("quantity + $qty")]
                );
            }

            LedgerEntry::create([
                'account' => 'Inventory',
                'debit' => $request->total_amount,
                'credit' => 0,
                'reference' => 'purchase_' . $purchase->id,
                'date' => now()
            ]);

            LedgerEntry::create([
                'account' => 'Cash',
                'debit' => 0,
                'credit' => $request->total_amount,
                'reference' => 'purchase_' . $purchase->id,
                'date' => now()
            ]);
        });

        return redirect()->route('purchases.index')
            ->with('success', 'Purchase Created Successfully');
    }
}
