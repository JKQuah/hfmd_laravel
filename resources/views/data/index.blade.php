@extends('layouts.layout')
@section('data-active')
<li class="nav-item active">  
@endsection

@section('css')
<!-- Styles -->
<link rel="stylesheet" type="text/css" href="/css/data.css">
<!-- Calender -->
<link rel="stylesheet" href="css/evo-calendar.css" />
<link rel="stylesheet" href="css/evo-calendar.orange-coral.css" />

@endsection

@section('content')
<div class="wrapper">
    <!-- wrapper class -->
    <div class="row">
        <div class="col-sm-12">
            <!-- Content -->
            <nav class="data-year-nav">
                <div class="nav nav-tabs justify-content-center" id="nav-tab" role="tablist">
                    @foreach($data_analysed as $data)
                    @if($loop->first)
                    <a class="nav-item nav-link show active" id="year-{{ $data['year'] }}-tab" data-toggle="tab" href="#year-{{ $data['year'] }}" role="tab" aria-controls="year-{{ $data['year'] }}" aria-selected="true">{{ $data['year'] }}</a>
                    @else
                    <a class="nav-item nav-link" id="year-{{ $data['year'] }}-tab" data-toggle="tab" href="#year-{{ $data['year'] }}" role="tab" aria-controls="year-{{ $data['year'] }}" aria-selected="true">{{ $data['year'] }}</a>
                    @endif
                    @endforeach
                </div>
            </nav>
            <br>

            <div class="tab-content" id="nav-tabContent">
                @foreach($data_analysed as $singleYear)
                @if($loop->first)
                <div class="tab-pane fade show active" id="year-{{ $singleYear['year'] }}" role="tabpanel" aria-labelledby="year-{{ $singleYear['year'] }}-tab">
                    @else
                    <div class="tab-pane fade" id="year-{{ $singleYear['year'] }}" role="tabpanel" aria-labelledby="year-{{ $singleYear['year'] }}-tab">
                        @endif
                        <!-- data total -->
                        <div class="row">
                            <div class="col-sm-12 col-md-6 col-xl-6">
                                <div class="card mb-3 border-0">
                                    <div class="card-body">
                                        <h5 class="card-title text-center mb-3">Total Cases</h5>
                                        <div class="row">
                                            <div class="col text-center">
                                                <h1 class="card-text">{{ number_format($singleYear['total_infected']) }}</h1>
                                                <p class="card-text mb-0" style="cursor: context-menu" data-toggle="popover" data-placement="bottom" title="Gender" data-content="  Male: {{ $singleYear['total_infected_gender']['male'] }} | Female: {{ $singleYear['total_infected_gender']['female'] }}">
                                                    Infected
                                                </p>
                                                <p class="card-text mb-0">({{ number_format(($singleYear['total_infected']/($singleYear['total_infected'] + $singleYear['total_deaths'])) * 100, 1) }}%)</p>
                                            </div>
                                            <div class="col text-center">
                                                <h1 class="card-text text-danger">{{ number_format($singleYear['total_deaths']) }}</h1>
                                                <p class="card-text mb-0 text-danger" style="cursor: context-menu" data-toggle="popover" data-placement="bottom" title="Gender" data-content="  Male: {{ $singleYear['total_death_gender']['male'] }} | Female: {{ $singleYear['total_death_gender']['female'] }}">
                                                    Deaths
                                                </p>
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
                                            <h1 class="card-text">{{ number_format($singleYear['analysed_result']['highest']['total']['count']) }}</h1>
                                            <p class="card-text"><a class="state-link" href="{{ route('data.show', ['year'=>$singleYear['year'], 'state'=>$singleYear['analysed_result']['highest']['total']['state']]) }}">{{ $singleYear['analysed_result']['highest']['total']['state'] }}</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-xl-6 border-top">
                                <div class="card mb-3 border-0">
                                    <div class="card-body">
                                        <h5 class="card-title text-center">Lowest Total Cases</h5>
                                        <div class="col text-center">
                                            <h1 class="card-text">{{ number_format($singleYear['analysed_result']['lowest']['total']['count']) }}</h1>
                                            <p class="card-text"><a class="state-link" href="{{ route('data.show', ['year'=>$singleYear['year'], 'state'=>$singleYear['analysed_result']['highest']['total']['state']]) }}">{{ $singleYear['analysed_result']['lowest']['total']['state'] }}</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="time-wrapper">
                            <div class="row mb-5">
                                <div class="col-sm-12 col-md-6">
                                    <img src="{{ asset('img\all_the_data.svg') }}" class="w-100" alt="data_title_image">
                                </div>
                                <div class="col-sm-12 col-md-6 time-container">
                                    <h2><b>{{ $singleYear['year'] }}</b></h2>
                                    <small class="subtitle">View more details</small>
                                    <a class="btn w-50 btn-time" href="#monthly_{{ $singleYear['year'] }}">Monthly</a>
                                    <a class="btn w-50 btn-time" href="#weekly_{{ $singleYear['year'] }}">Weekly</a>
                                    <a class="btn w-50 btn-time" href="#daily_{{ $singleYear['year'] }}">Daily</a>

                                    <!-- <button class="btn w-50 btn-time" onclick=""></button>
                                <button class="btn w-50 btn-time" onclick="weekly_{{ $singleYear['year'] }}">Weekly</button>
                                <button class="btn w-50 btn-time" onclick="">Daily</button> -->
                                </div>
                            </div>
                        </div>

                        <!-- data table : Summary -->
                        <div class="jumbotron">
                            <div class="col-sm-12 data-table w-75 m-auto summary-table">
                                <table class="table table-sm table-responsive-lg">
                                    <h4 class="text-center mb-3">Summary</h4>
                                    <thead class="table-header">
                                        <tr>
                                            <th scope="col" class="freeze-col">State <i class="fas fa-sort text-secondary"></i></th>
                                            <th scope="col" class="text-center">Total <i class="fas fa-sort text-secondary"></i></th>
                                            <th scope="col" class="text-center">Infected <i class="fas fa-sort text-secondary"></i></th>
                                            <th scope="col" class="text-center">Deaths <i class="fas fa-sort text-secondary"></i></th>
                                            <th scope="col" class="text-center">Male <i class="fas fa-sort text-secondary"></i></th>
                                            <th scope="col" class="text-center">Female <i class="fas fa-sort text-secondary"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach($singleYear['monthly_analysis'] as $state => $values)
                                        <tr class="table-row">
                                            <th scope="row" class="freeze-col">
                                                <a class="state-link" href="{{ route('data.show', ['year'=>$singleYear['year'], 'state'=>$state]) }}">{{ $state }}</a>
                                            </th>
                                            @foreach($values as $variable => $figure)
                                            <td class="text-center">{{ $figure }}</td>
                                            @endforeach
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- data table : State Distribution across Month -->
                        <div class="jumbotron" id="monthly_{{ $singleYear['year'] }}">
                            <div class="col-sm-12 data-table m-auto">
                                <table class="table table-sm table-responsive-lg">
                                    <h4 class="text-center mb-3">State Distribution across Month</h4>
                                    <thead class="table-header">
                                        <tr>
                                            <th scope="col" class="freeze-col">State <i class="fas fa-sort text-secondary"></i></th>
                                            @foreach($singleYear['monthly_details']['JOHOR'] as $month => $count)
                                            <th scope="col" class="month-{{ $month }} text-center"><span class="month">{{ $month }}</span> <i class="fas fa-sort text-secondary"></i></th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach($singleYear['monthly_details'] as $state => $values)
                                        <tr class="table-row">
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
                        </div>

                        <!-- data table : State Distribution across Week -->
                        <div class="jumbotron" id="weekly_{{ $singleYear['year'] }}">
                            <div class="col-sm-12 data-table m-auto">
                                <table class="table table-sm table-responsive-lg">
                                    <h4 class="text-center mb-3">State Distribution across Week</h4>
                                    <thead class="table-header">
                                        <tr>
                                            <th scope="col" class="freeze-col">State <i class="fas fa-sort text-secondary"></i></th>
                                            @foreach($singleYear['weekly_details']['JOHOR'] as $week => $count)
                                            <th scope="col" class="text-center">{{ $week }}<i class="fas fa-sort text-secondary d-inline"></i></th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach($singleYear['weekly_details'] as $state => $values)
                                        <tr class="table-row">
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
                        </div>

                        <!-- data table : State Distribution across Daily -->
                        <div class="jumbotron position-relative" id="daily_{{ $singleYear['year'] }}">
                            <h4 class="text-center mb-5">State Distribution across Day</h4>
                            <div class="evoCalendar"></div>
                            <div class="spinner" id="daily-spinner">
                                <div class="bounce1"></div>
                                <div class="bounce2"></div>
                                <div class="bounce3"></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection()

@section('js')
<!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css"> -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.js"></script>

<script>
    var year = $('.show').html();
</script>
<script src="js/evo-calendar.js"></script>

<!-- Plugin: Data table -->
<script>
    $(document).ready(function() {
        $('.table').DataTable({
            responsive: true,
            paging: false,
            info: false,
            "autoWidth": false,
            "language": {
                search: ' ',
                searchPlaceholder: 'Search states'
            },
        });
    });
</script>

<!-- Plugin: EvoCalendar -->
<script>
    $(document).ready(function() {
        $.ajax({
            url: "{{ route('data.getDailyData') }}",
            type: 'GET',
            beforeSend: function() {
                $('#daily-spinner').css('display', 'block');
            },
            success: function(data) {
                $('#daily-spinner').css('display', 'none');

                $('.evoCalendar').evoCalendar({
                    calendarEvents: data,
                    theme: 'Orange Coral',
                    firstDayOfWeek: 1, // Sunday
                    format: 'mm dd yyyy',
                    titleFormat: 'MM yyyy',
                    eventHeaderFormat: 'MM d, yyyy',
                });
            },
            error: function(xml, status, error) {
                Swal.fire({
                    title: 'Oops...',
                    html: 'An error had occurred - ' + error,
                    icon: 'error'
                });
            },
        });
    });

    // Initialize tooptips
    $(function() {
        $('[data-toggle="tooltip"]').tooltip()
    });

    // Initialize popovers
    $(function() {
        $('[data-toggle="popover"]').popover({
            container: 'body',
            trigger: 'hover',
        })
    })
</script>
@endsection()