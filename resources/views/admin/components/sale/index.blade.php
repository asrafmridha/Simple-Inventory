@extends('admin.layouts.master')

@section('title', 'Sale History')

@section('content')

    <div class="shadow card">

        <div class="text-white card-header bg-primary">
            <h4 class="mb-0">Sale History</h4>
        </div>

        <div class="card-body">

            {{-- ================= FILTER ================= --}}

            <form method="GET" class="mb-3">

                <div class="row">

                    <div class="col-md-3">
                        <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                    </div>

                    <div class="col-md-3">
                        <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                    </div>

                    <div class="col-md-3">

                        <select name="customer_id" class="form-control">

                            <option value="">All Customer</option>

                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}"
                                    {{ request('customer_id') == $customer->id ? 'selected' : '' }}>

                                    {{ $customer->name }} - {{ $customer->phone }}

                                </option>
                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-3">
                        <button class="btn btn-primary">
                            Filter
                        </button>

                        <a href="{{ route('sales.index') }}" class="btn btn-secondary">
                            Reset
                        </a>

                    </div>

                </div>

            </form>

            {{-- ================= TABLE ================= --}}

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead class="table-dark">

                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Paid</th>
                            <th>Due</th>
                            <th>Action</th>
                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($sales as $sale)
                            <tr>

                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    {{ $sale->customer->name ?? '' }}
                                    <br>
                                    <small>{{ $sale->customer->phone ?? '' }}</small>
                                </td>

                                <td>{{ $sale->date }}</td>

                                <td>{{ number_format($sale->total_amount, 2) }}</td>
                                <td>{{ number_format($sale->payment->paid_amount, 2) }}</td>
                                <td class="text-danger">
                                    {{ number_format($sale->payment->due_amount, 2) }}
                                </td>

                                <td>

                                    <button class="btn btn-info btn-sm" data-toggle="modal"
                                        data-target="#viewModal-{{ $sale->id }}">
                                        View
                                    </button>

                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>


            </div>

        </div>

    </div>

    @foreach ($sales as $sale)
        <div class="modal fade" id="viewModal-{{ $sale->id }}">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="text-white modal-header bg-dark">
                        <h5>Sale Details</h5>
                    </div>

                    <div class="modal-body">

                        <p><strong>Customer:</strong>
                            {{ $sale->customer->name ?? '' }}
                        </p>

                        <p><strong>Date:</strong> {{ $sale->date }}</p>

                        <hr>

                        <table class="table table-bordered">

                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach ($sale->items as $item)
                                    <tr>
                                        <td>{{ $item->product->name ?? '' }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ number_format($item->price, 2) }}</td>
                                        <td>
                                            {{ number_format($item->quantity * $item->price, 2) }}
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>
            </div>
        </div>
    @endforeach

@endsection
