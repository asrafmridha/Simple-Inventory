@extends('admin.layouts.master')

@section('title')
    Users
@endsection
@section('section')
    Users
@endsection
@section('content')
    <div class="card-body">
        <div class="mb-1">

            <button type="button" class="btn bg-gradient-success" data-toggle="modal" data-target="#create-modal">
                Create User
            </button>
        </div>

        <table id="example2" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>#SL</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $item)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->email }}</td>
                        <td>

                            <div class="flex-row d-flex">
                                <button data-toggle="modal" data-target="#updateModal-{{ $item->id }}"
                                    class="mr-1 btn btn-primary btn-sm"><i class="fa-solid fa-pen-to-square"></i></button>

                                <form action="{{ route('users.destroy', $item->id) }}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <button data-toggle="tooltip" class=" deleteBtn btn-danger btn-sm"
                                        data-id="{{ $item->id }}"><i class="fa-solid fa-trash"></i></button>
                                </form>

                            </div>
                        </td>

                    </tr>
                    {{-- create Modal --}}
                    <div class="modal fade" id="updateModal-{{ $item->id }}">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Update User</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="card card-primary">
                                        <!-- form start -->
                                        <form action="{{ route('users.update', $item->id) }}" method="POST"
                                            enctype="multipart/form-data">
                                            @method('PUT')
                                            @csrf

                                            <div class="card-body">

                                                <div class="form-group">
                                                    <label for="">Name <span class="text-danger"> * </span></label>
                                                    <input type="text" class="form-control" name="name"
                                                        placeholder="Name" value="{{ old('name', $item->name) }}">
                                                    @error('name')
                                                        <p class="text text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="">Email <span class="text-danger"> * </span></label>
                                                    <input type="email" class="form-control" name="email"
                                                        placeholder="Email" value="{{ old('email', $item->email) }}"
                                                        required>
                                                    @error('email')
                                                        <p class="text text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="">Select Role<span class="text-danger"> *
                                                        </span></label>
                                                    <select name="roles" id="" class="form-control" required>
                                                        <option value="">Select Role</option>
                                                        @foreach ($roles as $role)
                                                            <option value="{{ $role->id }}"
                                                                {{ $item->hasRole($role->name) ? 'selected' : '' }}>
                                                                {{ $role->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('course_category_id')
                                                        <p class="text text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="">Password <span class="text-danger"> *
                                                        </span></label>
                                                    <input type="password" class="form-control" name="password"
                                                        placeholder="Password" value="{{ old('password') }}">
                                                    @error('password')
                                                        <p class="text text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>




                                            </div>

                                            <div class="card-footer">
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-end">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- create Modal --}}
    <div class="modal fade" id="create-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Create User</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card card-primary">
                        <!-- form start -->
                        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="">Name <span class="text-danger"> * </span></label>
                                    <input type="text" class="form-control" name="name" placeholder="Name"
                                        value="{{ old('name') }}">
                                    @error('name')
                                        <p class="text text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="">Email <span class="text-danger"> * </span></label>
                                    <input type="email" class="form-control" name="email" placeholder="Name"
                                        value="{{ old('email') }}" required>
                                    @error('email')
                                        <p class="text text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="">Select Role<span class="text-danger"> *
                                        </span></label>
                                    <select name="roles" id="" class="form-control" required>
                                        <option value="">Select Role</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">
                                                {{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('course_category_id')
                                        <p class="text text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="">Password <span class="text-danger"> * </span></label>
                                    <input type="password" class="form-control" name="password" placeholder="Password"
                                        value="{{ old('password') }}">
                                    @error('password')
                                        <p class="text text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection
@section('scripts')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('.deleteBtn').click(function(e) {
            var form = $(this).closest('form');
            var dataId = $(this).data('id');
            e.preventDefault();

            //swal javascript
            swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this imaginary file!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        form.submit();
                        swal("Poof! Your imaginary file has been deleted!", {
                            icon: "success",
                        });
                    } else {
                        swal("Your imaginary file is safe!");
                    }
                });

        });
    </script>
@endsection
