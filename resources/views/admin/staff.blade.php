@extends('adminlte::page')

@section('title', 'Admin Dashboard')

@section('content_header')
<h1>View Staff</h1>
@stop

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            @foreach($staffs as $staff)
            <div class="col-md-3 col-sm-12">
                <!-- Widget: user widget style 2 -->
                <div class="card card-widget widget-user-2">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header bg-warning text-dark">
                        <!-- <div class="widget-user-image">
                            <img class="img-circle elevation-2" src="../dist/img/user7-128x128.jpg" alt="">
                        </div> -->
                        <!-- /.widget-user-image -->
                        <div class="modal fade" id="modal-{{ $staff->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalCenterTitle">Edit staff's Details</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('update_user', $staff->id ) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="active">
                                        <div class="modal-body">
                                            <div class="form-row">
                                                <div class="form-group col-md-6 text-left">
                                                    <label for="editStaffTitle_{{ $staff->id }}">Title</label>
                                                    <select name="title" id="editStaffTitle_{{ $staff->id }}" class="form-control">
                                                        <option value="{{ $staff->title }}" hidden>{{ $staff->title }}</option>
                                                        <option value="Mr.">Mr.</option>
                                                        <option value="Ms.">Ms.</option>
                                                        <option value="Dr.">Dr.</option>
                                                        <option value="Prof.">Prof.</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-6 text-left">
                                                    <label for="editFirstName_{{ $staff->id }}">First Name</label>
                                                    <input type="text" class="form-control" id="editFirstName_{{ $staff->id }}" name="fname" value="{{ $staff->fname }}" placeholder="First Name" required>
                                                </div>
                                                <div class="form-group col-md-6 text-left">
                                                    <label for="editLastName_{{ $staff->id }}">Last Name</label>
                                                    <input type="text" class="form-control" id="editLastName_{{ $staff->id }}" name="lname" value="{{ $staff->lname }}" placeholder="Last Name" required>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-6 text-left">
                                                    <label for="editEmail_{{ $staff->id }}">Email</label>
                                                    <input type="text" class="form-control" id="editEmail_{{ $staff->id }}" name="email" value="{{ $staff->email }}" placeholder="Email" required>
                                                </div>
                                                <div class="form-group col-md-6 text-left">
                                                    <label for="editPhone_{{ $staff->id }}">Phone</label>
                                                    <input type="text" class="form-control tel" id="editPhone_{{ $staff->id }}" name="phone" value="{{ $staff->phone }}" placeholder="+6012-5558686" required>
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
                        <div class="text-right m-0">
                            <button type="button" class="btn p-0 border-0 text-secondary" data-toggle="modal" data-target="#modal-{{ $staff->id }}">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                        <h3 class="widget-user-username text-right ml-0">{{ $staff->title }} {{ $staff->fname }} {{ $staff->lname }}</h3>
                        <h5 class="widget-user-desc text-right">Staff</h5>
                    </div>
                    <div class="card-footer p-0">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a href="mailto:{{ $staff->email }}" class="nav-link">
                                    Email <span class="float-right">{{ $staff->email }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <span class="nav-link">
                                    Phone <span class="float-right text-dark">{{ $staff->phone }}</span>
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <hr>
    <div class="container-fluid" id="add_new_staff">
        <h3>Invite New Staff</h3>
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Invitation Form</h3>
            </div>
            <form class="form-horizontal" action="{{ route('store_user') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 col-sm-12">
                            <div class="form-group">
                                <label for="new-staff-title">Title</label>
                                <select name="title" id="new-staff-title" class="form-control">
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
                                <label for="new-staff-fname">First Name</label>
                                <input type="text" class="form-control" id="new-staff-fname" placeholder="Enter First Name" name="fname" required>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="new-staff-lname">Last Name</label>
                                <input type="text" class="form-control" id="new-staff-lname" placeholder="Enter Last Name" name="lname" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="new-staff-email">Email address</label>
                                <input type="email" class="form-control" id="new-staff-email" placeholder="Enter email" name="email">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="new-staff-phone">Phone number</label>
                                <input type="text" class="form-control tel" id="new-staff-phone" placeholder="Phone number (eg. 0162223460)" name="phone" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <div class="form-check">
                                <!-- <input type="checkbox" class="form-check-input" id="email_notification">
                                <label class="form-check-label" for="email_notification">Notify new adminstrator with the email given above <small>(if checked)</small></label> -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-warning w-50 font-weight-bold d-flex m-auto justify-content-center">Invite Now</button>
                </div>
                <input type="hidden" name="user_type" value="staff">
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
        $('.tel').inputmask('019-999 9999[9]');
    });

    // Prevent letters in input form
    $(function() {
        var regExp = /[a-z]/i;
        $('#new-staff-phone').on('keydown keyup', function(e) {
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