@extends('adminlte::page')

@section('title', 'Admin Dashboard')

@section('content_header')
<h1>Profile</h1>
@stop

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="card card-widget widget-user">
                    <div class="widget-user-header bg-info">
                        <h3 class="widget-user-username text-right">{{ $user->title }} <b>{{ $user->fname }}</b> {{ $user->lname }}</h3>
                        <h5 class="widget-user-desc text-right">Adminstrator</h5>
                        <span class="position-absolute" style="top:98px;right:2px;padding:10px;cursor:pointer" onclick="show_edit_form()"><i class="fas fa-user-edit"></i></span>
                    </div>
                    <div class="widget-user-image">
                        <img class="img-circle elevation-2" src="{{ asset('img/female_avatar.svg') }}" alt="">
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="description-block">
                                    <h5 class="description-header"><i class="far fa-envelope"></i></h5>
                                    <span class="description-text">{{ $user->email }}</span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="description-block">
                                    <h5 class="description-header"><i class="fas fa-phone"></i></h5>
                                    <span class="description-text">{{ $user->phone }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-md-8" id="profile-edit-form" style="display:none">
                <form role="form" action="" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2 col-sm-12">
                                <div class="form-group">
                                    <label for="admin-title">Title</label>
                                    <select name="title" id="admin-title" class="form-control" required>
                                        @if(isset($user->title))
                                        <option value="{{ $user->title }}" selected hidden> {{ $user->title }}</option>
                                        @endif
                                        <option value="Mr.">Mr.</option>
                                        <option value="Ms.">Ms.</option>
                                        <option value="Dr.">Dr.</option>
                                        <option value="Prof.">Prof.</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5 col-sm-12">
                                <div class="form-group">
                                    <label for="admin-fname">First Name</label>
                                    <input type="text" class="form-control" id="admin-fname" placeholder="Enter First Name" value="{{ $user->fname}}" required>
                                </div>
                            </div>
                            <div class="col-md-5 col-sm-12">
                                <div class="form-group">
                                    <label for="admin-lname">Last Name</label>
                                    <input type="text" class="form-control" id="admin-lname" placeholder="Enter Last Name" value="{{ $user->lname}}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="admin-email">Email address</label>
                                    <input type="email" class="form-control" id="admin-email" placeholder="Enter email" value="{{ $user->email}}" required>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="admin-phone">Phone number</label>
                                    <input type="text" class="form-control" id="admin-phone" placeholder="Phone number" value="{{ $user->phone}}" pattern="^(\+?6?01)[0-46-9]-*[0-9]{7,8}$" oninvalid="setCustomValidity('The phone format should be 016-xxx4567')" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="float-right pr-4">
                        <button type="button" class="btn btn-secondary" onclick="hide_edit_form()">Cancel</button>
                        <button type="submit" class="btn btn-info">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>


@stop

@section('css')
<!-- <link rel="stylesheet" href="/css/admin_custom.css"> -->
@stop

@section('js')
<script>
    function show_edit_form() {
        $('#profile-edit-form').show();
    }

    function hide_edit_form() {
        $('#profile-edit-form').hide();
    }

    // Prevent letters in input form
    $(function() {
        var regExp = /[a-z]/i;
        $('#admin-phone').on('keydown keyup', function(e) {
            var value = String.fromCharCode(e.which) || e.key;

            // No letters allowed
            if (regExp.test(value)) {
                e.preventDefault();
                return false;
            }
        });
    });
</script>
@stop