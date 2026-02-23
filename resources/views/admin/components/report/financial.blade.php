@extends('admin.layouts.master')

@section('title', 'Financial Report')

@section('content')

    <div class="shadow card">

        <div class="text-white card-header bg-primary">
            <h4 class="mb-0">Financial Report</h4>
        </div>

        <div class="card-body">

            {{-- ================= FILTER ================= --}}

            <form method="GET" action="{{ route('financial.report') }}" class="mb-4">

                <div class="row">

                    <div class="col-md-3">
                        <label>From</label>
                        <input type="date" name="from" class="form-control" value="{{ $from ?? request('from') }}">
                    </div>

                    <div class="col-md-3">
                        <label>To</label>
                        <input type="date" name="to" class="form-control" value="{{ $to ?? request('to') }}">
                    </div>

                    <div class="col-md-3 align-self-end">
                        <button class="btn btn-primary">
                            Filter
                        </button>

                        <a href="{{ route('financial.report') }}" class="btn btn-secondary">
                            Reset
                        </a>
                    </div>

                </div>

            </form>

            {{-- ================= SUMMARY ================= --}}

            <table class="table table-bordered">

                <tr>
                    <th>Total Sell</th>
                    <td class="text-success">
                        {{ number_format($totalSell ?? 0, 2) }}
                    </td>
                </tr>

                <tr>
                    <th>COGS (Product Cost)</th>
                    <td class="text-warning">
                        {{ number_format($cogs ?? 0, 2) }}
                    </td>
                </tr>

                <tr>
                    <th>Total Expense</th>
                    <td class="text-danger">
                        {{ number_format($totalExpense ?? 0, 2) }}
                    </td>
                </tr>

                <tr>
                    <th>Net Profit</th>
                    <td class="{{ ($profit ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                        <strong>
                            {{ number_format($profit ?? 0, 2) }}
                        </strong>
                    </td>
                </tr>

            </table>

            <hr>

            {{-- ================= SALES DETAILS ================= --}}

            <h5>Sales Details</h5>

            <table class="table table-bordered table-striped">

                <thead class="table-dark">
                    <tr>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Total</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($sales ?? [] as $sale)
                        <tr>
                            <td>{{ $sale->date }}</td>
                            <td>{{ $sale->customer->name ?? '' }}</td>
                            <td>{{ number_format($sale->total_amount, 2) }}</td>
                        </tr>
                    @endforeach

                </tbody>

            </table>

            {{-- ================= PURCHASE DETAILS ================= --}}

            <h5 class="mt-4">Purchase Details</h5>

            <table class="table table-bordered">

                <thead class="table-dark">
                    <tr>
                        <th>Date</th>
                        <th>Supplier</th>
                        <th>Total</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($purchases ?? [] as $purchase)
                        <tr>
                            <td>{{ $purchase->date }}</td>
                            <td>{{ $purchase->supplier_name }}</td>
                            <td>{{ number_format($purchase->total_amount, 2) }}</td>
                        </tr>
                    @endforeach

                </tbody>

            </table>

            {{-- ================= EXPENSE DETAILS ================= --}}

            <h5 class="mt-4">Expense Details</h5>

            <table class="table table-bordered">

                <thead class="table-dark">
                    <tr>
                        <th>Date</th>
                        <th>Title</th>
                        <th>Amount</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($expenses ?? [] as $expense)
                        <tr>
                            <td>{{ $expense->date }}</td>
                            <td>{{ $expense->reference ?? 'Expense' }}</td>
                            <td class="text-danger">
                                {{ number_format($expense->debit, 2) }}
                            </td>
                        </tr>
                    @endforeach

                </tbody>

            </table>

        </div>
    </div>

@endsection
