@extends('layouts.layout')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.4.3/css/ol.css" type="text/css">
<style>
    .map {
    height: 400px;
    width: 100%;
    }
</style>
<script src="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.4.3/build/ol.js"></script>

<!-- Styles -->
<link rel="stylesheet" type="text/css" href="/css/dashboard.css">

@section('home-active')
<li class="nav-item active">
@endsection

@section('content')
<div class="title vertical-center">
    <h2>Overview</h2>
</div>
<div class="wrapper">

    <div class="row">
        <div class="col-xl-5 col-sm-12">
            <div class="col-md-12 case-box">
                <div class="subtitle">Total cases from {{$overall['min_year']}} to {{$overall['max_year']}}</div>
                <div class="row">
                    <div class="col total-case">
                        <div class="title-case">{{number_format($overall['total'])}}</div>
                        <div class="subtitle-case">Total</div>
                    </div>
                    <div class="col infected-case">
                        <div class="title-case">{{number_format($overall['infected'])}}</div>
                        <div class="subtitle-case">Infected</div>
                    </div>
                    <div class="col death-case">
                        <div class="title-case">{{number_format($overall['death'])}}</div>
                        <div class="subtitle-case">Death</div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 case-box">
                <div class="spinner" id="overall-spinner">
                    <div class="bounce1"></div>
                    <div class="bounce2"></div>
                    <div class="bounce3"></div>
                </div>
                <div class="subtitle">
                    Total cases over years
                    <div id="more_total" class="more_vert"><i class="fal fa-ellipsis-h"></i></div>
                    <div class="more_option rounded">
                        <a href="" class="border-bottom" id="all_cases_jpg" onclick="export_as_jpg(this)">Export as JPG</a><br>
                        <a href="" class="border-bottom">Export as PNG</a><br>
                        <a href="">Export as data</a>
                    </div>
                </div>
                <canvas id="lineChart_all_cases" style="min-height: 195px;" height="200"></canvas>
                <input type="radio" name="overall_chart" id="overall_line" onchange="plotOverallChart('line')" checked>
                <label for="overall_line" class="pr-3">Line</label>
                <input type="radio" name="overall_chart" id="overall_bar" onchange="plotOverallChart('bar')">
                <label for="overall_bar">Bar</label>
            </div>
        </div>
        <div class="col-xl-7 col-sm-12 case-box">
            <div class="subtitle">Malaysia Geographical Map</div>
            <div id="map" class="map"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6 col-sm-12">
            <div class="case-box">
                <!-- Slider main container -->
                <div class="swiper-container">
                    <!-- Additional required wrapper -->
                    <div class="swiper-wrapper">
                        <!-- Slides -->
                        <div class="swiper-slide">
                            @for($i = 0; $i < 5; $i++) <div class="row">
                                <div class="col vertical-center h5 mb-0">{{ $summary[$i]['year'] }}</div>
                                <div class="col total-case">
                                    <div class="title-case">{{ number_format($summary[$i]['total']) }}</div>
                                    <div class="subtitle-case">Total</div>
                                </div>
                                <div class="col infected-case">
                                    <div class="title-case">{{ number_format($summary[$i]['infected']) }}</div>
                                    <div class="subtitle-case">Infected</div>
                                </div>
                                <div class="col death-case">
                                    <div class="title-case">{{ number_format($summary[$i]['death']) }}</div>
                                    <div class="subtitle-case">Death</div>
                                </div>
                        </div>
                        @if($i < 4)<hr>@else<br>@endif
                            @endfor
                    </div>
                    <div class="swiper-slide">
                        @for($i = 5; $i < count($summary); $i++) <div class="row">
                            <div class="col vertical-center h5 mb-0">{{ $summary[$i]['year'] }}</div>
                            <div class="col total-case">
                                <div class="title-case">{{ number_format($summary[$i]['total']) }}</div>
                                <div class="subtitle-case">Total</div>
                            </div>
                            <div class="col infected-case">
                                <div class="title-case">{{ number_format($summary[$i]['infected']) }}</div>
                                <div class="subtitle-case">Infected</div>
                            </div>
                            <div class="col death-case">
                                <div class="title-case">{{ number_format($summary[$i]['death']) }}</div>
                                <div class="subtitle-case">Death</div>
                            </div>
                    </div>
                    @if($i < 9)<hr>@else<br>@endif
                        @endfor
                </div>
            </div>
            <!-- If we need scrollbar -->
            <div class="swiper-scrollbar"></div>
        </div>

    </div>
</div>
<div class="col-xl-6 col-sm-12">
    <div class="col-md-12 case-box">
        <div class="spinner" id="age-spinner">
            <div class="bounce1"></div>
            <div class="bounce2"></div>
            <div class="bounce3"></div>
        </div>
        <div class="subtitle">
            Age group under 3 years old
            <div id="more_age" class="more_vert"><i class="fal fa-ellipsis-h"></i></div>
            <div class="more_option rounded">
                <a href="" class="border-bottom" id="all_cases_jpg" onclick="export_as_jpg(this)">Export as JPG</a><br>
                <a href="" class="border-bottom">Export as PNG</a><br>
                <a href="">Export as data</a>
            </div>
        </div>
        <canvas id="lineChart_age_group" style="min-height: 195px;" height="200"></canvas>
        <input type="radio" name="age_chart" id="age_line" onchange="plotAgeChart('line')">
        <label for="age_line" class="pr-3">Line</label>
        <input type="radio" name="age_chart" id="age_bar" onchange="plotAgeChart('bar')" checked>
        <label for="age_bar">Bar</label>
    </div>
    <div class="col-md-12 case-box" hidden>
        <div class="subtitle">
            Actual vs Prediction
            <div id="more_prediction" class="more_vert"><i class="fal fa-ellipsis-h"></i></div>
            <div class="more_option rounded">
                <a href="" class="border-bottom" id="all_cases_jpg" onclick="export_as_jpg(this)">Export as JPG</a><br>
                <a href="" class="border-bottom">Export as PNG</a><br>
                <a href="">Export as data</a>
            </div>
        </div>
        <canvas id="lineChart_prediction" style="min-height: 195px;" height="200"></canvas>
        <input type="radio" name="prediction_chart" id="prediction_line" onchange="plotPredictionChart('line')" checked>
        <label for="age_line" class="pr-3">Line</label>
        <input type="radio" name="prediction_chart" id="prediction_bar" onchange="plotPredictionChart('bar')">
        <label for="age_bar">Bar</label>
    </div>
</div>
</div>
</div>


@endsection
@section('footer')
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
    var mySwiper = new Swiper('.swiper-container', {
        // Optional parameters
        direction: 'horizontal',

        // And if we need scrollbar
        scrollbar: {
            el: '.swiper-scrollbar',
        },
    })

    $(document).ready(function() {
        // default chart plotted for overall
        plotOverallChart('line');
        plotAgeChart('bar');
    });

    function plotOverallChart(charttype) {
        var myOverallChart;
        $.ajax({
            type: 'GET',
            url: '{{ route("overview.getOverallChart") }}',
            beforeSend: function() {
                $('#overall-spinner').css('display', 'block');
            },
            success: function(chart) {
                if(myOverallChart){
                    myOverallChart.destroy();
                }
                $('#overall-spinner').css('display', 'none');
                var ctx_line = document.getElementById('lineChart_all_cases').getContext('2d');
                myOverallChart = new Chart(ctx_line, {
                    type: charttype,
                    backgroundColor: 'white',
                    data: {
                        labels: chart.years,
                        datasets: [{
                            label: 'Total',
                            backgroundColor: '#F2C94C',
                            borderColor: '#F2C94C',
                            data: chart.total,
                            fill: false,
                        }, {
                            label: 'Infected',
                            backgroundColor: '#6fcf97',
                            borderColor: '#6fcf97',
                            data: chart.infected,
                            fill: false,
                        }, {
                            label: 'Deaths',
                            backgroundColor: '#eb5757',
                            borderColor: '#eb5757',
                            data: chart.death,
                            fill: false,
                        }],

                    },
                    options: {
                        legend: {
                            display: true,
                            position: 'bottom',
                        },
                        scales: {
                            yAxes: [{
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Total number of cases'
                                },
                                ticks: {
                                    stepSize: 10000,
                                },
                            }],
                            xAxes: [{
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Years'
                                }
                            }],
                        },
                        tooltips: {
                            callbacks: {
                                label: function(tooltipItem, data) {
                                    return tooltipItem.yLabel + ' cases';

                                }
                            }
                        }
                    }
                });
                
            },
            error: function(xhr, status, error) {

            }

        });
    }

    function plotAgeChart(charttype) {
        $.ajax({
            type: 'GET',
            url: '{{ route("overview.getAgeChart") }}',
            beforeSend: function() {
                $('#age-spinner').css('display', 'block');
            },
            success: function(chart) {
                $('#age-spinner').css('display', 'none');
                console.log(chart)
                var ctx_line = document.getElementById('lineChart_age_group').getContext('2d');
                var myLineChart = new Chart(ctx_line, {
                    type: charttype,
                    backgroundColor: 'white',
                    data: {
                        labels: chart.years,
                        datasets: [{
                            label: '0 - 1',
                            backgroundColor: '#FFE38C',
                            borderColor: '#FFE38C',
                            data: chart.children_below_1,
                            fill: false,
                        }, {
                            label: '1 - 2',
                            backgroundColor: '#F2C94C',
                            borderColor: '#F2C94C',
                            data: chart.children_below_2,
                            fill: false,
                        }, {
                            label: '2 - 3',
                            backgroundColor: '#BF9922',
                            borderColor: '#BF9922',
                            data: chart.children_below_3,
                            fill: false,
                        }],

                    },
                    options: {
                        legend: {
                            display: true,
                            position: 'bottom',
                        },
                        scales: {
                            yAxes: [{
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Total number of cases'
                                },
                                ticks: {
                                    stepSize: 2000,
                                },
                            }],
                            xAxes: [{
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Age Group'
                                }
                            }],
                        },
                        tooltips: {
                            callbacks: {
                                label: function(tooltipItem, data) {
                                    return tooltipItem.yLabel + ' cases';

                                }
                            }
                        }
                    }
                });
            },
            error: function(xhr, status, error) {

            }

        });
    }

    function export_as_jpg(chart) {
        var url_base64jp = document.getElementById("lineChart_all_cases").toDataURL("image/jpg");
        var a = document.getElementById("all_cases_jpg");
        a.href = url_base64jp;
    }
</script>
<script src="{{asset('js/map.js')}}"></script>
@endsection