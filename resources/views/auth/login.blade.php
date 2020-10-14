@extends('layouts.app')
<link rel="stylesheet" type="text/css" href="/css/login.css">
@section('swal')
@if(Session::has('success'))
<script>
    Swal.fire('Success', '{{ Session::get("success") }}', 'success');
</script>
@endif
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12 col-md-10 col-xl-8">
            <div class="card">
                <div class="row m-0">
                    <div class="col-md-6 d-flex"><img class="w-100 login-img" loading="lazy" src="{{asset('img/login.svg')}}" alt="login_img"></div>
                    <div class="col-md-6">
                        <div class="login-header">{{ __('Login') }}</div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="form-group row">

                                    <label for="email" class="col-md-12 col-form-label text-md-left">{{ __('Email Address') }}</label>

                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="far fa-envelope"></i></div>
                                            </div>
                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                        </div>
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password" class="col-md-12 col-form-label text-md-left">{{ __('Password') }}</label>

                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="fas fa-lock"></i></div>
                                            </div>
                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                        </div>
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        @if (Route::has('password.request'))
                                        <a class="forget link" href="{{ route('password.request') }}">
                                            {{ __('Forgot Password?') }}
                                        </a>
                                        @endif
                                        <div class="checkRememberMe">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                            <label class="form-check-label" for="remember">
                                                {{ __('Remember Me') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="form-group row centerBtn">
                                    <div class="centerBtn">
                                        <button type="submit" class="login_button">
                                            {{ __('LOGIN') }}
                                        </button>
                                    </div>
                                </div>

                            </form>
                            <hr>
                            <div style="text-align:center">
                                <span>First time user? <a class="link" href="{{ route('register') }}">{{ __('Register') }}</a></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection