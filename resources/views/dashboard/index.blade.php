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
    <div class="wrapper">
        <div class="title vertical-center">
            <h2>Overview</h2>
        </div>
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
                        <!-- <div id="more_total" class="more_vert"><i class="fal fa-ellipsis-h"></i></div>
                        <div class="more_option rounded">
                            <a href="" class="border-bottom" id="all_cases_jpg" onclick="export_as_jpg(this)">Export as JPG</a><br>
                            <a href="" class="border-bottom">Export as PNG</a><br>
                            <a href="">Export as data</a>
                        </div> -->
                    </div>
                    <div id="lineChart_all_cases" style="min-height: 195px;" height="200"></div>
                    <!-- <div class="chart-type-label">
                        <input type="radio" name="overall_chart" id="overall_line" onchange="plotOverallChart('line')" checked>
                        <label for="overall_line" class="pr-3">Line</label>
                        <input type="radio" name="overall_chart" id="overall_bar" onchange="plotOverallChart('bar')">
                        <label for="overall_bar">Bar</label>
                    </div> -->
                </div>
            </div>
            <div class="col-xl-7 col-sm-12 case-box overview-map-wrapper" style="padding: 3rem 0.5rem;">
                <div class="subtitle">
                    <p>Malaysia Geographical Map</p>
                    <div>
                        <label for="map-year">Year: </label>
                        <select name="map-year" id="map-year">
                            <option value="">2009</option>
                            <option value="">2010</option>
                            <option value="">2011</option>
                            <option value="">2012</option>
                            <option value="">2013</option>
                            <option value="">2014</option>
                            <option value="">2015</option>
                        </select>
                    </div>
                </div>
                <div id="map"></div>
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
                Age Group Ditribution Across Years
                <!--<div id="more_age" class="more_vert"><i class="fal fa-ellipsis-h"></i></div>
                 <div class="more_option rounded">
                    <a href="" class="border-bottom" id="all_cases_jpg" onclick="export_as_jpg(this)">Export as JPG</a><br>
                    <a href="" class="border-bottom">Export as PNG</a><br>
                    <a href="">Export as data</a>
                </div> -->
            </div>
            <canvas id="lineChart_age_group" height="200"></canvas>
            <!-- <div class="chart-type-label">
                <input type="radio" name="age_chart" id="age_line" onchange="plotAgeChart('line')">
                <label for="age_line" class="pr-3">Line</label>
                <input type="radio" name="age_chart" id="age_bar" onchange="plotAgeChart('bar')" checked>
                <label for="age_bar">Bar</label>
            </div> -->

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
    @can('public')

    <!-- <div class="banner-wrapper">
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel" style="width: 100%;">
                <ol class="carousel-indicators">
                    <li data-target="#carouselExampleCaptions" data-slide-to="0" class="active"></li>
                    <li data-target="#carouselExampleCaptions" data-slide-to="1"></li>
                </ol>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="{{ asset('img/banner/HFMD_poster_1.jfif') }}" class="d-block w-100" alt="...">
                        
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('img/banner/HFMD_poster_4.jfif') }}" class="d-block w-100" alt="...">
                       
                    </div>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleCaptions" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
        <div class="col-sm-12 col-md-6">

        </div>
    </div>

</div> -->

    @endcan


    @endsection
    @section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <!-- Simple Map Trial -->
    <script type="text/javascript" src="{{ asset('js/mapdata.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/countrymap.js') }}"></script>
    <!-- Graphical data by ApexChart -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

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
            $.ajax({
                type: 'GET',
                url: '{{ route("overview.getOverallChart") }}',
                beforeSend: function() {
                    $('#overall-spinner').css('display', 'block');
                },
                success: function(chart) {
                    $('#overall-spinner').css('display', 'none');
                    var options = {
                        series: [{
                            name: "Total",
                            data: chart.total
                        }, {
                            name: "Infected",
                            data: chart.infected
                        }, {
                            name: "Death",
                            data: chart.death
                        }],
                        chart: {
                            height: 350,
                            type: charttype,
                            zoom: {
                                enabled: true
                            },
                            toolbar: {
                                tools: {
                                    download: chart.download
                                }
                            }
                        },
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            curve: 'straight'
                        },
                        markers: {
                            size: 1
                        },
                        grid: {
                            borderColor: '#e7e7e7',
                            row: {
                                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                                opacity: 0.5
                            },
                        },
                        xaxis: {
                            categories: chart.years,
                            title: {
                                text: 'Years'
                            },
                        },
                        yaxis: {
                            title: {
                                text: 'Total number of cases'
                            },
                        },
                        colors: ['#F2C94C', '#6fcf97', '#eb5757']
                    };

                    var chart = new ApexCharts(document.getElementById('lineChart_all_cases'), options);
                    chart.render();

                    // var ctx_line = document.getElementById('lineChart_all_cases').getContext('2d');
                    // myOverallChart = new Chart(ctx_line, {
                    //     type: charttype,
                    //     backgroundColor: 'white',
                    //     data: {
                    //         labels: chart.years,
                    //         datasets: [{
                    //             label: 'Total',
                    //             backgroundColor: '#F2C94C',
                    //             borderColor: '#F2C94C',
                    //             data: chart.total,
                    //             fill: false,
                    //         }, {
                    //             label: 'Infected',
                    //             backgroundColor: '#6fcf97',
                    //             borderColor: '#6fcf97',
                    //             data: chart.infected,
                    //             fill: false,
                    //         }, {
                    //             label: 'Deaths',
                    //             backgroundColor: '#eb5757',
                    //             borderColor: '#eb5757',
                    //             data: chart.death,
                    //             fill: false,
                    //         }],

                    //     },
                    //     options: {
                    //         legend: {
                    //             display: true,
                    //             position: 'bottom',
                    //         },
                    //         scales: {
                    //             yAxes: [{
                    //                 scaleLabel: {
                    //                     display: true,
                    //                     labelString: 'Total number of cases'
                    //                 },
                    //                 ticks: {
                    //                     stepSize: 10000,
                    //                 },
                    //             }],
                    //             xAxes: [{
                    //                 scaleLabel: {
                    //                     display: true,
                    //                     labelString: 'Years'
                    //                 }
                    //             }],
                    //         },

                    //     }
                    // });

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
                                label: 'Below 1 (excl 1)',
                                backgroundColor: '#FFE38C',
                                borderColor: '#FFE38C',
                                data: chart.children_below_1,
                                fill: false,
                            }, {
                                label: 'Below 2 (excl 2)',
                                backgroundColor: '#F2C94C',
                                borderColor: '#F2C94C',
                                data: chart.children_below_2,
                                fill: false,
                            }, {
                                label: 'Below 3 (excl 3)',
                                backgroundColor: '#BF9922',
                                borderColor: '#BF9922',
                                data: chart.children_below_3,
                                fill: false,
                            }, {
                                label: '3 & above',
                                backgroundColor: '#987814',
                                borderColor: '#987814',
                                data: chart.children_above_3,
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
                                        labelString: 'Years'
                                    }
                                }],
                            },
                            // tooltips: {
                            //     callbacks: {
                            //         label: function(tooltipItem, data) {
                            //             return tooltipItem.yLabel + ' cases';

                            //         }
                            //     }
                            // }
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
    <!-- <script src="{{asset('js/map.js')}}"></script> -->
    @endsection