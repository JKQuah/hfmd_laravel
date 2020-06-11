@extends('layouts.app')

@section('content')
<div class="flex-center position-ref full-height">
    <div class="content" style="text-align: center">
        <div class="title m-b-md">
            <h3>Common Dashboard</h3>
        </div>
        <p>Coming soon! Stay tuned</p>
        <a class="link" href="{{ route('login') }}">{{ __('Login') }}</a>
    </div>
</div>
@endsection()