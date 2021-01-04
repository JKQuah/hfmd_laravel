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
                
               
            </div>
            <div class="col-sm-12 col-md-6">
            <!-- TODO::Other data -->
            </div>
        </div>
    </div>
</section>


@stop

@section('css')

@stop

@section('js')


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