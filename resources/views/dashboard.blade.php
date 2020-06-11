@extends('layouts.layout')

<!-- Styles -->
<link rel="stylesheet" type="text/css" href="/css/dashboard.css">

@section('home-active')
<li class="nav-item active">
@endsection

@section('content')
<div class="wrapper">
    <div class="row">
        <div class="col-12 col-md-3 col-xl-2">
            <!-- sidebar for filter -->
            <div class="sidenav">
                <h5>Filter By<a href="#" class="reset" type="reset" value="Reset">Reset</a></h5>
            <hr>
                <h5>States
                    <a style="float: right" type="button" data-toggle="collapse" data-target="#collapseState" aria-expanded="true" aria-controls="collapseState">
                        <i class="material-icons md-light">minimize</i>
                    </a>
                </h5>
                <div class="collapse show" id="collapseState">
                    @foreach($states as $state) 
                    <label class="filter-wrapper">{{ $state->states }}
                        <input type="checkbox">
                        <span class="checkmark"></span>
                    </label>
                    @endforeach
                </div>
            <hr>
                <h5>Year
                <a style="float: right" type="button" data-toggle="collapse" data-target="#collapseYear" aria-expanded="true" aria-controls="collapseYear">
                    <i class="material-icons md-light">minimize</i>
                </a>
                </h5>
                <div class="collapse show" id="collapseYear">
                    @foreach($years as $year)
                    <label class="filter-wrapper">{{ $year->years }}
                        <input type="checkbox">
                        <span class="checkmark"></span>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-12 col-md-9 col-xl-10">
            <!-- figure and table -->
            <div class="section-header">Overview</div>
                <div class="row">
                    <div class="col-12 col-xl-5">
                        <div class="row" style="margin-right:13px;">
                            <div class="col-12 col-xl cases-border">
                                <h6>222,222</h6>
                                <p>Reported</p>
                            </div>
                            <div class="col-12 col-xl cases-border">
                                <h6>131,111</h6>
                                <p>Recovered</p>
                            </div>
                            <div class="col-12 col-xl cases-border">
                                <h6>131,111</h6>
                                <p>Recovered</p>
                            </div>
                            <div class="col-12 col-xl-12 pie-border">
                                <div id="piechartdiv"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-7">
                        <div class="map-border">
                            
                        </div>
                    </div>
                </div>
            </div>    
        </div>
    </div>
</div>






<!-- <div class="wrapper">
    <div class="sidenav">
        <h5>Filter By<span class="reset"><a href="#">Reset</a></span></h5>
        <hr>
        <h5>States
        <a style="float: right" type="button" data-toggle="collapse" data-target="#collapseState" aria-expanded="true" aria-controls="collapseState">
            <i class="material-icons md-light">minimize</i>
        </a>
        </h5>
        <div class="collapse show" id="collapseState">
            @foreach($states as $state) 
            <label class="filter-wrapper">{{ $state->states }}
                <input type="checkbox">
                <span class="checkmark"></span>
            </label>
            @endforeach
        </div>
        <hr>
        <h5>Year
        <a style="float: right" type="button" data-toggle="collapse" data-target="#collapseYear" aria-expanded="true" aria-controls="collapseYear">
            <i class="material-icons md-light">minimize</i>
        </a>
        </h5>
        <div class="collapse show" id="collapseYear">
            @foreach($years as $year)
            <label class="filter-wrapper">{{ $year->years }}
                <input type="checkbox">
                <span class="checkmark"></span>
            </label>
            @endforeach
        </div>
    </div>
    <div class="row">
        <div class="col-2"></div>
        <div class="col-10 overview">
            <div class="section-header">Overview</div>
                <div class="row">
                    <div class="col-3">
                        <div class="row">
                            <div class="cases-border">
                                <span class="cases-number">250,125</span><br>
                                <span class="cases-title">Reported</span>
                            </div>
                            <div class="cases-border">
                                <span class="cases-number">250,000</span><br>
                                <span class="cases-title">Recovered</span> 
                            </div>
                            <div class="cases-border">
                                <span class="cases-number">5</span><br>
                                <span class="cases-title">ICU</span> 
                            </div>
                            <div class="cases-border">
                                <span class="cases-number">125</span><br>
                                <span class="cases-title">Death</span>
                            </div>
                            <div class="pie-border">
                                <div id="piechartdiv"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-9">
                        <div class="map-border">
                            
                        </div>
                    </div>
                </div>
            </div>
        <div class="w-100"></div>
        <div class="col-2"></div>
        <div class="col-10">
            <div class="section-header">Tabulation</div>
                <div class="row">
                    <div class="col">
                        <table class="table">
                            <thead>
                                <tr>
                                <th scope="col">States</th>
                                <th scope="col">Total cases</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($states as $state)
                                <tr>
                                <td>{{ $state->states }}</td>
                                <td>12,345</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col">
                    </div>
                    <div class="col">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->

<script src="https://www.amcharts.com/lib/4/core.js"></script>
<script src="https://www.amcharts.com/lib/4/charts.js"></script>
<script src="/js/dashboard.js"></script>
@endsection
