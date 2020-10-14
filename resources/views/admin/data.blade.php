@extends('adminlte::page')

@section('title', 'Admin Dashboard')

@section('content_header')
<h1>Hand Foot Mouth Disease Data</h1>
@stop

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <!-- Map card -->
                <div class="card bg-gradient-light">
                    <div class="card-header border-0">
                        <h3 class="card-title">
                            <i class="fas fa-map-marker-alt mr-1"></i>
                            Malaysia Map
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-primary btn-sm daterange" data-toggle="tooltip" title="Date range">
                                <i class="far fa-calendar-alt"></i>
                            </button>
                            <button type="button" class="btn btn-primary btn-sm" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="map" height="225"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6">
            <!-- TODO::Other data -->
            </div>
        </div>
    </div>
</section>


@stop

@section('css')
<!-- <link rel="stylesheet" href="/css/admin_custom.css"> -->
<link href="{{ asset('dist/jqvmap.css') }}" media="screen" rel="stylesheet" type="text/css">
<style>
    #map tspan{
        font-size: 10px;
    }
</style>
@stop

@section('js')
<!-- JQVMap -->
<script type="text/javascript" src="{{ asset('js/mapdata.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/countrymap.js') }}"></script>

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