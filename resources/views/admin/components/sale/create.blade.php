@extends('admin.layouts.master')

@section('title', 'Create Sale')

@section('content')

<div class="shadow card">

    <div class="text-white card-header bg-primary">
        <h4 class="mb-0">Create Sale</h4>
    </div>

    {{-- ================= FORM ================= --}}
    <form action="{{ route('sales.store') }}" method="POST">
        @csrf

        <input type="hidden" id="rowIndex" value="0">

        <div class="card-body">

            {{-- ================= ERROR MESSAGE ================= --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            {{-- ================= CUSTOMER SECTION ================= --}}

            <div class="mb-3 row">

                <div class="col-md-4">
                    <label class="form-label">Customer Phone <span class="text-danger">*</span></label>
                    <input type="text"
                           name="customer_phone"
                           value="{{ old('customer_phone') }}"
                           class="form-control"
                           required>
                    @error('customer_phone')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                    <input type="text"
                           name="customer_name"
                           value="{{ old('customer_name') }}"
                           class="form-control"
                           required>
                    @error('customer_name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Address</label>
                    <input type="text"
                           name="customer_address"
                           value="{{ old('customer_address') }}"
                           class="form-control">
                </div>

            </div>

            <hr>

            {{-- ================= PRODUCT TABLE ================= --}}

            <div class="table-responsive">

                <table class="table table-bordered" id="saleTable">

                    <thead class="table-dark">
                        <tr>
                            <th>Product <span class="text-danger">*</span></th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th width="80">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        <tr>

                            <td>
                                <select name="items[0][product_id]"
                                        class="form-control product"
                                        required>

                                    <option value="">Select Product</option>

                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}"
                                                data-price="{{ $product->sell_price }}"
                                                data-stock="{{ $product->stock->quantity ?? 0 }}">
                                            {{ $product->name }}
                                            (Stock: {{ $product->stock->quantity ?? 0 }})
                                        </option>
                                    @endforeach

                                </select>

                                <small class="text-danger">
                                    @error('items.0.product_id')
                                        {{ $message }}
                                    @enderror
                                </small>

                                <small class="text-danger stock-text"></small>

                            </td>

                            <td>
                                <input type="number"
                                       name="items[0][qty]"
                                       class="form-control qty"
                                       min="1"
                                       required>

                                <small class="text-danger">
                                    @error('items.0.qty')
                                        {{ $message }}
                                    @enderror
                                </small>
                            </td>

                            <td>
                                <input type="number"
                                       class="form-control price"
                                       readonly>
                            </td>

                            <td>
                                <input type="text"
                                       class="form-control rowTotal"
                                       readonly>
                            </td>

                            <td>
                                <button type="button"
                                        class="btn btn-success addRow">
                                    +
                                </button>
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

            {{-- ================= DISCOUNT + VAT ================= --}}

            <div class="mt-3 row">

                <div class="col-md-3">
                    <label>Flat Discount</label>
                    <input type="number"
                           name="discount" id="discount"
                           value="{{ old('discount',0) }}"
                           class="form-control">
                </div>

                <div class="col-md-3">
                    <label>VAT (%)</label>
                    <input type="number"
                           name="vat" id="vat"
                           value="{{ old('vat',0) }}"
                           class="form-control">
                </div>

                <div class="col-md-3">
                    <label>Paid Amount</label>
                    <input type="number"
                           name="paid_amount"
                           value="{{ old('paid_amount',0) }}"
                           class="form-control">
                </div>

                <div class="col-md-3">
                    <label>Grand Total</label>
                    <input type="text"
                           id="grandTotal"
                           name="total_amount"
                           class="form-control"
                           readonly>
                </div>

            </div>

            <div class="mt-3">

                <label>Payment Method</label>

                <select name="payment_method" class="form-control" required>

                    <option value="">Select Method</option>
                    <option value="Cash">Cash</option>
                    <option value="Bank">Bank</option>
                    <option value="Mobile">Mobile</option>

                </select>

                @error('payment_method')
                    <small class="text-danger">{{ $message }}</small>
                @enderror

            </div>

        </div>

        <div class="text-right card-footer">

            <button type="submit" class="px-4 btn btn-primary">
                Submit Sale
            </button>

        </div>

    </form>

</div>

@endsection

@section('scripts')

    <script>
        {{-- ================= PRICE AUTO ================= --}}

        $(document).on('change', '.product', function() {

            let price = $(this).find(':selected').data('price');
            let stock = $(this).find(':selected').data('stock');

            let row = $(this).closest('tr');

            row.find('.price').val(price || 0);
            row.find('.stock-text').text("Available Stock: " + stock);

            row.find('.qty').attr('max', stock); // 👈 Max limit set

            calculateRow(row);

        });

        $(document).on('input', '.qty', function() {

            let row = $(this).closest('tr');

            let qty = parseFloat($(this).val()) || 0;

            let maxStock = parseFloat($(this).attr('max')) || 0;

            if (qty > maxStock) {

                alert("Stock Not Available!");

                $(this).val(maxStock);
            }

            calculateRow(row);
        });

        function calculateRow(row) {

            let qty = parseFloat(row.find('.qty').val()) || 0;
            let price = parseFloat(row.find('.price').val()) || 0;

            let total = qty * price;

            row.find('.rowTotal').val(total.toFixed(2));

            calculateGrandTotal();
        }

        function calculateGrandTotal() {

            let sum = 0;

            $('.rowTotal').each(function() {
                sum += parseFloat($(this).val()) || 0;
            });

            let discount = parseFloat($('#discount').val()) || 0;
            let vat = parseFloat($('#vat').val()) || 0;

            let afterDiscount = sum - discount;

            let vatAmount = (afterDiscount * vat) / 100;

            let finalTotal = afterDiscount + vatAmount;

            $('#grandTotal').val(finalTotal.toFixed(2));
        }

        $(document).on('input', '#discount,#vat', function() {
            calculateGrandTotal();
        });

        {{-- ================= ADD ROW ================= --}}

        $('.addRow').click(function() {

            let index = parseInt($('#rowIndex').val()) + 1;
            $('#rowIndex').val(index);

            let row = $(this).closest('tr').clone();

            row.find('input').val('');
            row.find('select').val('');

            row.find('select')
                .attr('name', 'items[' + index + '][product_id]');

            row.find('.qty')
                .attr('name', 'items[' + index + '][qty]');

            row.find('.addRow')
                .removeClass('btn-success addRow')
                .addClass('btn-danger removeRow')
                .text('-');

            $('#saleTable tbody').append(row);

        });

        $(document).on('click', '.removeRow', function() {

            $(this).closest('tr').remove();
            calculateGrandTotal();

        });

        {{-- ================= CUSTOMER AUTO SEARCH ================= --}}

        $('#customer_phone').on('keyup', function() {

            let phone = $(this).val();

            if (phone.length >= 3) {

                $.ajax({
                    url: "{{ route('customer.search') }}",
                    type: "GET",
                    data: {
                        phone: phone
                    },
                    success: function(res) {

                        if (res.status == 'found') {

                            $('#customer_name').val(res.customer.name);
                            $('#customer_address').val(res.customer.address);

                        } else {

                            $('#customer_name').val('');
                            $('#customer_address').val('');
                        }

                    }
                });

            }

        });
    </script>

@endsection
