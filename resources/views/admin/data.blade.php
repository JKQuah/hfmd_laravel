@extends('adminlte::page')

@section('title', 'Admin Dashboard')

@section('content_header')
<h1>Hand Foot Mouth Disease Data</h1>
@stop

@section('content')
<section class="content">
    <div class="container-fluid">
        <h3>Add new data</h3>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header bg-light">Upload an excel file to add new data</div>
                    <div class="card-body">
                        <form action="{{ route('data.import') }}" method="post" enctype="multipart/form-data" onsubmit="return verifyFile();">
                            <div class="input-group mb-3">
                                @csrf
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="importfile" id="excel-data-import" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                    <label class="custom-file-label" for="excel-data-import">Choose file</label>
                                </div>
                                <button class="btn btn-primary ml-3" type="submit" id="data-import-button" onclick="loading(this)">Upload</button>
                            </div>
                        </form>
                        <div id="data-import-filename">No file</div>
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <h3>Year Configuration</h3>
        <div class="row">
            <div class="col-sm-12 col-md-4">
                <div class="card">
                    <div class="card-header bg-secondary">Enable/Disable the <b>Notification Date of Cases</b></div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless table-striped ">
                            <thead>
                                <tr>
                                    <th scope="col" class="w-100">Year</th>
                                    <th scope="col" colspan="2" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($years as $year)
                                <tr>
                                    <th class="pt-2">{{$year}}</th>
                                    <?php
                                    $disabled = false;
                                    foreach ($disabled_years as $disabled_year) {
                                        if (intval($year) == $disabled_year) {
                                            $disabled = true;
                                            break;
                                        }
                                    }
                                    ?>
                                    @if($loop->last)
                                    <th colspan="2" class="text-center">Default</th>
                                    @elseif($disabled)
                                    <th><button class="btn btn-sm btn-success float-right w-100" id="enable_{{$year}}" onclick="enableYear(this, '{{$year}}')">Enable</button></th>
                                    <th><button class="btn btn-sm btn-danger w-100" id="disable_{{$year}}" onclick="disableYear(this, '{{$year}}')" disabled>Disabled</button></th>
                                    @else
                                    <th><button class="btn btn-sm btn-success float-right w-100" id="enable_{{$year}}" onclick="enableYear(this, '{{$year}}')" disabled>Enabled</button></th>
                                    <th><button class="btn btn-sm btn-danger w-100" id="disable_{{$year}}" onclick="disableYear(this, '{{$year}}')">Disable</button></th>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <h3 hidden>State Configuration</h3>
        <div class="row" hidden>
            <div class="col-sm-12 col-md-4">
                <div class="card">
                    <div class="card-header bg-light">Enable/Disable the <b>States</b></b></div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless table-striped ">
                            <thead>
                                <tr>
                                    <th scope="col" class="w-100">State</th>
                                    <th scope="col" colspan="2" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($states[0] as $state)
                                <tr>
                                    <th class="pt-2">{{$state}}</th>
                                    <?php
                                    $disabled = false;

                                    ?>
                                    @if($disabled)
                                    <th><button class="btn btn-sm btn-success float-right w-100" id="enable_{{$state}}" onclick="enableState(this, '{{$state}}')">Enable</button></th>
                                    <th><button class="btn btn-sm btn-danger w-100" id="disable_{{$state}}" onclick="disableState(this, '{{$state}}')" disabled>Disabled</button></th>
                                    @else
                                    <th><button class="btn btn-sm btn-success float-right w-100" id="enable_{{$state}}" onclick="enableState(this, '{{$state}}')" disabled>Enabled</button></th>
                                    <th><button class="btn btn-sm btn-danger w-100" id="disable_{{$state}}" onclick="disableState(this, '{{$state}}')">Disable</button></th>
                                    @endif
                                    <!-- <th><button class="btn btn-sm btn-success float-right" disabled>Enabled</button></th>
                                    <th><button class="btn btn-sm btn-danger">Disable</button></th> -->
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-4">
                <div class="card">
                    <div class="card-header bg-light">Enable/Disable the <b>States</b></b></div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless table-striped ">
                            <thead>
                                <tr>
                                    <th scope="col" class="w-100">State</th>
                                    <th scope="col" colspan="2" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($states[1] as $state)
                                <tr>
                                    <th class="pt-2">{{$state}}</th>
                                    <?php
                                    $disabled = false;

                                    ?>
                                    @if($disabled)
                                    <th><button class="btn btn-sm btn-success float-right w-100" id="enable_{{$state}}" onclick="enableState(this, '{{$state}}')">Enable</button></th>
                                    <th><button class="btn btn-sm btn-danger w-100" id="disable_{{$state}}" onclick="disableState(this, '{{$state}}')" disabled>Disabled</button></th>
                                    @else
                                    <th><button class="btn btn-sm btn-success float-right w-100" id="enable_{{$state}}" onclick="enableState(this, '{{$state}}')" disabled>Enabled</button></th>
                                    <th><button class="btn btn-sm btn-danger w-100" id="disable_{{$state}}" onclick="disableState(this, '{{$state}}')">Disable</button></th>
                                    @endif
                                    <!-- <th><button class="btn btn-sm btn-success float-right" disabled>Enabled</button></th>
                                    <th><button class="btn btn-sm btn-danger">Disable</button></th> -->
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>


@stop

@section('css')

@stop

@section('js')
<script>
    $(document).ready(function() {

        // Display file name
        var input = document.getElementById('excel-data-import');
        var infoArea = document.getElementById('data-import-filename');
        input.addEventListener('change', showFileName);

        function showFileName(event) {
            var input = event.srcElement;
            var fileName = input.files[0].name;
            infoArea.textContent = 'File name: ' + fileName;
        }


    });

    function loading(button){
        var loading = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="sr-only">Loading...</span>`
        $(button).html(loading);
    }

    function verifyFile() {
        if (document.getElementById("excel-data-import").files.length == 0) {
            Swal.fire({
                title: 'Oops...',
                html: 'No files selected...',
                icon: 'error',
            }).then((result) => {
                if(result.isConfirmed){
                    $('#data-import-button').html('Upload');
                }
            });
            
            return false;
        }
        return true;
    }

    function enableYear(button, year) {
        console.log(year)
        $.ajax({
            url: 'enableYear',
            type: 'POST',
            data: {
                year: year,
                _token: "{{ csrf_token() }}",
            },
            beforeSend: function() {
                var loading = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  <span class="sr-only">Loading...</span>`
                $(button).html(loading);
            },
            success: function(result) {
                if (result.status) {
                    $(button).html('Enabled');
                    $(button).prop("disabled", true);
                    $('#disable_' + year).prop("disabled", false);

                    Swal.fire({
                        icon: 'success',
                        title: result.success,
                        showConfirmButton: false,
                        timer: 2000
                    })
                } else {
                    $(button).html('Enable');
                    Swal.fire({
                        title: 'Oops...',
                        html: result.error,
                        icon: 'error'
                    })
                }
            },
        })
    }

    function disableYear(button, year) {
        Swal.fire({
            title: 'Are you sure?',
            text: "The data in " + year + " will be disabled...",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, disabled it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'disableYear',
                    type: 'POST',
                    data: {
                        year: year,
                        _token: "{{ csrf_token() }}",
                    },
                    beforeSend: function() {
                        var loading = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  <span class="sr-only">Loading...</span>`
                        $(button).html(loading);
                    },
                    success: function(result) {
                        if (result.status) {
                            $(button).html('Disabled');
                            $(button).prop("disabled", true);
                            $('#enable_' + year).prop("disabled", false);
                            Swal.fire({
                                icon: 'success',
                                title: result.success,
                                showConfirmButton: false,
                                timer: 2000
                            })
                        } else {
                            $(button).html('Disable');
                            Swal.fire({
                                title: 'Oops...',
                                html: result.error,
                                icon: 'error'
                            })
                        }

                    },
                    complete: function() {
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    }
                });
            }
        });
    }
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
        html: "{!!Session::get('error')!!}",
        icon: 'error'
    })
</script>
@endif
@stop