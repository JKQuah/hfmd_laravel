@extends('layouts.layout')

@section('title', "$state")

@section('css')
<!-- Styles -->
<link rel="stylesheet" type="text/css" href="/css/state.css">
@endsection

@section('content')
<div class="wrapper">
    <div class="row">
        <div class="col-sm-12 col-md-4">
            <div class="card border-0">
                <img src="{{ asset('img/states/'.$state.'.png') }}" alt="{{ $state }}" title="{{ $state }}" class="w-50 h-25 mx-auto mt-3 border border-dark">
                <div class="card-body mb-3">
                    <h6 class="card-title text-center">{{ ucwords(strtolower($state)) }}</h6>
                </div>
            </div>
            <ul class="list-group list-group-flush">
                @foreach($summary as $data)
                <li class="list-group-item">
                    <span class="float-left"><a class="text-dark" href="{{ route('data.show', ['year'=>$data['year'], 'state'=>$state]) }}">{{ $data['year'] }} <i class="fas fa-angle-right"></i></a></span>
                    <span class="float-right">{{ number_format($data['count']) }}
                        <small>({{ number_format($data['count']/$sum*100, 2) }}%)</small>

                        @if($data['grow'] == 'equal')
                        <span class="text-warning">
                            <i class="fas fa-minus"></i>
                        </span>
                        @elseif($data['grow'] == 'larger')
                        <span class="text-danger">
                            <i class="fas fa-arrow-circle-up"></i>
                        </span>
                        @elseif($data['grow'] == 'smaller')
                        <span class="text-success">
                            <i class="fas fa-arrow-circle-down"></i>
                        </span>
                        @endif
                    </span>
                </li>
                @endforeach
                <li class="list-group-item">
                    <span class="float-left">Total Cases</span>
                    <span class="float-right">{{ number_format($sum) }}</span>
                </li>
                <li class="list-group-item">
                    <small>
                        <span class="text-warning">
                            <i class="fas fa-minus"></i>
                            No comparison with the previous year
                        </span>
                    </small>
                    <br>
                    <small>
                        <span class="text-danger">
                            <i class="fas fa-arrow-circle-up"></i>
                            Cases rose compared to the previous year
                        </span>
                    </small>
                    <br>
                    <small>
                        <span class="text-success">
                            <i class="fas fa-arrow-circle-down"></i>
                            Cases reduced compared to the previous year
                        </span>
                    </small>
                </li>

            </ul>
        </div>
        <div class="col-sm-12 col-md-8">
            <div class="card card-body my-5" style="min-height: 407px">
                <div class="card-title">Annual Cases in {{ ucwords(strtolower($state)) }}</div>
                <div id="stateOverYear"></div>
                <div class="spinner" id="overall-spinner">
                    <div class="bounce1"></div>
                    <div class="bounce2"></div>
                    <div class="bounce3"></div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="title vertical-center pt-3">
        <h2>{{ ucwords(strtolower($state)) }} Districts</h2>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                @foreach($districts as $district)
                <a class="nav-link @if($loop->first) show active @endif" id="v-pills-{{$loop->iteration}}-tab" data-toggle="pill" href="#v-pills-{{$loop->iteration}}" role="tab" aria-controls="v-pills-{{$loop->iteration}}" aria-selected="true">{{ ucwords(strtolower($district)) }}</a>
                @endforeach
            </div>
        </div>
        <div class="col-md-9">
            <div class="tab-content" id="v-pills-tabContent">
                @foreach($districts as $district)
                <div class="tab-pane fade @if($loop->first) show active @endif" id="v-pills-{{$loop->iteration}}" role="tabpanel" aria-labelledby="v-pills-{{$loop->iteration}}-tab">
                    <div class="card card-body mb-3">
                        <div class="card-title">Annual Cases in {{ ucwords(strtolower($district)) }}</div>
                        <div id="district_{{ $district }}"></div>
                    </div>
                    <div class="card card-body mb-3">
                        <div class="card-title">Age Group Distribution in {{ ucwords(strtolower($district)) }}</div>
                        <small class="text-right text-secondary">* Click age group to hide</small>
                        <div id="age_{{ $district }}"></div>
                    </div>
                </div>
                @endforeach
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

    })
    $('#v-pills-tab a').on('click', function(e) {
        e.preventDefault()
        $(this).tab('show')
    })
    $.ajax({
        url: "{{ route('state.getStateOverYear') }}",
        type: 'GET',
        data: {
            state: "{{ $state }}"
        },
        beforeSend: function() {
            $('#overall-spinner').css('display', 'block');
        },
        success: function(chart) {
            $('#overall-spinner').css('display', 'none');
           
            var options = {
                series: chart.data,
                chart: {
                    type: 'area',
                    height: 400,
                    toolbar: {
                        tools: {
                            download: chart.download
                        }
                    },
                    zoom: {
                        enabled: true
                    },
                },
                stroke: {
                    width: 4
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
                        text: chart.xlabel,
                    },
                    categories: chart.category,
                },
                yaxis: {
                    title: {
                        text: chart.ylabel,
                    },
                },
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
                    enabledOnSeries: [0, 1],
                    formatter: function(val, opts) {
                        let percent = opts.w.globals.seriesPercent[opts.seriesIndex][opts.dataPointIndex];
                        return percent.toFixed(1) + "%";
                    },
                },
                responsive: [{
                    breakpoint: 1000,
                    options: {
                        legend: {
                            position: "bottom"
                        },

                    }
                }],
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
            var chart = new ApexCharts(document.getElementById('stateOverYear'), options);
            chart.render();
        }
    })
</script>

@foreach($districts as $district)
<script>
    $(document).ready(function() {
        setTimeout(() => {
            $.ajax({
                url: "{{ route('state.getDistrictOverYear') }}",
                type: 'GET',
                data: {
                    district: "{{ $district }}"
                },
                beforeSend: function() {
                    // $('#overall-spinner').css('display', 'block');
                },
                success: function(chart) {
                    // $('#overall-spinner').css('display', 'none');
                    var options = {
                        series: chart.data,
                        chart: {
                            type: 'area',
                            height: 350,
                            toolbar: {
                                tools: {
                                    download: chart.download
                                }
                            },
                            zoom: {
                                enabled: true
                            },
                        },
                        stroke: {
                            width: 4
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
                                text: chart.xlabel,
                            },
                            categories: chart.category,
                        },
                        yaxis: {
                            title: {
                                text: chart.ylabel,
                            },
                        },
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
                            enabledOnSeries: [0, 1],
                            formatter: function(val, opts) {
                                let percent = opts.w.globals.seriesPercent[opts.seriesIndex][opts.dataPointIndex];
                                return percent.toFixed(1) + "%";
                            },
                        },
                        responsive: [{
                            breakpoint: 1000,
                            options: {
                                legend: {
                                    position: "bottom"
                                },

                            }
                        }],
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
                    var chart = new ApexCharts(document.getElementById('district_{{ $district }}'), options);
                    chart.render();
                }
            });

            $.ajax({
                url: "{{ route('state.getAgeGroupOverYear') }}",
                type: 'GET',
                data: {
                    district: "{{ $district }}"
                },
                beforeSend: function() {
                    // $('#overall-spinner').css('display', 'block');
                },
                success: function(chart) {
                    // $('#overall-spinner').css('display', 'none');
                    var options = {
                        series: chart.data,
                        chart: {
                            type: 'bar',
                            height: 500,
                            stacked: true,
                            toolbar: {
                                tools: {
                                    download: chart.download
                                }
                            },
                            zoom: {
                                enabled: true
                            },
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                            },
                        },
                        xaxis: {
                            title: {
                                text: chart.xlabel,
                            },
                            categories: chart.category,
                        },
                        yaxis: {
                            title: {
                                text: chart.ylabel,
                            },
                        },
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

                        },
                        responsive: [{
                            breakpoint: 1000,
                            options: {
                                legend: {
                                    position: "bottom"
                                },

                            }
                        }],
                        dataLabels: {
                            enabled: true,
                            formatter: function(val, opts) {
                                let percent = opts.w.globals.seriesPercent[opts.seriesIndex][opts.dataPointIndex];
                                return percent.toFixed(1) + "%";
                            },
                        },
                        tooltip: {
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
                    var chart = new ApexCharts(document.getElementById('age_{{ $district }}'), options);
                    chart.render();
                }
            });
        }, 200);

    });
</script>
@endforeach
@endsection