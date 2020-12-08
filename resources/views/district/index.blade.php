@extends('layouts.layout')

@section('content')
<link rel="stylesheet" type="text/css" href="/css/data.css">

<div class="wrapper">
    <h2 class="text-center">HFMD Cases in <a href="{{ route('data.show', ['year'=>$year, 'state'=>$state]) }}">{{ $state }}</a>, {{ $district }}
        <button type="button" class="btn dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-chevron-down"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-right">
            <!-- Dropdown menu links -->
            @foreach($district_list as $this_district)
            <a class="dropdown-item text-center" href="{{ route('district.index', ['year'=>$year, 'state'=>$state, 'district'=>$this_district['district']]) }}">{{ $this_district['district'] }}</a>
            @endforeach
        </div>
    </h2>
    <h5 class="text-center mb-3">at {{ $year }}</h5>
    <!-- data total -->
    <div class="row">
        <div class="col-sm-12">
            <div class="jumbotron">
                <div class="row">
                    <div class="col-sm-12 col-md-4 border-right border-warning">
                        <div class="row">
                            <div class="col-sm-0 col-md-12"><br><br></div>
                            <div class="col-sm-12 col-md-12">
                                <h1 class="card-title text-center font-weight-bold">{{ $single_district['infected'] + $single_district['deaths'] }}</h1>
                                <p class="card-text text-center">Total Cases</p>
                            </div>
                            <div class="col-sm-0 col-md-12"> </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4 border-right border-warning">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 mb-3">
                                <h2 class="card-title text-center font-weight-bold">{{ $single_district['infected'] }}</h2>
                                <p class="card-text text-center">Infected</p>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <h2 class="card-title text-center font-weight-bold">{{ $single_district['i_male'] }}</h2>
                                <p class="card-text text-center">Male</p>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <h2 class="card-title text-center font-weight-bold">{{ $single_district['i_female'] }}</h2>
                                <p class="card-text text-center">Female</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 mb-3">
                                <h2 class="card-title text-center font-weight-bold">{{ $single_district['deaths'] }}</h2>
                                <p class="card-text text-center">Death</p>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <h2 class="card-title text-center font-weight-bold">{{ $single_district['d_male'] }}</h2>
                                <p class="card-text text-center">Male</p>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <h2 class="card-title text-center font-weight-bold">{{ $single_district['d_female'] }}</h2>
                                <p class="card-text text-center">Female</p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-12 border-right border-warning">
                        <h4>Age Group Distribution</h4>
                        <p>at {{ $district }} in {{ $year }}</p>
                        <div id="lineChart_age"></div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <h4>Monthly Distribution</h4>
                        <p>at {{ $district }} in {{ $year }}</p>
                        <div id="lineChart_month"></div>
                    </div>
                </div>
            </div>
            <!-- <div class="row">
                @foreach($data_district_year as $data)
                @if($data['year'] != $year)
                <div class="col-sm-12 col-md-4">
                    <div class="card shadow mb-3">
                        <h5 class="card-header">Summary in {{ $data['year'] }}</h5>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 mb-3">
                                    <h5 class="card-title text-center font-weight-bold">{{ $data['infected']+$data['deaths'] }}</h5>
                                    <p class="card-text text-center">Total Cases</p>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <h5 class="card-title text-center font-weight-bold">{{ $data['infected'] }}</h5>
                                    <p class="card-text text-center">Infected</p>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <h5 class="card-title text-center font-weight-bold">{{ $data['deaths'] }}</h5>
                                    <p class="card-text text-center">Death</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-6 col-md-6">
                                    <h5 class="card-title text-center font-weight-bold">{{ $data['male'] }}</h5>
                                    <p class="card-text text-center">Male</p>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <h5 class="card-title text-center font-weight-bold">{{ $data['female'] }}</h5>
                                    <p class="card-text text-center">Female</p>
                                </div>
                            </div>
                            <br>
                            <div class="text-center">
                                <a href="{{ route('district.index', ['year'=>$data['year'] , 'state'=>$state, 'district'=>$district]) }}" class="btn btn-warning font-weight-bold">Go to {{ $data['year'] }}</a>
                            </div>
                        </div>
                    </div>
                    <br>
                </div>
                @endif
                @endforeach
            </div> -->
            <br>
        </div>
    </div>
</div>
@endsection
@section('js')
<!-- Graphical data by ApexChart -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    $(document).ready(function() {
        plotAgeGroupChart();
        plotMonthlyChart();
    });

    function plotAgeGroupChart() {
        $.ajax({
            type: 'GET',
            data: {
                year: "2010",
                state: "JOHOR",
                gender: 'male'
            },
            url: '{{ route("state.getGenderChart") }}',
            beforeSend: function() {},
            success: function(chart) {
                var options = {
                    series: [{
                        name: 'TEAM A',
                        type: 'column',
                        data: [23, 11, 22, 27, 13, 22, 37, 21, 44, 22, 30]
                    }, {
                        name: 'TEAM B',
                        type: 'area',
                        data: [44, 55, 41, 67, 22, 43, 21, 41, 56, 27, 43]
                    }, {
                        name: 'TEAM C',
                        type: 'line',
                        data: [30, 25, 36, 30, 45, 35, 64, 52, 59, 36, 39]
                    }],
                    chart: {
                        height: 350,
                        type: 'line',
                        stacked: false,
                    },
                    stroke: {
                        width: [0, 2, 5],
                        curve: 'smooth'
                    },
                    plotOptions: {
                        bar: {
                            columnWidth: '50%'
                        }
                    },

                    fill: {
                        opacity: [0.85, 0.25, 1],
                        gradient: {
                            inverseColors: false,
                            shade: 'light',
                            type: "vertical",
                            opacityFrom: 0.85,
                            opacityTo: 0.55,
                            stops: [0, 100, 100, 100]
                        }
                    },
                    labels: ['01/01/2003', '02/01/2003', '03/01/2003', '04/01/2003', '05/01/2003', '06/01/2003', '07/01/2003',
                        '08/01/2003', '09/01/2003', '10/01/2003', '11/01/2003'
                    ],
                    markers: {
                        size: 0
                    },
                    xaxis: {
                        type: 'datetime'
                    },
                    yaxis: {
                        title: {
                            text: 'Points',
                        },
                        min: 0
                    },
                    tooltip: {
                        shared: true,
                        intersect: false,
                        y: {
                            formatter: function(y) {
                                if (typeof y !== "undefined") {
                                    return y.toFixed(0) + " points";
                                }
                                return y;

                            }
                        }
                    }
                };

                var chart = new ApexCharts(document.getElementById('lineChart_age'), options);
                chart.render();

            },
        });
    }

    function plotMonthlyChart() {
        $.ajax({
            type: 'GET',
            data: {
                year: "{{ $year }}",
                district: "{{ $district }}",
            },
            url: '{{ route("district.getMonthlyChart") }}',
            beforeSend: function() {},
            success: function(chart) {
                var options = {
                    series: chart.data,
                    chart: {
                        height: 350,
                        toolbar: {
                            export: {
                                csv: {
                                    filename: '{{ strtolower($district) }}-{{ $year }}',
                                },
                            },
                        }
                    },
                    stroke: {
                        width: [0, 5],
                        curve: 'smooth'
                    },
                    legend : {
                        position: 'top',
                    },
                    xaxis: {
                        title: {
                            text: chart.xlabel,
                        },
                        categories: chart.categories
                    },
                    yaxis: {
                        title: {
                            text: chart.ylabel,
                        },
                        categories: chart.categories
                    },
                    dataLabels: {
                        enabled: true,
                        enabledOnSeries: [0],
                        formatter: function(val, opts) {
                            let percent = opts.w.globals.seriesPercent[opts.seriesIndex][opts.dataPointIndex];
                            return percent.toFixed(1) + "%";
                        },
                        offsetY: -10,
                        style: {
                            fontSize: '12px',
                            colors: ["#304758"]
                        },
                    },
                    markers: {
                        size: 4
                    }
                };
                var chart = new ApexCharts(document.getElementById('lineChart_month'), options);
                chart.render();
            },
        });

    }
</script>
@endsection