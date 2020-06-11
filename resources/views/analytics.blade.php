@extends('layouts.layout')

@section('analytics-active')
<li class="nav-item active">
@endsection

@section('content')
<div class="wrapper">
    <div class="row filter">
        <div class="col col-sm-4 col-md-4">
            <div class="card border-info">
                <div class="card-header">
                    Month
                </div>
                <div class="card-body row">
                    <div class="form-group col">
                    <select id="inputStartMonth" class="form-control" name="start_month">
                        <option selected>Choose...</option>
                        <option>Jan</option>
                    </select>
                    </div>
                    <div class="form-group col">
                    <select id="inputEndMonth" class="form-control" name="end_month">
                        <option selected>Choose...</option>
                        <option>Jan</option>
                    </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col col-sm-4 col-md-4">
            <div class="card border-info">
                <div class="card-header">
                    Year
                </div>
                <div class="card-body row">
                    <div class="form-group col">
                    <select id="inputStartYear" class="form-control" name="start_year">
                        <option selected>Choose...</option>
                        <option>2008</option>
                    </select>
                    </div>
                    <div class="form-group col">
                    <select id="inputEndYear" class="form-control" name="end_year">
                        <option selected>Choose...</option>
                        <option>2008</option>
                    </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col col-sm-4 col-md-4">
            <div class="card border-info">
                <div class="card-header">
                    State
                </div>
                <div class="card-body row">
                    <div class="form-group col">
                    <select id="inputState" class="form-control" name="state">
                        <option selected>Choose...</option>
                        <option>Perlis</option>
                    </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
