<?php

namespace App\Http\Controllers\Admin\Expense;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\LedgerEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{

    public function index()
    {
        $expenses = Expense::latest()->get();
        return view('admin.components.expense.index', compact('expenses'));
    }

    // ================= STORE =================
    public function store(Request $request)
    {
        $request->validate([
            'title'  => 'required|string',
            'amount' => 'required|numeric|min:0',
            'date'   => 'required|date'
        ]);

        DB::transaction(function () use ($request) {

            $expense = Expense::create([
                'title' => $request->title,
                'amount' => $request->amount,
                'date' => $request->date,
                'created_by' => auth()->id()
            ]);

            // 🔥 Auto Ledger Entry
            LedgerEntry::create([
                'account'  => 'Expense',
                'debit'    => $request->amount,
                'credit'   => 0,
                'reference' => 'expense_' . $expense->id,
                'date'     => $request->date
            ]);
        });

        return back()->with('success', 'Expense Created');
    }

    // ================= UPDATE =================
    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'title'  => 'required',
            'amount' => 'required|numeric',
            'date'   => 'required|date'
        ]);

        DB::transaction(function () use ($request, $expense) {

            // 🔥 Reverse Old Ledger Entry
            LedgerEntry::where('reference', 'expense_' . $expense->id)
                ->delete();


            $expense->update([
                'title' => $request->title,
                'amount' => $request->amount,
                'date' => $request->date
            ]);


            LedgerEntry::create([
                'account'  => 'Expense',
                'debit'    => $request->amount,
                'credit'   => 0,
                'reference' => 'expense_' . $expense->id,
                'date'     => $request->date
            ]);
        });

        return back()->with('success', 'Expense Updated');
    }

    public function destroy(Expense $expense)
    {
        DB::transaction(function () use ($expense) {
            LedgerEntry::where('reference', 'expense_' . $expense->id)->delete();
            $expense->delete();
        });

        return back()->with('success', 'Expense Deleted');
    }
}
