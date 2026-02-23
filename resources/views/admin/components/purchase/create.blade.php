@extends('admin.layouts.master')

@section('title', 'Purchase Create')

@section('content')

    <div class="card">
        <div class="text-white card-header bg-primary">
            <h4>Create Purchase</h4>
        </div>

        <form action="{{ route('purchases.store') }}" method="POST">
            @csrf

            <div class="card-body">

                {{-- Supplier --}}
                <div class="form-group">
                    <label>Supplier Name *</label>
                    <input type="text" name="supplier_name" class="form-control" placeholder="Enter Supplier Name" required>
                </div>

                {{-- Product Table --}}
                <table class="table table-bordered" id="productTable">
                    <thead>
                        <tr>
                            <th width="30%">Product</th>
                            <th width="15%">Quantity</th>
                            <th width="20%">Purchase Price</th>
                            <th width="20%">Total</th>
                            <th width="10%">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>
                                <select name="items[0][product_id]" class="form-control" required>
                                    <option value="">Select Product</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>

                            <td>
                                <input type="number" name="items[0][quantity]" class="form-control quantity" min="1"
                                    required>
                            </td>

                            <td>
                                <input type="number" step="0.01" name="items[0][purchase_price]"
                                    class="form-control price" required>
                            </td>

                            <td>
                                <input type="text" class="form-control total" readonly>
                            </td>

                            <td>
                                <button type="button" class="btn btn-success addRow">
                                    +
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                {{-- Grand Total --}}
                <div class="row">
                    <div class="col-md-4 offset-md-8">
                        <div class="form-group">
                            <label>Grand Total</label>
                            <input type="text" name="total_amount" id="grandTotal" class="form-control" readonly>
                        </div>
                    </div>
                </div>

            </div>

            <div class="text-right card-footer">
                <button type="submit" class="btn btn-primary">
                    Save Purchase
                </button>
            </div>

        </form>
    </div>

@endsection


@section('scripts')

    <script>
        let rowIndex = 1;

        // Add new row
        $('.addRow').click(function() {

            let row = `
    <tr>
        <td>
            <select name="items[${rowIndex}][product_id]"
                class="form-control" required>
                <option value="">Select Product</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}">
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>
        </td>

        <td>
            <input type="number"
                name="items[${rowIndex}][quantity]"
                class="form-control quantity" min="1" required>
        </td>

        <td>
            <input type="number"
                step="0.01"
                name="items[${rowIndex}][purchase_price]"
                class="form-control price" required>
        </td>

        <td>
            <input type="text"
                class="form-control total" readonly>
        </td>

        <td>
            <button type="button"
                class="btn btn-danger removeRow">-</button>
        </td>
    </tr>
    `;

            $('#productTable tbody').append(row);
            rowIndex++;
        });


        // Remove row
        $(document).on('click', '.removeRow', function() {
            $(this).closest('tr').remove();
            calculateGrandTotal();
        });


        // Auto calculate row total
        $(document).on('keyup change', '.quantity, .price', function() {

            let row = $(this).closest('tr');

            let qty = parseFloat(row.find('.quantity').val()) || 0;
            let price = parseFloat(row.find('.price').val()) || 0;

            let total = qty * price;

            row.find('.total').val(total.toFixed(2));

            calculateGrandTotal();
        });


        // Grand total calculate
        function calculateGrandTotal() {

            let grandTotal = 0;

            $('.total').each(function() {
                grandTotal += parseFloat($(this).val()) || 0;
            });

            $('#grandTotal').val(grandTotal.toFixed(2));
        }
    </script>

@endsection
