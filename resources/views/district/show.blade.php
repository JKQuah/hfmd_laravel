@extends('layouts.layout')

@section('content')
<link rel="stylesheet" type="text/css" href="/css/data.css">

<div class="wrapper">
    <h2 class="text-center">HFMD Cases in <a href="{{ route('data.show', ['year'=>$year, 'state'=>$state]) }}">{{ $state }}</a>, {{ $district }}
        <button type="button" class="btn dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="material-icons">expand_more</span>
        </button>
        <div class="dropdown-menu dropdown-menu-right">
            <!-- Dropdown menu links -->
            @foreach($district_list as $this_district)
                <a class="dropdown-item text-center" href="{{ route('district.show', ['year'=>$year, 'state'=>$state, 'district'=>$this_district['district']]) }}">{{ $this_district['district'] }}</a>
            @endforeach
        </div>
    </h2>
    <h5 class="text-center mb-3">at {{ $year }}</h5>
    <!-- data total -->
    <div class="row">
        <div class="col-sm-0 col-md-0 col-xl-1"></div>
        <div class="col-sm-12 col-md-12 col-xl-10">
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
                    <div class="col-sm-12 col-md-6 border-right border-warning">
                        <h4>Age Distribution</h4>
                        <canvas id="lineChart_age"></canvas>
                        <input type="radio" class="">Line
                        <input type="radio" class="">Bar
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <h4>Month Distribution</h4>
                        <canvas id="lineChart_case"></canvas>
                        <input type="radio" class="">Line
                        <input type="radio" class="">Bar
                    </div>
                </div>
            </div>
            <div class="row">
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
                                <a href="{{ route('district.show', ['year'=>$data['year'] , 'state'=>$state, 'district'=>$district]) }}" class="btn btn-warning font-weight-bold">Go to {{ $data['year'] }}</a>
                            </div>
                        </div>
                    </div>
                    <br>
                </div>
                @endif
                @endforeach
            </div>
            <br>
            <!-- table of all data in district -->
            <table class="table table-sm table-responsive-lg">
                <thead>
                    <tr class="text-center">
                        <th scope="col"></th>
                        <th scope="col">Gender</th>
                        <th scope="col">Year</th>
                        <th scope="col">Month</th>
                        <th scope="col">Day</th>
                        <th scope="col">Notification Date</th>
                        <th scope="col">Onset Date</th>
                        <th scope="col">Day</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>
        <div class="col-sm-0 col-md-0 col-xl-1"></div>
    </div>
    

</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script>
    var ctx_line = document.getElementById('lineChart_age').getContext('2d');
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
    var ctx_pie = document.getElementById('lineChart_case').getContext('2d');
    var myPieChart = new Chart(ctx_pie, {
        type: 'line',
        data: {
            labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
            datasets: [{
                borderColor: '#F2C94C',
                data: [0, 10, 5, 2, 20, 30, 24, 10, 5, 2, 20, 30]
            }]
        },
        
        options: {}
    });
</script>
@endsection