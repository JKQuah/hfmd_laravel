@extends('layouts.layout')

@section('content')
<link rel="stylesheet" type="text/css" href="/css/data.css">

<div class="wrapper">
    <div class="row">
        <div class="col-xl-12">
            <h1 class="state-title text-center">HFMD Cases in {{ $this_state }}
                <button type="button" class="btn dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="material-icons ">expand_more</span>
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <!-- Dropdown menu links -->
                    @foreach($states as $state)
                        <a class="dropdown-item text-center" href="{{ route('data.show', ['state'=>$state->state, 'year'=>$this_year]) }}">{{ $state->state }}</a>
                    @endforeach
                </div>
            </h1>
            <h3 class="year-title text-center">at {{ $this_year }}
                <button type="button" class="btn dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="material-icons">expand_more</span>
                </button>
                <div class="dropdown-menu dropdown-menu-right" style="left:0; min-width: 8rem;">
                    <!-- Dropdown menu links -->
                    @foreach($years as $year)
                        <a class="dropdown-item text-center" href="{{ route('data.show', ['year'=>$year->year, 'state'=>$this_state]) }}">{{ $year->year }}</a>
                    @endforeach
                </div>
            </h3>
        </div>
        
        <div class="col-xl-12">
            <div class="row wrapper">
                <div class="col-sm-4 col-md-4">
                    <h1 class="total-cases-title text-center">{{ $total_infected + $total_deaths}}</h1>
                    <p class="total-cases-subtitle text-center">Total cases</p>
                </div>
                <div class="col-sm-4 col-md-4 border-left">
                    <h1 class="total-cases-title text-center">{{ $total_infected }}</h1>
                    <p class="total-cases-subtitle text-center">Infected cases
                        @if($total_infected != 0)
                        <span>({{ number_format(($total_infected/($total_infected + $total_deaths)) * 100, 1) }} %)</span>
                        @else
                        <span>(0 %)</span>
                        @endif
                    </p>
                </div>
                <div class="col-sm-4 col-md-4 border-left">
                    <h1 class="total-cases-title text-center">{{ $total_deaths }}</h1>
                    <p class="total-cases-subtitle text-center">Deaths
                        @if($total_deaths != 0)
                        <span>({{ number_format(($total_deaths/($total_infected + $total_deaths)) * 100, 1) }} %)</span>
                        @else
                        <span>(0 %)</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="jumbotron">
        <h2>Infected cases by locality</h2>
            <h5>at {{ $this_year }}</h5>
        <hr>
        <div class="wrapper">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-8">
                    <canvas id="barChart"></canvas>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-4">
                    <h3>Highest Infected District</h3>
                    <h3>Highest Deaths District</h3>
                </div>
                
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12"><br>
                    <table class="table table-sm table-responsive-lg">
                        <thead>
                            <tr>
                                <th scope="col" class="freeze-col"></th>
                                @foreach($months as $month)
                                <th scope="col" class="month month-{{ $month->month }} text-center">{{$month->month}}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($data_district_months as $data)
                            @foreach($data as $district => $values)
                            <tr class="table-row">
                                <th class="text-left freeze-col"><a class="state-link" href="{{ route('district.show', ['year'=>$this_year, 'state'=>$this_state, 'district'=>$district] ) }}">{{ $district }}</a></th>
                                @foreach($values as $value)
                                <td class="text-center">{{$value}}</td>
                                @endforeach
                            </tr>
                            @endforeach
                        @endforeach
                        </tbody>
                    </table>
                </div>
                
            </div>
        </div>
        
    </div>
    <div class="jumbotron">
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
                        <div class="row mb-1 table-row">
                            <div class="col-sm-10"><p class="card-text float-left">{{$state}}</p></div>
                            <div class="col-sm-2"><p class="card-text float-right">{{$value}}</p></div>
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
                <h2>Infected cases by Gender</h2>
                    <h5>at {{ $this_year }}</h5>
                <hr>
                <canvas id="pieChart"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="jumbotron">
                <h2>Infected cases by Age</h2>
                    <h5>at {{ $this_year }}</h5>
                <hr>
                <canvas id="lineChart"></canvas>
            </div>
        </div>
    </div>
    
</div>
<script src="https://cdn.jsdelivr.net/npm/fusioncharts@3.12.2/fusioncharts.js" charset="utf-8"></script>
<script>
    var months = document.getElementsByClassName('month');
    
    for (var i = 0; i < months.length; i++) {
        switch (months[i].innerText) {
            case "1":
                months[i].innerHTML = "Jan";
                break;
            case "2":
                months[i].innerHTML = "Feb";
                break;
            case "3":
                months[i].innerHTML = "Mar";
                break;
            case "4":
                months[i].innerHTML = "Apr";
                break;
            case "5":
                months[i].innerHTML = "May";
                break;
            case "6":
                months[i].innerHTML = "Jun";
            case "7":
                months[i].innerHTML = "Jul";
            break;
            case "8":
                months[i].innerHTML = "Aug";
            break;
            case "9":
                months[i].innerHTML = "Sep";
            break;
            case "10":
                months[i].innerHTML = "Oct";
            break;
            case "11":
                months[i].innerHTML = "Nov";
            break;
            case "12":
                months[i].innerHTML = "Dec";
            break;
            default:
                console.log('error');
            break;
        }
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script>
    var ctx_bar = document.getElementById('barChart').getContext('2d');
    var months = document.getElementsByClassName('month');
    var monthlist=[];
    for(var i = 0; i < months.length; i++){
        monthlist.push(months[i].innerText);
    }
    console.log(monthlist);
    var chart = new Chart(ctx_bar, {
        // The type of chart we want to create
        type: 'bar',

        // The data for our dataset
        data: {
            labels: monthlist,
            datasets: [{
                backgroundColor: '#F2C94C',
                borderColor: 'rgb(255, 99, 132)',
                data: [0, 10, 5, 2, 20, 30, 45]
            }]
        },

        // Configuration options go here
        options: {
            hover: {
                // Overrides the global setting
                mode: 'index'
            }
        }
    });
    var ctx_line = document.getElementById('lineChart').getContext('2d');
    var myLineChart = new Chart(ctx_line, {
        type: 'line',
        data: {
            labels: [6, 12, 18, 24, 30, 36],
            datasets: [{
                borderColor: '#F2C94C',
                data: [0, 10, 5, 2, 20, 30]
            }]
        },
        
        options: {}
    });
    var ctx_pie = document.getElementById('pieChart').getContext('2d');
    var myPieChart = new Chart(ctx_pie, {
        type: 'pie',
        data: {
            labels: [6, 12, 18, 24, 30, 36],
            datasets: [{
                borderColor: '#F2C94C',
                data: [0, 10, 5, 2, 20, 30]
            }]
        },
        
        options: {}
    });
</script>
@endsection