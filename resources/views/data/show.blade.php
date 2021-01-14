@extends('layouts.layout')

@section('title', "$this_state")

@section('css')
<link rel="stylesheet" type="text/css" href="/css/data.css">
<style>
.highlight-row {
    justify-content: flex-end;
    margin-bottom: 5px;
}
.highlight-input {
    border: 0;
    border-radius: 0;
    border-bottom: 3px solid var(--secondary-text-color);
    margin-left: 23px;
    width: -webkit-fill-available;
}
@media screen and (max-width:768px) {
    .highlight-row label {
        text-align: left !important;
        margin-right: 0 !important;
        padding-bottom: 0;
    }
}
</style>
@endsection

@section('content')
<div class="wrapper">
    <div class="row">
        <div class="col-xl-12">
            <h2 class="state-title text-center">HFMD Analysis at {{ $this_state }}
                <button type="button" class="btn dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <!-- Dropdown menu links -->
                    @foreach($states as $msia_state)
                    <a class="dropdown-item text-center" href="{{ route('data.show', ['state'=>$msia_state, 'year'=>$this_year]) }}">{{ $msia_state }}</a>
                    @endforeach
                </div>
            </h2>
            <h5 class="year-title text-center">in {{ $this_year }}
                <button type="button" class="btn dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right" style="left:0; min-width: 8rem;">
                    <!-- Dropdown menu links -->
                    @foreach($years as $year)
                    <a class="dropdown-item text-center" href="{{ route('data.show', ['year'=>$year, 'state'=>$this_state]) }}">{{ $year }}</a>
                    @endforeach
                </div>
            </h5>
        </div>

        <div class="col-xl-12">
            <div class="row mt-3">
                <div class="col-sm-4 col-md-4">
                    <h1 class="total-cases-title text-center">{{ number_format($total_infected + $total_deaths) }}</h1>
                    <p class="total-cases-subtitle text-center">Total cases</p>
                </div>
                <div class="col-sm-4 col-md-4 border-left">
                    <h1 class="total-cases-title text-center">{{ number_format($total_infected) }}</h1>
                    <p class="total-cases-subtitle text-center">Infected cases
                        @if($total_infected != 0)
                        <span>({{ number_format(($total_infected/($total_infected + $total_deaths)) * 100, 1) }} %)</span>
                        @else
                        <span>(0 %)</span>
                        @endif
                    </p>
                </div>
                <div class="col-sm-4 col-md-4 border-left">
                    <h1 class="total-cases-title text-center">{{ number_format($total_deaths) }}</h1>
                    <p class="total-cases-subtitle text-center">Deaths
                        @if($total_deaths != 0)
                        <span>({{ number_format(($total_deaths/($total_infected + $total_deaths)) * 100, 1) }} %)</span>
                        @else
                        <span>(0 %)</span>
                        @endif
                    </p>
                </div>
                <div class="col-sm-12 col-md-6 col-xl-6 border-top">
                    <div class="card mb-3 border-0">
                        <div class="card-body">
                            <h5 class="card-title text-center">Highest Total Cases</h5>
                            <div class="col text-center">
                                <h1 class="card-text">{{ number_format( $analysed_result['highest']['total']['count'] ) }}</h1>
                                <p class="card-text"><a class="state-link">{{ $analysed_result['highest']['total']['state'] }}</a></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-xl-6 border-top">
                    <div class="card mb-3 border-0">
                        <div class="card-body">
                            <h5 class="card-title text-center">Lowest Total Cases</h5>
                            <div class="col text-center">
                                <h1 class="card-text">{{ number_format( $analysed_result['lowest']['total']['count'] ) }}</h1>
                                <p class="card-text"><a class="state-link">{{ $analysed_result['lowest']['total']['state'] }}</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mb-4 text-center link-climatic">
        <a href="{{ route('climatic', ['year'=>$this_year, 'state'=>$this_state]) }}"><i class="fas fa-cloud"></i> Go to climatic data</a>
    </div>
    <!-- data table : Infected cases by district -->
    <div class="jumbotron">
        <h2>Infected Cases by District</h2>
        <h5>at {{ $this_year }}</h5>
        <hr>
        <div class="wrapper mt-5">
            <div class="row">
                <div class="col-sm-12">
                    <div id="myLocalityChart"></div>
                </div>

                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 data-table"><br>
                    <h2>District Distribution across Month</h2>
                    <h5>at {{ $this_year }}</h5>
                    <hr>
                    <div class="form-group row highlight-row">
                        <label for="inputMonthCases" class="col-sm-3 col-form-label text-right pr-0" style="margin-right: -15px;">You may highlight</label>
                        <div class="col-sm-3">
                            <input type="number" class="form-control highlight-input" id="inputMonthCases" placeholder="Number of cases" min='0'>
                        </div>
                    </div>
                    <table class="table table-sm table-responsive-lg">
                        <thead class="table-header">
                            <tr>
                                <th scope="col" class="freeze-col">State <i class="fas fa-sort text-secondary"></i></th>
                                @foreach($months as $month)
                                <th scope="col" class="month month-{{ $month }} text-center">{{ $month }} <i class="fas fa-sort text-secondary"></i></th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($monthly_details as $district => $month)
                            <tr class="table-row month">
                                <th class="text-left freeze-col"><a class="state-link" href="{{ route('district.index', ['year'=>$this_year, 'state'=>$this_state, 'district'=>$district] ) }}">{{ $district }}</a></th>
                                @foreach($month as $value)
                                <td class="text-center">{{ $value }}</td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <!-- data table : State Distribution across Week -->
    <div class="jumbotron">
        <div class="col-sm-12 data-table m-auto">
            <table class="table table-sm table-responsive-lg">
                <h2>District Distribution across Week</h2>
                <h5>at {{ $this_year }}</h5>
                <hr>
                <div class="form-group row highlight-row">
                    <label for="inputWeekCases" class="col-sm-3 col-form-label text-right pr-0" style="margin-right: -15px;">You may highlight</label>
                    <div class="col-sm-3">
                        <input type="number" class="form-control highlight-input" style="margin-left: 15px;" id="inputWeekCases" placeholder="Number of cases" min='0'>
                    </div>
                </div>

                <thead class="table-header">
                    <tr>
                        <th scope="col" class="freeze-col">State <i class="fas fa-sort text-secondary"></i></th>
                        @foreach($weeks as $week)
                        <th scope="col" class="text-center">{{ $week }}<i class="fas fa-sort text-secondary d-inline"></i></th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>

                    @foreach($weekly_details as $district => $values)
                    <tr class="table-row week">
                        <th scope="row" class="freeze-col ">
                            <a class="state-link" href="{{  route('district.index', ['year'=>$this_year, 'state'=>$this_state, 'district'=>$district] ) }}">{{ $district }}</a>
                        </th>
                        @foreach($values as $value)
                        <td class="text-center">{{ $value }}</td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- categories data according to severity -->
    <div class="jumbotron category-wrapper">
        <h2>District by Cases Severity</h2>
        <h5>at {{ $this_year }}</h5>
        <hr>
        <div class="row">
            @foreach($category_data as $cat_data)
            <div class="col-sm-12 col-md-6 col-xl-3">
                @if($cat_data['type'] == 'A')
                <div class="card border-success mb-3 border-3 text-center shadow-lg">
                    @elseif($cat_data['type'] == 'B')
                    <div class="card border-info mb-3 border-3 text-center shadow-lg">
                        @elseif($cat_data['type'] == 'C')
                        <div class="card border-secondary mb-3 border-3 text-center shadow-lg">
                            @elseif($cat_data['type'] == 'D')
                            <div class="card border-danger mb-3 border-3 text-center shadow-lg">
                                @endif
                                <div class="card-body">
                                    @if($cat_data['type'] == 'A')
                                    <h1 class="card-title text-success">{{ $cat_data['count'] }}</h1>
                                    <p class="card-text text-success font-weight-bolder">{{ $cat_data['count'] < 2 ? 'District' : 'Districts' }}</p>
                                    @elseif($cat_data['type'] == 'B')
                                    <h1 class="card-title text-info">{{ $cat_data['count'] }}</h1>
                                    <p class="card-text text-info font-weight-bolder">{{ $cat_data['count'] < 2 ? 'District' : 'Districts' }}</p>
                                    @elseif($cat_data['type'] == 'C')
                                    <h1 class="card-title text-secondary">{{ $cat_data['count'] }}</h1>
                                    <p class="card-text text-secondary font-weight-bolder">{{ $cat_data['count'] < 2 ? 'District' : 'Districts' }}</p>
                                    @elseif($cat_data['type'] == 'D')
                                    <h1 class="card-title text-danger">{{ $cat_data['count'] }}</h1>
                                    <p class="card-text text-danger font-weight-bolder">{{ $cat_data['count'] < 2 ? 'District' : 'Districts' }}</p>
                                    @endif
                                </div>
                                @if($cat_data['type'] == 'A')
                                <div class="card-header text-success border-tb-2 font-weight-bold">Total cases {{ $cat_data['range'] }}</div>
                                @elseif($cat_data['type'] == 'B')
                                <div class="card-header text-info border-tb-2 font-weight-bold">Total cases {{ $cat_data['range'] }}</div>
                                @elseif($cat_data['type'] == 'C')
                                <div class="card-header text-secondary border-tb-2 font-weight-bold">Total cases {{ $cat_data['range'] }}</div>
                                @elseif($cat_data['type'] == 'D')
                                <div class="card-header text-danger border-tb-2 font-weight-bold">Total cases {{ $cat_data['range'] }}</div>
                                @endif
                                <div class="card-body">
                                    @foreach($cat_data['data'] as $state => $value)
                                    <div class="table-row clearfix">
                                        <span class="float-left text-left">{{$state}}</span>
                                        <span class="float-right text-right">{{$value}}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="jumbotron">
                            <h2>Age Group Distribution in {{ ucwords(strtolower($this_state)) }}</h2>
                            <h5>at {{ $this_year }}</h5>
                            <hr>
                            <div id="lineChart_age"></div>
                        </div>
                    </div>
                    <!-- <div class="col-md-6">
                        <div class="jumbotron">
                            <h2>Infected cases by Male</h2>
                            <h5>in {{ $this_state }} at {{ $this_year }}</h5>
                            <hr>
                            <div id="male-pieChart"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="jumbotron">
                            <h2>Infected cases by Female</h2>
                            <h5>in {{ $this_state }} at {{ $this_year }}</h5>
                            <hr>
                            <div id="female-pieChart"></div>
                        </div>
                    </div> -->
                    <div class="col-md-12">
                        <div class="jumbotron">
                            <h2>Infected cases daily in {{ ucwords(strtolower($this_state)) }}</h2>
                            <h5>at {{ $this_year }}</h5>
                            <hr>
                            <div id="heatmap"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<!-- Numerical data by DataTables -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>
<script>
    $(document).ready(function() {
        $('.table').DataTable({
            responsive: true,
            paging: false,
            info: false,
            "language": {
                search: 'You may filter',
                searchPlaceholder: 'District by name'
            }
        });
    });
</script>

<!-- Graphical data by ApexChart -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    $(document).ready(function() {
        plotLocalityChart();
        // plotMaleChart();
        // plotFemaleChart();
        plotHeatmap();
        plotAgeGroupChart();

        $("#inputWeekCases").on('keyup change', function(){
            var value = $("#inputWeekCases").val();
            $('.week td').each(function() {
                if(parseInt($(this).html()) == value){
                    $(this).css('background-color', '#f2c94c')
                } else {
                    $(this).css('background-color', '#fff')
                }
            });
        });

        $("#inputMonthCases").on('keyup change', function(){
            var value = $("#inputMonthCases").val();
            $('.month td').each(function() {
                if(parseInt($(this).html()) == value){
                    $(this).css('background-color', '#f2c94c')
                } else {
                    $(this).css('background-color', '#fff')
                }
            });
        });
    });

    function plotLocalityChart() {
        $.ajax({
            type: 'GET',
            data: {
                year: "{{ $this_year }}",
                state: "{{ $this_state }}",
            },
            url: '{{ route("state.getLocalityChart") }}',
            beforeSend: function() {},
            success: function(chart) {
                var chartHeight;
                if(chart.labels.length <= 5){
                    chartHeight = 450;
                } else if(chart.labels.length <= 10){
                    chartHeight = 850;
                } else {
                    chartHeight = 1200;
                }
                var options = {
                    series: chart.data,
                    chart: {
                        type: chart.type,
                        height: chartHeight,
                        zoom: {
                            enabled: true
                        },
                        toolbar: {
                            tools: {
                                download: chart.download
                            }
                        },

                    },
                    plotOptions: {
                        bar: {
                            horizontal: true,
                            dataLabels: {
                                position: 'top'
                            }
                        },
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: function(val, opts) {
                            let percent = opts.w.globals.seriesPercent[opts.seriesIndex][opts.dataPointIndex];
                            return percent.toFixed(1) + "%";
                        },
                        offsetX: 30,
                        style: {
                            fontSize: '12px',
                            colors: ["#304758"]
                        },
                    },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                    },
                    xaxis: {
                        categories: chart.labels,
                        tickPlacement: 'on'
                    },
                    yaxis: {
                        title: {
                            text: chart.ylabel,
                        }
                    },
                    legend: {
                        position: 'top'
                    },
                    fill: {
                        opacity: 1
                    },
                    responsive: [{
                        breakpoint: 1000,
                        options: {
                            legend: {
                                position: "bottom"
                            }
                        }
                    }],
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return val + " cases";
                            }
                        }
                    }
                };
                var chart = new ApexCharts(document.getElementById('myLocalityChart'), options);
                chart.render();
            },
            error: function(xhr, status, error) {

            }
        });
    }

    function plotMaleChart() {
        $.ajax({
            type: 'GET',
            data: {
                year: "{{ $this_year }}",
                state: "{{ $this_state }}",
                gender: 'male'
            },
            url: '{{ route("state.getGenderChart") }}',
            beforeSend: function() {},
            success: function(chart) {
                var options = {
                    chart: {
                        type: chart.type,
                        zoom: {
                            enabled: true
                        }
                    },
                    series: chart.data,
                    labels: chart.labels,
                    legend: {
                        position: 'right'
                    },
                    colors: chart.colors,
                }

                var chart = new ApexCharts(document.getElementById('male-pieChart'), options);
                chart.render();
            },
        });
    }

    function plotFemaleChart() {
        $.ajax({
            type: 'GET',
            data: {
                year: "{{ $this_year }}",
                state: "{{ $this_state }}",
                gender: 'female'
            },
            url: '{{ route("state.getGenderChart") }}',
            beforeSend: function() {},
            success: function(chart) {
                var options = {
                    chart: {
                        type: chart.type,
                        zoom: {
                            enabled: true
                        }
                    },
                    series: chart.data,
                    labels: chart.labels,
                    legend: {
                        position: 'right'
                    },
                    colors: chart.colors,
                }

                var chart = new ApexCharts(document.getElementById('female-pieChart'), options);
                chart.render();
            },
        });
    }


    function plotHeatmap() {
        $.ajax({
            type: 'GET',
            data: {
                year: "{{ $this_year }}",
                state: "{{ $this_state }}",
            },
            url: '{{ route("state.getDailyChart") }}',
            success: function(chart) {

                var options = {
                    series: chart.data,
                    chart: {
                        height: 500,
                        type: 'heatmap',
                        zoom: {
                            enabled: true
                        },
                        toolbar: {
                            tools: {
                                download: chart.download
                            }
                        }
                    },
                    plotOptions: {
                        heatmap: {
                            shadeIntensity: 0.5,
                            radius: 35,
                            useFillColorAsStroke: false,
                            colorScale: {
                                ranges: [{
                                        from: 0,
                                        to: 1,
                                        name: '0 - 1 case',
                                        color: '#ffa600',
                                    },
                                    {
                                        from: 2,
                                        to: 3,
                                        name: '2 - 3 cases',
                                        color: '#d45087',
                                    },
                                    {
                                        from: 4,
                                        to: 5,
                                        name: '4 - 5 cases',
                                        color: '#665191',
                                    },
                                    {
                                        from: 6,
                                        to: 20,
                                        name: 'More than 6 cases',
                                        color: '#2f4b7c',
                                    }
                                ],
                            },
                            distributed: true
                        }
                    },
                    xaxis: {
                        type: 'Day',
                        categories: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31'],

                    },
                    dataLabels: {
                        enabled: true,
                        style: {
                            colors: ['#fff']
                        }
                    },
                    stroke: {
                        width: 1
                    },
                    title: {
                        text: 'HeatMap Chart with Color Range'
                    }
                };

                var chart = new ApexCharts(document.getElementById('heatmap'), options);
                chart.render();
            }
        });
    }

    function plotAgeGroupChart() {
        $.ajax({
            type: 'GET',
            data: {
                year: "{{ $this_year }}",
                state: "{{ $this_state }}",
            },
            url: '{{ route("state.getAgeGroupChart") }}',
            beforeSend: function() {},
            success: function(chart) {
                var options = {
                    series: chart.data,
                    chart: {
                        height: 350,
                        stacked: false,
                        toolbar: {
                            tools: {
                                download: chart.download
                            },
                        },
                        zoom: {
                            enabled: true
                        },
                    },
                    stroke: {
                        width: [0, 3],
                        curve: 'smooth',
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
                        type: 'age'
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
</script>
@endsection