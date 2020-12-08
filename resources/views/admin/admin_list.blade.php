@extends('adminlte::page')

@section('title', 'Admin Dashboard')

@section('content_header')
<h1>View Adminstrator</h1>
@stop

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            @foreach($admins as $admin)
            <div class="col-md-4 col-sm-12">
                <!-- Widget: user widget style 2 -->
                <div class="card card-widget widget-user-2">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header" style="background-color:#3d9970;color:white">
                        <!-- <div class="widget-user-image">
                            <img class="img-circle elevation-2" src="../dist/img/user7-128x128.jpg" alt="">
                        </div> -->
                        <!-- /.widget-user-image -->
                        <h3 class="widget-user-username text-right ml-0">{{ $admin->title }} {{ $admin->fname }} {{ $admin->lname }}</h3>
                        <h5 class="widget-user-desc text-right">Adminstrator</h5>
                    </div>
                    <div class="card-footer p-0">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a href="mailto:{{ $admin->email }}" class="nav-link">
                                    Email <span class="float-right">{{ $admin->email }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    Phone <span class="float-right">{{ $admin->phone }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <hr>
    <div class="container-fluid" id="add_new_admin">
        <h3>Invite New Adminstrator</h3>
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Invitation Form</h3>
            </div>
            <form class="form-horizontal" action="{{ route('store_user') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 col-sm-12">
                            <div class="form-group">
                                <label for="new-admin-title">Title</label>
                                <select name="title" id="new-admin-title" class="form-control">
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
                                <label for="new-admin-fname">First Name</label>
                                <input type="text" class="form-control" id="new-admin-fname" placeholder="Enter First Name" name="fname" required>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="new-admin-lname">Last Name</label>
                                <input type="text" class="form-control" id="new-admin-lname" placeholder="Enter Last Name" name="lname" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="new-admin-email">Email address</label>
                                <input type="email" class="form-control" id="new-admin-email" placeholder="Enter email" name="email">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="new-admin-phone">Phone number</label>
                                <input type="text" class="form-control" id="new-admin-phone" placeholder="Phone number (eg. 0162223460)" name="phone" pattern="^(\+?6?01)[0-46-9]-*[0-9]{7,8}$" oninvalid="setCustomValidity('The phone format should be 016-xxx4567')" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="email_notification">
                                <label class="form-check-label" for="email_notification">Notify new adminstrator with the email given above <small>(if checked)</small></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success w-50 font-weight-bold d-flex m-auto justify-content-center">Invite Now</button>
                </div>
                <input type="hidden" name="user_type" value="admin">
            </form>
        </div>
    </div>
</section>


@stop

@section('css')
<!-- <link rel="stylesheet" href="/css/admin_custom.css"> -->
@stop

@section('js')
<script>
    // Prevent letters in input form
    $(function() {
        var regExp = /[a-z]/i;
        $('#new-admin-phone').on('keydown keyup', function(e) {
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