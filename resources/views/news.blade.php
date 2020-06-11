@extends('layouts.layout')

@section('news-active')
<li class="nav-item active">
@endsection

@section('content')
<div class="wrapper">
    <div class="row">
        <div class="col-1"></div>
        <div class="col-3">
            <form action="" method="get">
            <div class="form-group">
                <label for="news">News</label>
                <input type="text" class="form-control" id="news" placeholder="search content">
            </div>
            <div class="form-group">
                <label for="inputState">States</label>
                <select id="inputState" class="form-control">
                    <option selected>Choose...</option>
                    @foreach($states as $state)
                    <option>{{ $state->states }}</option>
                    @endforeach
                </select>
            </div>
            </form>
            <a href="#" style="float: right">Create a new post</a>
        </div>
        <div class="col-8">
        <div class="card mb-3">
            <div class="row no-gutters">
                <div class="col-md-2">
                <img src="..." class="card-img" alt="...">
                </div>
                <div class="col-md-10">
                <div class="card-body">
                    <h5 class="card-title">Card title</h5>
                    <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                    <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small>
                        <a href="" class="delete" style="float: right">Delete</a>
                        <a href="" class="edit" style="float: right">Edit</a>
                    </p>
                </div>
                </div>
            </div>
        </div>

        </div>
    </div>

</div>
@endsection
