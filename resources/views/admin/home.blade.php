@extends('adminlte::page')

@section('title', 'Admin Dashboard')

@section('content_header')
<h1>All Users</h1>
@stop

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $new_user_count }}</h3>
                        <p>User Registrations</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <a href="{{ route('admin.user_list') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $admin_count }}</h3>
                        <p>Active Admins</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <a href="{{ route('admin.admin_list') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $staff_count }}</h3>
                        <p>Active Staffs</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <a href="{{ route('admin.staff_list') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $active_user_count }}</h3>
                        <p>Active Users</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <a href="{{ route('admin.user_list') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
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
    
</script>
@stop