@extends('adminlte::page')

@section('title', 'Admin Dashboard')

@section('content_header')
<h1>Manage Users</h1>
@stop

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-info">
                        <h3 class="card-title ">Users list</h3>
                    </div>
                    <div class="card-body">
                        <!-- <form action="{{ route('activeSelectedUser') }}" method="POST"> -->
                        <table id="users-table" class="table table-bordered table-hover table-responsive-xl">
                            <thead class="thead-light">
                                <tr>
                                    <th></th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email Address</th>
                                    <th>Phone Number</th>
                                    <th>Status</th>
                                    <th>Created at</th>
                                    <th></th>
                                    <th></th>

                                </tr>
                            </thead>
                            <tbody>

                                @foreach($users as $user)
                                <tr>
                                    <td class="text-center">
                                        @if($user->status == 'active')
                                        <input type="checkbox" data-id="{{ $user->id }}" checked disabled>
                                        @else
                                        <input type="checkbox" class="getusers" value="{{ $user->id }}" name="pendingUser[]">
                                        @endif
                                    </td>
                                    <td>{{ $user->fname }}</td>
                                    <td>{{ $user->lname }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone }}</td>
                                    @if($user->status == 'active')
                                    <td class="text-success text-center">{{ ucfirst($user->status) }}</td>
                                    @else
                                    <td class="text-danger text-center">{{ ucfirst($user->status) }}</td>
                                    @endif
                                    <td>{{ $user->created_at }}</td>
                                    <td class="text-center">
                                        <div class="modal fade" id="modal-{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalCenterTitle">Edit User's Details</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('update_user', $user->id ) }}" method="POST" id="edit-form-{{$user->id}}">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="form-row">
                                                                <div class="form-group col-md-6 text-left">
                                                                    <label for="editUserTitle_{{ $user->id }}">Title</label>
                                                                    <select name="title" id="editUserTitle_{{ $user->id }}" class="form-control">
                                                                        <option value="{{ $user->title }}" hidden>{{ $user->title }}</option>
                                                                        <option value="Mr.">Mr.</option>
                                                                        <option value="Ms.">Ms.</option>
                                                                        <option value="Dr.">Dr.</option>
                                                                        <option value="Prof.">Prof.</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-row">
                                                                <div class="form-group col-md-6 text-left">
                                                                    <label for="editFirstName_{{ $user->id }}">First Name</label>
                                                                    <input type="text" class="form-control" id="editFirstName_{{ $user->id }}" name="fname" value="{{ $user->fname }}" placeholder="First Name" required>
                                                                </div>
                                                                <div class="form-group col-md-6 text-left">
                                                                    <label for="editLastName_{{ $user->id }}">Last Name</label>
                                                                    <input type="text" class="form-control" id="editLastName_{{ $user->id }}" name="lname" value="{{ $user->lname }}" placeholder="Last Name" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-row">
                                                                <div class="form-group col-md-6 text-left">
                                                                    <label for="editEmail_{{ $user->id }}">Email</label>
                                                                    <input type="text" class="form-control" id="editEmail_{{ $user->id }}" name="email" value="{{ $user->email }}" placeholder="Email" required>
                                                                </div>
                                                                <div class="form-group col-md-6 text-left">
                                                                    <label for="editPhone_{{ $user->id }}">Phone</label>
                                                                    <input type="text" class="form-control tel" id="editPhone_{{ $user->id }}" name="phone" value="{{ $user->phone }}" placeholder="+6012-5558686" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-row">
                                                                <div class="form-group col-md-6 text-left">
                                                                    <label for="showRole_{{ $user->id }}">Role</label>
                                                                    <input type="text" class="form-control" id="showRole_{{ $user->id }}" name="role" value="{{ $user->role }}" disabled>
                                                                </div>
                                                                <div class="form-group col-md-6 text-left">
                                                                    <label for="editStatus_{{ $user->id }}">Status</label>
                                                                    <select class="form-control" id="editStatus_{{ $user->id }}" name="status">
                                                                        <option value="{{ $user->status }}" hidden>{{ ucfirst($user->status) }}</option>
                                                                        <option value="active">Active</option>
                                                                        <option value="pending">Pending</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-primary font-weight-bolder">Save changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn p-0 border-0 text-secondary" data-toggle="modal" data-target="#modal-{{ $user->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn p-0 border-0 text-danger" onclick="deleteUser('{{$user->id}}', '{{$user->fname}}', '{{$user->lname}}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <hr>
                        <div class="float-right">
                            <button type="button" class="btn btn-info" onclick="activateAll()">Activate All</button>
                            <button type="button" class="btn btn-secondary" onclick="activatedSelected()">Activate Selected</button>
                            <!-- <button type="button" class="btn btn-dark">Unselect All</button> -->
                        </div>
                        <!-- </form> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="container-fluid" id="add_new_user">
        <h3>Invite New User</h3>
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Invitation Form</h3>
            </div>
            <form class="form-horizontal" action="{{ route('store_user') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 col-sm-12">
                            <div class="form-group">
                                <label for="new-user-title">Title</label>
                                <select name="title" id="new-user-title" class="form-control" required>
                                    <option value="Mr." selected>Mr.</option>
                                    <option value="Ms.">Ms.</option>
                                    <option value="Dr.">Dr.</option>
                                    <option value="Prof.">Prof.</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="new-user-fname">First Name *</label>
                                <input type="text" class="form-control" id="new-user-fname" placeholder="Enter First Name" name="fname" required>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="new-user-lname">Last Name *</label>
                                <input type="text" class="form-control" id="new-user-lname" placeholder="Enter Last Name" name="lname" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="new-user-email">Email address *</label>
                                <input type="email" class="form-control" id="new-user-email" placeholder="Enter email" name="email" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="new-user-phone">Phone number *</label>
                                <input type="text" class="form-control tel" id="new-user-phone" placeholder="Phone number (eg. 016-2223460)" name="phone" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <div class="form-check">
                                <!-- <input type="checkbox" class="form-check-input" id="email_notification" name="send_email">
                                <label class="form-check-label" for="email_notification">Notify new user with the email given above <small>(if checked)</small></label> -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-info w-50 font-weight-bold d-flex m-auto justify-content-center">Invite Now</button>
                </div>
                <input type="hidden" name="user_type" value="public">
            </form>
        </div>
    </div>
</section>


@stop

@section('css')
<!-- <link rel="stylesheet" href="/css/admin_custom.css"> -->
@stop

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        $('#users-table').DataTable({
            "columnDefs": [{
                "targets": [0, 7, 8],
                "orderable": false
            }]
        });
        $('.tel').inputmask('019-999 9999[9]');
    });

    function activateAll() {
        Swal.fire({
            title: 'Are you sure \n to activate all users?',
            text: "However, you can deactivate the account later.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{route('activeAllUser')}}",
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                        Swal.fire({
                            icon: 'success',
                            title: 'Activated All Successfully',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    },
                    error: function(xml, status, error) {
                        Swal.fire({
                            title: 'Oops...',
                            html: 'An error had occurred - ' + error,
                            icon: 'error'
                        });
                    },
                });
            }
        })
    }

    function activatedSelected() {
        var selectedUser = [];
        $(".getusers:checked").each(function() {
            selectedUser.push($(this).val());
        });
        $.ajax({
            url: "{{route('activeSelectedUser')}}",
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "user_ids": selectedUser,
            },
            success: function(data) {
                setTimeout(() => {
                    location.reload();
                }, 2000);
                Swal.fire({
                    icon: 'success',
                    title: 'Updated Successfully',
                    showConfirmButton: false,
                    timer: 2000
                });
            },
            error: function(xml, status, error) {
                Swal.fire({
                    title: 'Oops...',
                    html: 'An error had occurred - ' + error,
                    icon: 'error'
                });
            },
        });
    }

    function deleteUser(id, fname, lname) {
        Swal.fire({
            title: 'Are you sure to remove\n' + fname + ' ' + lname + '?',
            text: "You are not able to reveal this.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "destroy/user/" + id,
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(data) {
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                        Swal.fire({
                            icon: 'success',
                            title: 'Removed Successfully',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    },
                    error: function(xml, status, error) {
                        Swal.fire({
                            title: 'Oops...',
                            html: 'An error had occurred - ' + error,
                            icon: 'error'
                        });
                    },
                });
            }
        })
    }

    // Prevent letters in input form
    $(function() {
        var regExp = /[a-z]/i;
        $('#new-user-phone').on('keydown keyup', function(e) {
            var value = String.fromCharCode(e.which) || e.key;

            // No letters allowed
            if (regExp.test(value)) {
                e.preventDefault();
                return false;
            }
        });
    });
</script>

@if(Session::has('success'))
<script>
    Swal.fire({
        position: 'top-end',
        icon: 'success',
        title: '{!!Session::get("success")!!}',
        showConfirmButton: false,
        timer: 2000
    })
</script>
@endif
@if(Session::has('error'))
<script>
    Swal.fire({
        title: 'Oops...',
        html: '{!!Session::get("error")!!}',
        icon: 'error'
    })
</script>
@endif
@stop