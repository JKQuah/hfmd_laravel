@extends('layouts.layout')

@section('css')
<link rel="stylesheet" type="text/css" href="/css/data.css">
@endsection

@section('content')
<div class="wrapper">
    <div class="row">
        <div class="col-xl-12">
            <h1 class="state-title text-center">HFMD Cases in {{ $this_state }}
                <button type="button" class="btn dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <!-- Dropdown menu links -->
                    @foreach($states as $msia_state)
                    <a class="dropdown-item text-center" href="{{ route('data.show', ['state'=>$msia_state, 'year'=>$this_year]) }}">{{ $msia_state }}</a>
                    @endforeach
                </div>
            </h1>
            <h3 class="year-title text-center">at {{ $this_year }}
                <button type="button" class="btn dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right" style="left:0; min-width: 8rem;">
                    <!-- Dropdown menu links -->
                    @foreach($years as $year)
                    <a class="dropdown-item text-center" href="{{ route('data.show', ['year'=>$year, 'state'=>$this_state]) }}">{{ $year }}</a>
                    @endforeach
                </div>
            </h3>
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

    <!-- data table : Infected cases by locality -->
    <div class="jumbotron">
        <h2>Infected cases by locality</h2>
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
                            <tr class="table-row">
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
                    <tr class="table-row">
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
        <h2>District by infected cases</h2>
        <h5>at {{ $this_year }}</h5>
        <hr>
        <div class="row">
            @foreach($category_data as $cat_data)
            <div class="col-sm-12 col-md-6 col-xl-3">
                @if($cat_data['type'] == 'A')
                <div class="card border-secondary mb-3 border-3 text-center shadow-lg">
                    @elseif($cat_data['type'] == 'B')
                    <div class="card border-info mb-3 border-3 text-center shadow-lg">
                        @elseif($cat_data['type'] == 'C')
                        <div class="card border-warning mb-3 border-3 text-center shadow-lg">
                            @elseif($cat_data['type'] == 'D')
                            <div class="card border-danger mb-3 border-3 text-center shadow-lg">
                                @endif
                                <div class="card-body">
                                    @if($cat_data['type'] == 'A')
                                    <h1 class="card-title text-secondary">{{ $cat_data['count'] }}</h1>
                                    <p class="card-text text-secondary font-weight-bolder">District</p>
                                    @elseif($cat_data['type'] == 'B')
                                    <h1 class="card-title text-info">{{ $cat_data['count'] }}</h1>
                                    <p class="card-text text-info font-weight-bolder">District</p>
                                    @elseif($cat_data['type'] == 'C')
                                    <h1 class="card-title text-warning">{{ $cat_data['count'] }}</h1>
                                    <p class="card-text text-warning font-weight-bolder">District</p>
                                    @elseif($cat_data['type'] == 'D')
                                    <h1 class="card-title text-danger">{{ $cat_data['count'] }}</h1>
                                    <p class="card-text text-danger font-weight-bolder">District</p>
                                    @endif
                                </div>
                                @if($cat_data['type'] == 'A')
                                <div class="card-header text-secondary border-tb-2 font-weight-bold">Total cases {{ $cat_data['range'] }}</div>
                                @elseif($cat_data['type'] == 'B')
                                <div class="card-header text-info border-tb-2 font-weight-bold">Total cases {{ $cat_data['range'] }}</div>
                                @elseif($cat_data['type'] == 'C')
                                <div class="card-header text-warning border-tb-2 font-weight-bold">Total cases {{ $cat_data['range'] }}</div>
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
                    <div class="col-md-6">
                        <div class="jumbotron">
                            <h2>Infected cases by Male</h2>
                            <h5>at {{ $this_year }}</h5>
                            <hr>
                            <div id="male-pieChart" height="170"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="jumbotron">
                            <h2>Infected cases by Female</h2>
                            <h5>at {{ $this_year }}</h5>
                            <hr>
                            <div id="female-pieChart" height="170"></div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="jumbotron">
                            <h2>Infected cases in {{ $this_state }}</h2>
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
                search: ' ',
                searchPlaceholder: 'Search states'
            }
        });
    });
</script>

<!-- Graphical data by ApexChart -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    $(document).ready(function() {
        plotLocalityChart();
        plotMaleChart();
        plotFemaleChart();
        plotHeatmap();
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
                var options = {
                    series: chart.data,
                    chart: {
                        type: chart.type,
                        height: 350,
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
                            horizontal: false,
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
                        offsetY: -20,
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
                console.log(chart)

                var options = {
                    series: chart.data,
                    chart: {
                        height: 350,
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
                            radius: 5,
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
                        enabled: false
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
</script>
@endsection