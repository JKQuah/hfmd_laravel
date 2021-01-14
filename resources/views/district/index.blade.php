@extends('layouts.layout')

@section('title', "$district")

@section('css')
<link rel="stylesheet" type="text/css" href="/css/data.css">
@endsection

@section('content')
<div class="wrapper">
    <h2 class="text-center">Hand Foot Mouth Disease Cases</h2>
    <h2 class="text-center">at <a href="{{ route('data.show', ['year'=>$year, 'state'=>$state]) }}">{{ ucwords(strtolower($state)) }}</a>, {{ ucwords(strtolower($district)) }}
        <button type="button" class="btn dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-chevron-down"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-right">
            <!-- Dropdown menu links -->
            @foreach($district_list as $this_district)
            <a class="dropdown-item text-center" href="{{ route('district.index', ['year'=>$year, 'state'=>$state, 'district'=>$this_district['district']]) }}">{{ ucwords(strtolower($district)) }}</a>
            @endforeach
        </div>
    </h2>
    <h4 class="text-center mb-3">in {{ $year }}</h4>
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
                                <h2 class="card-title text-center font-weight-bold"><i style="font-size:30px;color:#2768A4" class="fa">&#xf222;</i> {{ $single_district['i_male'] }}</h2>
                                @if($single_district['i_male'] == 0)
                                <p class="card-text text-center">Male (0.0%)</p>
                                @else
                                <p class="card-text text-center">Male ({{ number_format(($single_district['i_male'] * 100 / $single_district['infected']), 1) }}%)</p>
                                @endif
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <h2 class="card-title text-center font-weight-bold"><i style="font-size:30px;color:#B85887" class="fa">&#xf221;</i> {{ $single_district['i_female'] }}</h2>
                                @if($single_district['i_female'] == 0)
                                <p class="card-text text-center">Female (0.0%)</p>
                                @else
                                <p class="card-text text-center">Female ({{ number_format(($single_district['i_female'] * 100 / $single_district['infected']), 1) }}%)</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 mb-3">
                                <h2 class="card-title text-center font-weight-bold text-danger">{{ $single_district['deaths'] }}</h2>
                                <p class="card-text text-center text-danger">Death</p>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <h2 class="card-title text-center font-weight-bold"><i style="font-size:30px;color:#2768A4" class="fa">&#xf222;</i> {{ $single_district['d_male'] }}</h2>
                                @if($single_district['d_male'] == 0)
                                <p class="card-text text-center">Male (0.0%)</p>
                                @else
                                <p class="card-text text-center">Male ({{ number_format(($single_district['d_male'] * 100 / $single_district['deaths']), 1) }}%)</p>
                                @endif
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <h2 class="card-title text-center font-weight-bold"><i style="font-size:30px;color:#B85887" class="fa">&#xf221;</i> {{ $single_district['d_female'] }}</h2>
                                @if($single_district['d_female'] == 0)
                                <p class="card-text text-center">Female (0.0%)</p>
                                @else
                                <p class="card-text text-center">Female ({{ number_format(($single_district['d_female'] * 100 / $single_district['deaths']), 1) }}%)</p>
                                @endif
                            </div>
                        </div>
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
    <div class="jumbotron">
        <div class="row">
            <div class="col-sm-12">
                <h4>Age Group Distribution by District</h4>
                <p>at {{ $district }} in {{ $year }}</p>
                <div id="lineChart_age"></div>
                <small class="text-secondary">*Percentage indicated the rate of infected cases</small>
            </div>

        </div>
    </div>

    <div class="jumbotron">
        <div class="row">
            <div class="col-sm-12">
                <h4>Monthly Distribution by District</h4>
                <p>at {{ $district }} in {{ $year }}</p>
                <div id="lineChart_month"></div>
                <small class="text-secondary">*Percentage indicated the rate of infected cases</small>
            </div>
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
                year: "{{ $year }}",
                district: "{{ $district }}",
            },
            url: '{{ route("district.getAgeGroupChart") }}',
            beforeSend: function() {},
            success: function(chart) {
                var options = {
                    series: chart.data,
                    chart: {
                        height: 350,
                        type: 'line',
                        stacked: false,
                    },
                    stroke: {
                        width: [0, 3],
                        curve: 'smooth',
                    },
                    toolbar: {
                        tools: {
                            download: chart.download
                        }
                    },
                    legend: {
                        position: 'top',
                    },
                    plotOptions: {
                        bar: {
                            columnWidth: '50%'
                        }
                    },
                    fill: {
                        opacity: [0.85, 1],
                        gradient: {
                            inverseColors: false,
                            shade: 'light',
                            type: "vertical",
                            opacityFrom: 0.85,
                            opacityTo: 0.55,
                            stops: [0, 100, 100, 100]
                        }
                    },
                    labels: ['Age 0', 'Age 1', 'Age 2', 'Age 3', 'Age 4', 'Age 5', 'Age 6', 'Age 7', 'Age 8', 'Age 9', 'Age 10', 'Age 11', 'Age 12'],
                    markers: {
                        size: 0
                    },
                    xaxis: {
                        title: {
                            text: chart.xlabel,
                        },
                    },
                    yaxis: {
                        title: {
                            text: chart.ylabel,
                        },
                        min: 0,
                        forceNiceScale: true,
                    },
                    tooltip: {
                        shared: true,
                        intersect: false,
                        y: {
                            formatter: function(y) {
                                if (typeof y !== "undefined") {
                                    return y.toFixed(0) + " cases";
                                }
                                return y;

                            }
                        }
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
                        width: [0, 3],
                        curve: 'smooth'
                    },
                    toolbar: {
                        tools: {
                            download: chart.download
                        }
                    },
                    legend: {
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
                        min: 0,
                        forceNiceScale: true,
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
                    },
                    tooltip: {
                        shared: true,
                        intersect: false,
                        y: {
                            formatter: function(y) {
                                if (typeof y !== "undefined") {
                                    return y.toFixed(0) + " cases";
                                }
                                return y;

                            }
                        }
                    },
                };
                var chart = new ApexCharts(document.getElementById('lineChart_month'), options);
                chart.render();
            },
        });

    }
</script>
@endsection