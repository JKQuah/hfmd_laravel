@extends('layouts.layout')

@section('analytics-active')
<li class="nav-item active">
@endsection

@section('css')
<!-- Styles -->
<link rel="stylesheet" type="text/css" href="/css/analytics.css">
<link rel="stylesheet" href="css/bootstrap-multiselect.css" type="text/css">
@endsection

@section('content')
<div class="analytics-wrapper">
    <div class="card">
        <form action="{{ route('analytics.getAnalyticsResult') }}" method="post">
            @csrf
            <div class="card-header">
                <h5>Customize Analytics Request</h5>
            </div>
            <div class="card-body analytics-request">
                <div class="request"></div>
                <div class="text-center">
                    <button type="button" class="btn btn-secondary rounded-circle" onclick="addRequest()" id="addRequestButton"><i class="far fa-plus"></i></button>
                </div>
            </div>
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-secondary">Submit Request</button>
            </div>
        </form>
    </div>
    <div class="row">
        <!-- Filters -->
        <div class="col-sm-12 col-md-3 filter pr-5">
            <div class="mx-2 my-4">
                <h5 onclick="showFilters()">Filter by <span class="mx-2"><i class="far fa-filter"></i></span></h5>
                <small class="text-secondary">* Select one or more for each filters</small>
            </div>
            <div class="filter-options">
                <div class="accordion my-3" id="accordionExample">
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left font-weight-bold" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Month <span class="float-right"><i class="far fa-minus"></i><i class="far fa-plus"></i></span>
                                </button>
                            </h2>
                        </div>
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        @foreach($firstHalfYears as $fmonth)
                                        <div class="form-group">
                                            <input type="checkbox" name="month[]" id="fmonth-{{$loop->iteration}}" class="mr-1">
                                            <label for="fmonth-{{$loop->iteration}}">{{ $fmonth }}</label>
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="col-6">
                                        @foreach($secondHalfYears as $smonth)
                                        <div class="form-group">
                                            <input type="checkbox" name="month[]" id="smonth-{{$loop->iteration}}" class="mr-1">
                                            <label for="smonth-{{$loop->iteration}}">{{ $smonth }}</label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer">
                                <small>* Select one or more</small>
                            </div>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-header" id="headingTwo">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left collapsed font-weight-bold" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Year <span class="float-right"><i class="far fa-minus"></i><i class="far fa-plus"></i></span>
                                </button>
                            </h2>
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                            <div class="card-body">
                                @foreach($years as $year)
                                <div class="form-group">
                                    <input type="checkbox" name="year[]" id="year-{{$loop->iteration}}" class="mr-1">
                                    <label for="year-{{$loop->iteration}}">{{ $year }}</label>
                                </div>
                                @endforeach
                            </div>
                            <div class="card-footer">
                                <small>* Select one or more</small>
                            </div>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-header" id="headingThree">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left collapsed font-weight-bold" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    State <span class="float-right"><i class="far fa-minus"></i><i class="far fa-plus"></i></span>
                                </button>
                            </h2>
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                            <div class="card-body">
                                @foreach($states as $state)
                                <div class="form-group">
                                    <input type="checkbox" name="state[]" id="state-{{$loop->iteration}}" class="mr-1">
                                    <label for="state-{{$loop->iteration}}">{{ ucwords($state) }}</label>
                                </div>
                                @endforeach
                            </div>
                            <div class="card-footer">
                                <small>* Select one or more</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-9">
            <!-- Analtics Results -->
            <div class="analytics-result-wrapper">
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <!-- State detials -->
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <!-- State map -->
                    </div>
                </div>
                <hr>
                <div class="card text-center">
                    <div class="card-header">
                        <ul class="nav nav-tabs border-bottom-0" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Profile</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Contact</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">...</div>
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">...</div>
                            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript" src="js/bootstrap-multiselect.js"></script>
<script type="text/template" id="request-inputs">
    <div class="request request-numbering">
        <hr class="first-numbering">
        <div class="position-relative text-right first-numbering">
            <button type="button" class="btn btn-danger rounded-circle" style="padding: 0 6.5px" onclick="removeRequest(this, 'numbering')">
                <i class="far fa-times"></i>
            </button>    
        </div>
        <div class="form-row">
            <div class="form-group col-sm-12 col-md-2">
                <label for="request-state-numbering">State</label>
                <select name="request[numbering][state]" id="request-state-numbering" class="form-control" required onchange="changeDistrict(this, 'numbering')">
                    <option value="" hidden>Select...</option>
                    @foreach($states as $state)
                    <option value="{{ $state }}">{{ $state }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-sm-12 col-md-2">
                <label for="request-district-numbering">District</label>
                <select name="request[numbering][district][]" id="request-district-numbering" class="form-control select-multiple" multiple="multiple">
                    <option value="" hidden>Select...</option>
                </select>
            </div>
            <div class="form-group col-sm-12 col-md-2">
                <label for="request-year-numbering" class="w-100">Year</label>
                <select name="request[numbering][year][]" id="request-year-numbering" class="form-control select-multiple" multiple="multiple">
                    @foreach($years as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-sm-12 col-md-2">
                <label for="request-month-numbering">Month</label>
                <select name="request[numbering][month][]" id="request-month-numbering" class="form-control select-multiple" multiple="multiple">
                    @foreach($months as $month)
                    <option value="{{ $loop->iteration }}">{{ $month }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-sm-12 col-md-2">
                <label for="request-week-numbering">Week <small>(Optional)</small></label>
                <input type="number" name="request[numbering][week]" id="request-week-numbering" class="form-control" placeholder="7" min="0" max="53">
            </div>
            <div class="form-group col-sm-12 col-md-2">
                <label for="request-day-numbering">Day <small>(Optional)</small></label>
                <input type="date" name="request[numbering][day]" id="request-day-numbering" class="form-control" min="2009-01-01" max="2015-12-31">
            </div>
        </div>
    </div>
</script>



<script>
    function showFilters() {
        $('.filter-options').toggle();
    }

    var toggle_count = 1;

    function addRequest() {
        var request = $('#request-inputs').html();
        request = request.replaceAll('numbering', toggle_count);
        $('.request').last().after(request);
        if (toggle_count >= 3) {
            $('#addRequestButton').hide();
        }
        $(function() {
            $('.select-multiple').multiselect({
                buttonWidth: '100%',
            });
        });
        toggle_count++;
    }

    function removeRequest(req, numbering) {
        $(req).closest('.request-' + numbering).remove();
        $('#addRequestButton').show();
        toggle_count--;
    }

    function changeDistrict(state, toggle_count){
        $.ajax({
            url: '{{ route("anaytics.getDistrict") }}',
            type: 'get',
            data: {
                state: state.value,
            },
            success: function(districts){
                $("#request-district-" + toggle_count).multiselect('dataprovider', districts);
            }
        })
            
        
    }

    $(document).ready(function() {
        addRequest();
        $('.first-1').remove();

        $('#accordionExample').on('hide.bs.collapse', function() {
            $('.toggle-show .far').addClass('fa-plus').removeClass('fa-minus');
        });

        $('#accordionExample').on('show.bs.collapse', function() {
            $('.toggle-show .far').addClass('fa-minus').removeClass('fa-plus');
        });
    });
</script>

@endsection