@extends('layouts.layout')
@section('data-active')
<li class="nav-item active">
@endsection

@section('content')
<!-- Styles -->
<link rel="stylesheet" type="text/css" href="/css/data.css">
<div class="wrapper">
    {{--@include('common.upload-button')--}}
    
    <!-- wrapper class -->
    <div class="row">
        <div class="col-sm-0 col-md-0 col-xl-1"></div>
        <div class="col-sm-12 col-md-12 col-xl-10">
            <!-- Content -->
            <nav class="data-year-nav">
                <div class="nav nav-tabs justify-content-center" id="nav-tab" role="tablist">
                @foreach($years as $year)
                    <a class="nav-item nav-link" id="year-{{$year->year}}-tab" data-toggle="tab" href="#year-{{$year->year}}" role="tab" aria-controls="year-{{$year->year}}" aria-selected="true">{{$year->year}}</a>
                @endforeach
                </div>
            </nav>
            <br>
      
            <div class="tab-content" id="nav-tabContent">
            @foreach($datalist as $singleYear)
                <div class="tab-pane fade" id="year-{{ $singleYear['year'] }}" role="tabpanel" aria-labelledby="year-{{ $singleYear['year'] }}-tab">
                    <!-- data total -->
                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-xl-6">
                            <div class="card mb-3 border-0">
                                <div class="card-body">
                                    <h5 class="card-title text-center mb-3">Total Cases</h5>
                                    <div class="row">
                                        <div class="col text-center">
                                            <h1 class="card-text">{{ number_format($singleYear['total_infected']) }}</h1>
                                            <p class="card-text mb-0">Infected</p>
                                            <p class="card-text mb-0">({{ number_format(($singleYear['total_infected']/($singleYear['total_infected'] + $singleYear['total_deaths'])) * 100, 1) }}%)</p>
                                        </div>
                                        <div class="col text-center">
                                            <h1 class="card-text text-danger">{{ number_format($singleYear['total_deaths']) }}</h1>
                                            <p class="card-text mb-0 text-danger">Deaths</p>
                                            <p class="card-text mb-0 text-danger">({{ number_format(($singleYear['total_deaths']/($singleYear['total_infected'] + $singleYear['total_deaths'])) * 100, 1) }}%)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-xl-6 border-left">
                            <div class="card mb-3 border-0">
                                <div class="card-body">
                                <h5 class="card-title text-center mb-3">Gender</h5>
                                    <div class="row">
                                        <div class="col text-center">
                                            <h1 class="card-text">{{ number_format($singleYear['total_male']) }}</h1>
                                            <p class="card-text mb-0">Male <i style="font-size:20px" class="fa">&#xf222;</i></p>
                                            <p class="card-text mb-0">({{ number_format(($singleYear['total_male']/($singleYear['total_male'] + $singleYear['total_female'])) * 100, 1) }}%)</p>
                                        </div>
                                        <div class="col text-center">
                                            <h1 class="card-text">{{ number_format($singleYear['total_female']) }}</h1>
                                            <p class="card-text mb-0">Female <i style="font-size:20px" class="fa">&#xf221;</i></p>               
                                            <p class="card-text mb-0">({{ number_format(($singleYear['total_female']/($singleYear['total_male'] + $singleYear['total_female'])) * 100, 1) }}%)</p>         
                                        </div>
                                    </div>
                                </div>
                            </div>    
                        </div>
                        <div class="col-sm-12 col-md-6 col-xl-6 border-top">
                            <div class="card mb-3 border-0">
                                <div class="card-body">
                                    <h5 class="card-title text-center">Highest Total Cases</h5>
                                    <div class="col text-center">
                                        <h1 class="card-text">{{ number_format($singleYear['maxvalue']) }}</h1>
                                        <p class="card-text"><a class="state-link" href="{{ route('data.show', ['year'=>$singleYear['year'], 'state'=>$singleYear['maxkey']]) }}">{{ $singleYear['maxkey'] }}</a></p>
                                    </div>
                                </div>
                            </div>    
                        </div>
                        <div class="col-sm-12 col-md-6 col-xl-6 border-top">
                            <div class="card mb-3 border-0">
                                <div class="card-body">
                                    <h5 class="card-title text-center">Lowest Total Cases</h5>
                                    <div class="col text-center">
                                        <h1 class="card-text">{{ number_format($singleYear['minvalue']) }}</h1>
                                        <p class="card-text"><a class="state-link" href="{{ route('data.show', ['year'=>$singleYear['year'], 'state'=>$singleYear['minkey']]) }}">{{ $singleYear['minkey'] }}</a></p>
                                    </div>
                                </div>
                            </div>    
                        </div>                
                    </div>
                    <br>
                    <!-- data table  -->
                    <div class="jumbotron">
                        <div class="row">
                            <div class="col-sm-0 col-md-1 col-xl-1"></div>
                                <div class="col-sm-12 col-md-10 col-xl-10 data-table">
                                    <table class="table table-sm table-responsive-lg">
                                        <h4 class="text-center">State Distribution across Month</h4>
                                        <thead>
                                            <tr>
                                                <th scope="col" class="freeze-col"></th>
                                                @foreach($months as $month)
                                                    <th scope="col" class="month month-{{ $month->month }} text-center">{{ $month->month }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                        
                                        @foreach($singleYear['data'] as $state => $values)
                                                <tr class="table-row" >
                                                    <th scope="row" class="freeze-col">
                                                        <a class="state-link" href="{{ route('data.show', ['year'=>$singleYear['year'], 'state'=>$state]) }}">{{ $state }}</a>
                                                    </th>
                                                    @foreach($values as $value)
                                                        <td class="text-center">{{ $value }}</td>
                                                    @endforeach
                                                </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            <div class="col-sm-0 col-md-1 col-xl-1"></div>
                        </div>
                    </div>
                </div>

            @endforeach
        </div>
        <div class="col-sm-0 col-md-0 col-xl-1"></div>
    </div>
</div>
@endsection()

@section('footer')
<!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css"> -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>
<script>
    $(document).ready( function () {
        $('.table').DataTable({
            responsive: true,
            paging: false,
            info: false,
            "language": {
                search: ' ',
                searchPlaceholder: 'Search states'
            }
        });
    } );
</script>

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
    document.getElementById("year-2009-tab").classList.add("active");
    document.getElementById("year-2009").classList.add("active");
    document.getElementById("year-2009-tab").classList.add("show");
    document.getElementById("year-2009").classList.add("show");

</script>
@endsection()