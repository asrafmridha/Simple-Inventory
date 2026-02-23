@extends('admin.layouts.master')

@section('title', 'Expense')

@section('content')

    <div class="shadow card">

        <div class="text-white card-header bg-primary">
            <h4 class="mb-0">Expense Module</h4>
        </div>

        <div class="card-body">

            {{-- ================= CREATE FORM ================= --}}

            <form method="POST" action="{{ route('expenses.store') }}">
                @csrf

                <div class="row">

                    <div class="col-md-3">
                        <input type="text" name="title" class="form-control" placeholder="Expense Title" required>
                    </div>

                    <div class="col-md-3">
                        <input type="number" name="amount" class="form-control" placeholder="Amount" required>
                    </div>

                    <div class="col-md-3">
                        <input type="date" name="date" class="form-control" required>
                    </div>

                    <div class="col-md-3">
                        <button class="btn btn-success">
                            Save
                        </button>
                    </div>

                </div>

            </form>

            <hr>

            {{-- ================= TABLE ================= --}}

            <table class="table table-bordered">

                <thead class="table-dark">
                    <tr>
                        <th>Title</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($expenses as $expense)
                        <tr>
                            <td>{{ $expense->title }}</td>
                            <td>{{ number_format($expense->amount, 2) }}</td>
                            <td>{{ $expense->date }}</td>
                            <td>

                                <form method="POST" action="{{ route('expenses.destroy', $expense->id) }}">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-danger btn-sm">
                                        Delete
                                    </button>

                                </form>

                            </td>
                        </tr>
                    @endforeach

                </tbody>

            </table>

        </div>
    </div>

@endsection
