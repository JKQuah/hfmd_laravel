<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    @include('common.header')
</head>
<style>
    .loading-screen{ display:none; position:fixed; z-index:10; background:rgba(0, 0, 0, .2); width:100%; height:100%; text-align:center; padding-top:200px} .loading-active{ display:block} .loading-img{ width:15%; -webkit-animation:breathing 0.7s ease-out infinite normal; animation:breathing 0.7s ease-out infinite normal;} @-webkit-keyframes breathing{ 0%{ -webkit-transform:scale(0.9); transform:scale(0.9);} 25%{ -webkit-transform:scale(1); transform:scale(1);} 60%{ -webkit-transform:scale(0.9); transform:scale(0.9);} 100%{ -webkit-transform:scale(0.9); transform:scale(0.9);}} @keyframes breathing{ 0%{ -webkit-transform:scale(0.9); -ms-transform:scale(0.9); transform:scale(0.9);} 25%{ -webkit-transform:scale(1); -ms-transform:scale(1); transform:scale(1);} 60%{ -webkit-transform:scale(0.9); -ms-transform:scale(0.9); transform:scale(0.9);} 100%{ -webkit-transform:scale(0.9); -ms-transform:scale(0.9); transform:scale(0.9);}}
</style>
<body>
    @include('common.navbar')
    <main>
        <!-- Loading bar -->
        <div class="loading-screen"><img class="loading-img" src="{{ asset('img/hfmd-logo.png') }}" alt="loading.."></div>
        <!-- Main content -->
        @yield('content')
    </main>

</body>

@include('common.footer')
@yield('footer')

</html>