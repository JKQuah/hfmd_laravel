@extends('layouts.app')
<link rel="stylesheet" type="text/css" href="/css/login.css">
@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-5 col-lg-6 col-md-8 col-12">
            <div class="card">
                <div class="login-header">{{ __('Register') }}</div>
                <small class="subtitle">Register and wait for approval to access to HFMD Dashboard</small>
                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="title" class="col-md-12 col-form-label text-md-left">{{ __('Title') }}</label>

                            <div class="col-4">
                                <select name="title" id="new-user-title" class="form-control @error('title') is-invalid @enderror" name="title" required>
                                    <option value="Mr." selected>Mr.</option>
                                    <option value="Ms.">Ms.</option>
                                    <option value="Dr.">Dr.</option>
                                    <option value="Prof.">Prof.</option>
                                </select>
                                @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="fname" class="col-form-label text-md-left">{{ __('First Name') }}</label>
                                <input id="fname" type="text" class="form-control @error('fname') is-invalid @enderror" name="fname" value="{{ old('fname') }}" autocomplete="fname" autofocus>
                                @error('fname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="lname" class="col-form-label text-md-left">{{ __('Last Name') }}</label>
                                <input id="lname" type="text" class="form-control @error('lname') is-invalid @enderror" name="lname" value="{{ old('lname') }}" autocomplete="lname">

                                @error('lname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                                              
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-12 col-form-label text-md-left">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-12">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="example@hfmd.com" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="phone" class="col-md-12 col-form-label text-md-left">{{ __('Phone Number') }}</label>
                            
                            <div class="col-md-12">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">+6</div>
                                    </div>
                                    <input id="phone" type="tel" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" placeholder="012-555 8686" required autocomplete="phone">

                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-12 col-form-label text-md-left">{{ __('Password') }}</label>

                            <div class="col-md-12">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-12 col-form-label text-md-left">{{ __('Confirm Password') }}</label>

                            <div class="col-md-12">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="centerBtn">
                                <button type="submit" class="login_button">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                        
                    </form>
                    <hr>
                    <div style="text-align:center">
                        <span>Existing user? Let's <a class="link" href="{{ route('login') }}">{{ __('Login') }}</a></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
