@extends('layouts.layout')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.4.3/css/ol.css" type="text/css">
<style>
    .map {
        height: 400px;
        width: 100%;
    }
</style>
<script src="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.4.3/build/ol.js"></script>

@section('css')
<!-- Styles -->
<link rel="stylesheet" type="text/css" href="/css/dashboard.css">
@endsection

@section('home-active')
<li class="nav-item active">
@endsection

@section('content')
<div class="wrapper">
    <!-- Navigate to district level data -->
    <div class="request-wrapper mb-5">
        <div class="row mb-5">
            <div class="col-sm-12 col-md-6">
                <img src="{{ asset('img\undraw_medical_care_movn.svg') }}" class="w-100" alt="data_title_image">
            </div>
            <div class="col-sm-12 col-md-6 request-container text-center px-5">
                <h2><b>Fill in the form below</b></h2>
                <small class="subtitle">We will tell you more!</small>
                <form method="post" action="{{ route('dashboard.getDistrictDetails') }}">
                    @csrf
                    <div class="form-row text-left mt-3 flex-column">
                        <div class="form-group col-sm-12 col-md-6 m-auto">
                            <label for="request-state">State</label>
                            <select name="state" id="request-state" class="form-control" style="text-align-last: center;" onchange="changeDistrict(this)" required>
                                <option value="" hidden>Select...</option>
                                @foreach($states as $state)
                                <option value="{{ $state }}">{{ $state }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm-12 col-md-6 m-auto" data-toggle="tooltip" data-placement="right" title="State must be selected">
                            <label for="request-district">District</label>
                            <select name="district" id="request-district" class="form-control" style="text-align-last: center;" disabled required>
                                <option value="" hidden>Select...</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-12 col-md-6 m-auto">
                            <label for="request-year">Year</label>
                            <select name="year" id="request-year" class="form-control" style="text-align-last: center;" required>
                                <option value="" hidden>Select...</option>
                                @foreach($years as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-warning w-50 mt-5 mx-auto">View More <i class="fas fa-angle-right"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Navigate to State level data -->
    <div class="py-5">
        <div class="title vertical-center">
            <h2>Malaysia State</h2>
        </div>
        <div class="row">
            @foreach($states as $state)
            <div class="col-sm-6 col-md-3 mb-3">
                <div class="card shadow border-0">
                    <img src="{{ asset('img/states/'.$state.'.png') }}" alt="{{ $state }}" title="{{ $state }}" class="w-50 h-25 mx-auto mt-3 border border-dark">
                    <div class="card-body">
                        <h6 class="card-title text-center">{{ $state }}</h6>
                        <a href="{{ route('state.show', $state) }}" class="card-link float-right text-secondary">View more <i class="fas fa-angle-right"></i></a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Overview -->
    <div class="title vertical-center">
        <h2>Overview</h2>
    </div>
    <div class="row">
        <div class="col-xl-5 col-sm-12">
            <div class="col-md-12 case-box">
                <div class="subtitle">Total cases from {{ $overall['min_year'] }} to {{ $overall['max_year'] }}</div>
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
            <div class="col-md-12 case-box" style="min-height: 453px;">
                <div class="spinner" id="overall-spinner">
                    <div class="bounce1"></div>
                    <div class="bounce2"></div>
                    <div class="bounce3"></div>
                </div>
                <div class="subtitle">
                    Total cases over years
                </div>
                <div id="lineChart_all_cases" style="min-height: 195px;" height="200"></div>

            </div>
        </div>
        <div class="col-xl-7 col-sm-12 case-box overview-map-wrapper" style="padding: 3rem 0.5rem;">
            <div class="subtitle">
                <p>Malaysia Geographical Map</p>
                <div>
                    <label for="map-year">Year: </label>
                    <select name="map-year" id="map-year" onchange="changeYear()">
                        @foreach($years as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div id="map" style="min-height: 330px;"></div>
            <div class="spinner" id="map-spinner">
                <div class="bounce1"></div>
                <div class="bounce2"></div>
                <div class="bounce3"></div>
            </div>
            <span class="text-center">Range of Total Number of Cases</span>
            <div class="map-legends"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6 col-sm-12">
            <div class="case-box">
                <div class="swiper-container">
                    <div class="swiper-wrapper">
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
    <div class="col-md-12 case-box" style="min-height: 470px;">
        <div class="spinner" id="age-spinner">
            <div class="bounce1"></div>
            <div class="bounce2"></div>
            <div class="bounce3"></div>
        </div>
        <div class="subtitle">
            Age Group Ditribution Across Years
        </div>
        <div id="yearly_age_group"></div>
    </div>
</div>


@can('public')

@endcan


@endsection
@section('js')
<!-- <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script> -->
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
        plotOverallChart();
        plotAgeChart();
        changeYear();
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        });
        var colors =  ["#ffa600","#ff7c43","#f95d6a", "#d45087", "#a05195", "#665191","#2f4b7c"];
        console.log(colors);
        colors.forEach(appendLegend);
    });

    function appendLegend(color, index){
        var range = (index)*500 + "~" + (((index+1)*500)-1);
        if(index-1 < 0){
            range = 0 + "~" + (((index+1)*500)-1);
        } else if(index == 6){
            range = ">" + (index)*500;
        }
        var html = $('#legends').html().replace("coloring", color).replace("range", range);
        $('.map-legends').append(html);
        if(index == 3){
            $('.map-legends').append("<br>");
        }
    }

    function changeDistrict(state) {
        $.ajax({
            url: '{{ route("anaytics.getDistrict") }}',
            type: 'get',
            data: {
                state: state.value,
            },
            beforeSend: function() {
                $("#request-district").attr('disabled', true);
            },
            success: function(districts) {
                $("#request-district").attr('disabled', false);
                var options;
                districts.forEach(element => {
                    options += '<option value="' + element.value + '">' + element.value + '</option>';
                });
                $("#request-district").html(options);
            }
        });
    }

    function plotOverallChart() {
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
                        type: 'column',
                        data: chart.total
                    }, {
                        name: "Infected",
                        type: 'line',
                        data: chart.infected
                    }, {
                        name: "Death",
                        type: 'line',
                        data: chart.death
                    }],
                    chart: {
                        height: 350,
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
                        width: [0, 3, 3],
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
                        forceNiceScale: true,
                    },
                    colors: ['#F2C94C', '#6fcf97', '#eb5757'],
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

                var chart = new ApexCharts(document.getElementById('lineChart_all_cases'), options);
                chart.render();
            },
            error: function(xhr, status, error) {

            }

        });
    }

    function plotAgeChart() {
        $.ajax({
            type: 'GET',
            url: '{{ route("overview.getAgeChart") }}',
            beforeSend: function() {
                $('#age-spinner').css('display', 'block');
            },
            success: function(chart) {
                $('#age-spinner').css('display', 'none');
                var options = {
                    series: chart.data,
                    chart: {
                        type: 'bar',
                        height: 350,
                        stacked: true,
                        stackType: '100%',
                        zoom: {
                            enabled: true
                        },
                        toolbar: {
                            tools: {
                                download: chart.download
                            }
                        }
                    },
                    responsive: [{
                        breakpoint: 480,
                        options: {
                            legend: {
                                position: 'bottom',
                                offsetX: -10,
                                offsetY: 0
                            }
                        }
                    }],
                    xaxis: {
                        categories: chart.category,
                        title: {
                            text: chart.xlabel,
                        } 
                    },
                    yaxis: {
                        title: {
                            text: chart.ylabel,
                        }
                    },
                    fill: {
                        opacity: 1
                    },
                    legend: {
                        position: 'right',
                        offsetX: 0,
                        offsetY: 50
                    },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return val + " cases";
                            }
                        }
                    }
                };

                var chart = new ApexCharts(document.getElementById('yearly_age_group'), options);
                chart.render();
            },
            error: function(xhr, status, error) {

            }

        });
    }

    function changeYear(){
        var year = document.getElementById("map-year").value ?? "2015";
        getGeoData(year);
    }
    
    function getGeoData(year) {
        $.ajax({
            url: "/dashboard/getGeographicData",
            type: "GET",
            data: {
                year: year,
            },
            beforeSend: function () {
                $('#map-spinner').css('display', 'block');
                $('#map-year').attr('disabled', true);
            },
            success: function (data) {
                $('#map-spinner').css('display', 'none');
                $('#map-year').attr('disabled', false);
                simplemaps_countrymap_mapdata.state_specific.MYS1137 = data.Perak;
                simplemaps_countrymap_mapdata.state_specific.MYS1139 = data['Pulau Pinang'];
                simplemaps_countrymap_mapdata.state_specific.MYS1140 = data.Kedah;
                simplemaps_countrymap_mapdata.state_specific.MYS1141 = data.Perlis;
                simplemaps_countrymap_mapdata.state_specific.MYS1143 = data.Johor;
                simplemaps_countrymap_mapdata.state_specific.MYS1144 = data.Kelantan;
                simplemaps_countrymap_mapdata.state_specific.MYS1145 = data.Melaka;
                simplemaps_countrymap_mapdata.state_specific.MYS1146 = data['Negeri Sembilan'];
                simplemaps_countrymap_mapdata.state_specific.MYS1147 = data.Pahang;
                simplemaps_countrymap_mapdata.state_specific.MYS1148 = data.Selangor;
                simplemaps_countrymap_mapdata.state_specific.MYS1149 = data.Terengganu;
                simplemaps_countrymap_mapdata.state_specific.MYS1186 = data.Sabah;
                simplemaps_countrymap_mapdata.state_specific.MYS1187 = data.Sarawak;
                simplemaps_countrymap_mapdata.state_specific.MYS4831 = data['Kuala Lumpur'];
                simplemaps_countrymap_mapdata.state_specific.MYS4832 = data.Putrajaya;
                simplemaps_countrymap_mapdata.state_specific.MYS4833 = data.Labuan;
                simplemaps_countrymap.load();
            },
            error: function () {

            },
        });
    }
</script>

<script type="text/template" id="legends">
    <span style="color: coloring"><i class="fas fa-circle"></i></span>
    <span>range</span>
</script>
@endsection