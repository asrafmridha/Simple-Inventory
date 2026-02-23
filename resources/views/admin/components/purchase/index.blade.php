@extends('admin.layouts.master')

@section('title','Purchase History')

@section('content')

<div class="card">

    <div class="text-white card-header bg-primary">
        <h4 class="mb-0">Purchase History</h4>
    </div>

    <div class="card-body">

        {{-- ================= DATE FILTER ================= --}}
        <form method="GET" action="{{ route('purchases.index') }}">
            <div class="mb-3 row">

                <div class="col-md-3">
                    <input type="date"
                           name="from"
                           class="form-control"
                           value="{{ request('from') }}">
                </div>

                <div class="col-md-3">
                    <input type="date"
                           name="to"
                           class="form-control"
                           value="{{ request('to') }}">
                </div>

                <div class="col-md-3">
                    <button class="btn btn-primary">
                        Filter
                    </button>

                    <a href="{{ route('purchases.index') }}"
                       class="btn btn-danger">
                        Reset
                    </a>
                </div>

            </div>
        </form>

        {{-- ================= TABLE ================= --}}
        <table class="table table-bordered table-hover datatable">
            <thead>
                <tr>
                    <th>Purchase No</th>
                    <th>Supplier</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th width="120">Action</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($purchases as $purchase)
                    <tr>
                        <td>{{ $purchase->purchase_number }}</td>
                        <td>{{ $purchase->supplier_name }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($purchase->date)->format('d M Y') }}
                        </td>
                        <td>
                            {{ number_format($purchase->total_amount,2) }}
                        </td>

                        <td>
                            <button class="btn btn-info btn-sm"
                                    data-toggle="modal"
                                    data-target="#viewModal-{{ $purchase->id }}">
                                View
                            </button>
                        </td>
                    </tr>
                @endforeach

            </tbody>

        </table>

    </div>
</div>


{{-- ================= ALL MODALS ================= --}}
@foreach ($purchases as $purchase)

<div class="modal fade"
     id="viewModal-{{ $purchase->id }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="text-white modal-header bg-dark">
                <h5 class="modal-title">
                    Purchase Details - {{ $purchase->purchase_number }}
                </h5>

                <button type="button"
                        class="text-white close"
                        data-dismiss="modal">
                    &times;
                </button>
            </div>

            <div class="modal-body">

                <p>
                    <strong>Supplier:</strong>
                    {{ $purchase->supplier_name }}
                </p>

                <p>
                    <strong>Date:</strong>
                    {{ \Carbon\Carbon::parse($purchase->date)->format('d M Y') }}
                </p>

                <hr>

                <table class="table table-bordered">
                    <thead class="bg-light">
                        <tr>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($purchase->items as $item)
                            <tr>
                                <td>
                                    {{ $item->product->name ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ $item->quantity }}
                                </td>

                                <td>
                                    {{ number_format($item->purchase_price,2) }}
                                </td>

                                <td>
                                    {{ number_format($item->quantity * $item->purchase_price,2) }}
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


{{-- ================= DATATABLE SCRIPT ================= --}}
@section('scripts')

<link rel="stylesheet"
href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function () {
        $('.datatable').DataTable({
            responsive: true
        });
    });
</script>

@endsection
