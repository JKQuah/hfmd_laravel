@extends('adminlte::page')

@section('title', 'Admin Dashboard')

@section('content_header')
<h1>Manage Chart</h1>
@stop

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <canvas id="myLocalityChart" height="100"></canvas>
            </div>
            <div class="col-md-4"></div>
        </div>
        
    </div>
</section>
@stop

@section('css')
<!-- <link rel="stylesheet" href="/css/admin_custom.css"> -->
@stop

@section('js')
<script>
    $.ajax({
        type: 'GET',
        data: {
            chartdata: '{!! json_encode($monthly_analysis ?? "")  !!}',
        },
        url: '{{ route("state.getLocalityChart") }}',
        beforeSend: function() {},
        success: function(chart) {

            var ctx_bar = document.getElementById('myLocalityChart').getContext('2d');
            myLocalityChart = new Chart(ctx_bar, {
                type: chart.type,
                backgroundColor: 'white',
                data: {
                    labels: chart.labels,
                    datasets: chart.data,
                },
                options: {
                    legend: {
                        display: true,
                        position: 'right',
                    },
                    scales: {
                        yAxes: [{
                            scaleLabel: {
                                display: true,
                                labelString: chart.ylabel
                            },
                        }],
                        xAxes: [{
                            scaleLabel: {
                                display: true,
                                labelString: chart.xlabel
                            }
                        }],
                    },
                }
            });

        },
        error: function(xhr, status, error) {

        }
    });
</script>
@stop