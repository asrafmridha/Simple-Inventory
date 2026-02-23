@extends('admin.layouts.master')

@section('title')
    Products
@endsection

@section('section')
    Products
@endsection

@section('content')

<div class="card-body">

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-2">
        <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#create-modal">
            Create Product
        </button>
    </div>

    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>#SL</th>
                <th>Name</th>
                <th>Sell Price</th>
                <th width="150px">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ number_format($item->sell_price, 2) }}</td>
                    <td>
                        <div class="d-flex">

                            {{-- Edit Button --}}
                            <button data-toggle="modal"
                                data-target="#updateModal-{{ $item->id }}"
                                class="mr-1 btn btn-primary btn-sm">
                                Edit
                            </button>

                            {{-- Delete --}}
                            <form action="{{ route('products.destroy',$item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm deleteBtn">
                                    Delete
                                </button>
                            </form>

                        </div>
                    </td>
                </tr>

                {{-- ================= UPDATE MODAL ================= --}}
                <div class="modal fade" id="updateModal-{{ $item->id }}">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('products.update',$item->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="modal-header">
                                    <h4 class="modal-title">Update Product</h4>
                                    <button type="button" class="close" data-dismiss="modal">
                                        <span>&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">

                                    <div class="form-group">
                                        <label>Name <span class="text-danger">*</span></label>
                                        <input type="text"
                                               name="name"
                                               value="{{ old('name',$item->name) }}"
                                               class="form-control">
                                        @error('name')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Sell Price <span class="text-danger">*</span></label>
                                        <input type="number"
                                               step="0.01"
                                               name="sell_price"
                                               value="{{ old('sell_price',$item->sell_price) }}"
                                               class="form-control">
                                        @error('sell_price')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                </div>

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">
                                        Update
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

            @empty
                <tr>
                    <td colspan="4" class="text-center text-danger">
                        No Product Found
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>

{{-- ================= CREATE MODAL ================= --}}
<div class="modal fade" id="create-modal">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="{{ route('products.store') }}" method="POST">
                @csrf

                <div class="modal-header">
                    <h4 class="modal-title">Create Product</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <label>Name <span class="text-danger">*</span></label>
                        <input type="text"
                               name="name"
                               value="{{ old('name') }}"
                               class="form-control">
                        @error('name')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Sell Price <span class="text-danger">*</span></label>
                        <input type="number"
                               step="0.01"
                               name="sell_price"
                               value="{{ old('sell_price') }}"
                               class="form-control">
                        @error('sell_price')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">
                        Submit
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

@endsection


@section('scripts')

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
    $('.deleteBtn').click(function(e){
        e.preventDefault();
        let form = $(this).closest('form');

        swal({
            title: "Are you sure?",
            text: "This product will be deleted permanently!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete)=>{
            if(willDelete){
                form.submit();
            }
        });
    });
</script>

@endsection
