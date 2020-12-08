@extends('adminlte::page')

@section('title', 'Admin Dashboard')

@section('content_header')
<h1>HFMD Dashboard Control Panel</h1>
@stop

@section('content')
<section class="content">
    <div class="container-fluid">
        <!-- Users summary -->
        <h3>Users Summary</h3>
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

        <!-- Charts Summary -->
        <h3>Charts Summary</h3>
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-secondary">
                    <div class="inner">
                        <h3>2</h3>
                        <p>Bar Charts</p>
                    </div>
                    <div class="icon">
                        <i class="far fa-chart-bar"></i>
                    </div>
                    <a href="{{ route('admin.user_list') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-secondary">
                    <div class="inner">
                        <h3>2</h3>
                        <p>Pie Charts</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <a href="{{ route('admin.admin_list') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-secondary">
                    <div class="inner">
                        <h3>3</h3>
                        <p>Line Charts</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <a href="{{ route('admin.user_list') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-secondary">
                    <div class="inner">
                        <h3>1</h3>
                        <p>Heatmap Charts</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-chart"></i>
                    </div>
                    <a href="{{ route('admin.user_list') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

        <!-- FAQ Summary -->
        <h3>FAQ Summary</h3>
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>{{ number_format($active_faq) }}</h3>
                        <p>Total Number of Active FAQ</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-info"></i>
                    </div>
                    <a href="{{ route('admin.faq') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
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