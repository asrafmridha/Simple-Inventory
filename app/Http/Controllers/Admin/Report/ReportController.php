<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\LedgerEntry;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function financialReport(Request $request)
    {
        $from = $request->from;
        $to   = $request->to;

        /* ================= SALES ================= */

        $salesQuery = Sale::with('customer')
            ->when($from && $to, function ($q) use ($from, $to) {
                $q->whereBetween('date', [$from, $to]);
            });

        $sales = $salesQuery->get();

        $totalSell = $sales->sum('total_amount');


        /* ================= COGS (REAL COST) ================= */

        $cogsQuery = SaleItem::when($from && $to, function ($q) use ($from, $to) {
            $q->whereBetween('created_at', [$from, $to]);
        });

        $cogs = $cogsQuery->sum(DB::raw('quantity * price'));

        /* ================= EXPENSE ================= */

        $expenseQuery = LedgerEntry::where('account', 'like', '%Expense%')
            ->when($from && $to, function ($q) use ($from, $to) {
                $q->whereBetween('date', [$from, $to]);
            });

        $expenses = $expenseQuery->get();

        $totalExpense = $expenses->sum('debit');


        /* ================= PURCHASE ================= */

        $purchasesQuery = Purchase::when($from && $to, function ($q) use ($from, $to) {
            $q->whereBetween('date', [$from, $to]);
        });

        $purchases = $purchasesQuery->get();

        $totalPurchase = $purchases->sum('total_amount');



        $profit = $totalSell - ($cogs + $totalExpense);

        return view('admin.components.report.financial', compact(
            'sales',
            'expenses',
            'purchases',
            'totalSell',
            'totalExpense',
            'totalPurchase',
            'cogs',
            'profit',
            'from',
            'to'
        ));
    }
}
