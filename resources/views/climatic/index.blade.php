@extends('layouts.layout')
@section('climatic-active')
<li class="nav-item active">
@endsection

@section('title', 'Climatic')

@section('css')
<!-- Styles -->
<link rel="stylesheet" type="text/css" href="/css/climatic.css">
@endsection

@section('content')
<div class="wrapper">
    <div class="text-center">
        <h2>Climatic Analysis at {{ $this_state }}
            <button type="button" class="btn dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-right">
                <!-- Dropdown menu links -->
                @foreach($states as $msia_state)
                <a class="dropdown-item text-center" href="{{ route('climatic', ['state'=>$msia_state, 'year'=>$this_year]) }}">{{ $msia_state }}</a>
                @endforeach
            </div>
        </h2>
        <h5>in {{ $this_year }}
            <button type="button" class="btn dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-right" style="left:0; min-width: 8rem;">
                <!-- Dropdown menu links -->
                @foreach($years as $year)
                <a class="dropdown-item text-center" href="{{ route('climatic', ['year'=>$year, 'state'=>$this_state]) }}">{{ $year }}</a>
                @endforeach
            </div>
        </h5>
    </div>

    <div class="average-wrapper">
        <ul class="list-group list-group-flush">
            <li class="list-group-item">
                <span class="float-left">Average Mean Temperature(°C) </span>
                <span class="float-right">{{ number_format($mean_temp, 1) }}°C <i class="fas fa-thermometer-quarter" style="padding: 0 3px;"></i></span>
            </li>
            <li class="list-group-item">
                <span class="float-left">Average Rainfall Amount<small>(mm)</small></span>
                <span class="float-right">{{ number_format($mean_rainAmount, 1) }}<small>mm</small> <i class="fas fa-cloud-rain"></i></span>
            </li>
            <li class="list-group-item">
                <span class="float-left">Average Number of Rain Day</span>
                <span class="float-right">{{ number_format($mean_rainDay, 1) }} <i class="fas fa-cloud-sun-rain"></i></span>
            </li>
            <li class="list-group-item">
                <span class="float-left">Average Relative Humidity</span>
                <span class="float-right">{{ number_format($mean_humidity, 1) }}</span>
            </li>
        </ul>
    </div>

    <div class="my-5 text-center link-data">
        <a href="{{ route('data.show', ['year'=>$this_year, 'state'=>$this_state]) }}"><i class="fas fa-table"></i> Go to numerical data</a>
    </div>
    <div class="row mt-5">
        <div class="col-sm-12 col-md-1"></div>
        <div class="col-sm-12 col-md-10">
            <h5>24h Mean Temprature(°C) at {{ $this_state }}</h5>
            <h6>in {{ $this_year }}</h6>
            <div id="temperature"></div>
            <hr>
            <h5>Monthly Rainfall Amount<small>(mm)</small> at {{ $this_state }}</h5>
            <h6>in {{ $this_year }}</h6>
            <div id="rainfall"></div>
            <hr>
            <h5>Number of Rainday at {{ $this_state }}</h5>
            <h6>in {{ $this_year }}</h6>
            <div id="rainday"></div>
            <hr>
            <h5>Relative Humidity at {{ $this_state }}</h5>
            <h6>in {{ $this_year }}</h6>
            <div id="humidity"></div>
        </div>
        <div class="col-sm-12 col-md-1"></div>
    </div>
</div>

@endsection



@section('js')
<!-- Graphical data by ApexChart -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    $(document).ready(function() {
        getClimaticChart('temperature', '24h Mean Temperature (°C)', 'temperature');
        getClimaticChart('rainfall', 'Monthly Rainfall Amount', 'rainfall');
        getClimaticChart('rainday', 'Number of Rainday', 'rainday');
        getClimaticChart('humidity', 'Relative Humidity', 'humidity');
    })

    function getClimaticChart(type, ylabel, chartId) {
        $.ajax({
            url: "{{route('climatic.getClimaticChart')}}",
            type: 'GET',
            data: {
                year: "{{ $this_year }}",
                state: "{{ $this_state }}",
                type: type
            },
            success: function(chart) {
                var options = {
                    series: chart.data,
                    chart: {
                        type: 'line',
                        height: 350,
                        toolbar: {
                            show: true
                        },
                        zoom: {
                            enabled: true
                        },
                    },
                    stroke: {
                        width: [0, 4]
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            dataLabels: {
                                position: 'bottom'
                            }
                        },
                    },
                    xaxis: {
                        title: {
                            text: "{{ $this_year }}",
                        },
                        categories: chart.category,
                    },
                    yaxis: [{
                        title: {
                            text: 'Total number of cases',
                        },

                    }, {
                        opposite: true,
                        title: {
                            text: ylabel
                        }
                    }],
                    legend: {
                        position: 'right',
                    },
                    fill: {
                        opacity: 1
                    },
                    markers: {
                        size: 4
                    },
                    dataLabels: {
                        enabled: true,
                        enabledOnSeries: [0],
                    },
                    responsive: [{
                        breakpoint: 1000,
                        options: {
                            legend: {
                                position: "bottom"
                            },
                           
                        }
                    }]
                };
                var chart = new ApexCharts(document.getElementById(chartId), options);
                chart.render();
            }
        })
    }
</script>
@endsection