@extends('layouts.app')
<link rel="stylesheet" type="text/css" href="/css/login.css">
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-4 col-lg-6 col-md-8 col-12">
            <div class="card">
                <p>{{ session('mssg')}}</p>
                <div class="login-header">{{ __('Login') }}</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                        <!-- <span class="material-icons">email</span> -->
                            <label for="email" class="col-md-12 col-form-label text-md-left">{{ __('Email Address') }}</label>

                            <div class="col-md-12">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                        <!-- <span class="material-icons">lock</span> -->
                            <label for="password" class="col-md-12 col-form-label text-md-left">{{ __('Password') }}</label>

                            <div class="col-md-12">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

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

                        <div class="form-group row">
                            <label for="inputStatus" class="col-md-4 col-4 col-form-label text-md-left">{{ __('Status') }}</label>

                            <div class="col-md-6 col-6">
                                <select id="inputStatus" class="form-control" name="role">
                                    <option selected>Choose...</option>
                                    <option value="admin">{{__('Admin')}}</option>
                                    <option value="public">{{__('Public')}}</option>
                                </select>
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
@endsection
