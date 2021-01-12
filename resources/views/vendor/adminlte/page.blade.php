@extends('adminlte::master')

@inject('layoutHelper', \JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper)

@if($layoutHelper->isLayoutTopnavEnabled())
    @php( $def_container_class = 'container' )
@else
    @php( $def_container_class = 'container-fluid' )
@endif

@section('adminlte_css')
    @stack('css')
    @yield('css')
    <style>
    .loading-screen{ display:none; position:fixed; z-index:9999; background:rgba(0, 0, 0, .2); width:100%; height:100%; text-align:center; padding-top:200px} .loading-active{ display:block} .loading-img{ width:15%; -webkit-animation:breathing 0.7s ease-out infinite normal; animation:breathing 0.7s ease-out infinite normal;} @-webkit-keyframes breathing{ 0%{ -webkit-transform:scale(0.9); transform:scale(0.9);} 25%{ -webkit-transform:scale(1); transform:scale(1);} 60%{ -webkit-transform:scale(0.9); transform:scale(0.9);} 100%{ -webkit-transform:scale(0.9); transform:scale(0.9);}} @keyframes breathing{ 0%{ -webkit-transform:scale(0.9); -ms-transform:scale(0.9); transform:scale(0.9);} 25%{ -webkit-transform:scale(1); -ms-transform:scale(1); transform:scale(1);} 60%{ -webkit-transform:scale(0.9); -ms-transform:scale(0.9); transform:scale(0.9);} 100%{ -webkit-transform:scale(0.9); -ms-transform:scale(0.9); transform:scale(0.9);}}
    </style>
@stop

@section('classes_body', $layoutHelper->makeBodyClasses())

@section('body_data', $layoutHelper->makeBodyData())

@section('body')
    <div class="wrapper">
        <div class="loading-screen"><img class="loading-img" src="{{ asset('img/hfmd-logo.png') }}" alt="loading.."></div>
        {{-- Top Navbar --}}
        @if($layoutHelper->isLayoutTopnavEnabled())
            @include('adminlte::partials.navbar.navbar-layout-topnav')
        @else
            @include('adminlte::partials.navbar.navbar')
        @endif

        {{-- Left Main Sidebar --}}
        @if(!$layoutHelper->isLayoutTopnavEnabled())
            @include('adminlte::partials.sidebar.left-sidebar')
        @endif

        {{-- Content Wrapper --}}
        <div class="content-wrapper {{ config('adminlte.classes_content_wrapper') ?? '' }}">

            {{-- Content Header --}}
            <div class="content-header">
                <div class="{{ config('adminlte.classes_content_header') ?: $def_container_class }}">
                    @yield('content_header')
                </div>
            </div>

            {{-- Main Content --}}
            <div class="content">
                <div class="{{ config('adminlte.classes_content') ?: $def_container_class }}">
                    @yield('content')
                </div>
            </div>

        </div>

        {{-- Footer --}}
        @hasSection('footer')
            @include('adminlte::partials.footer.footer')
        @endif

        {{-- Right Control Sidebar --}}
        @if(config('adminlte.right_sidebar'))
            @include('adminlte::partials.sidebar.right-sidebar')
        @endif

    </div>
@stop

@section('adminlte_js')
    @stack('js')
    @yield('js')
    <script>
        document.onreadystatechange = function() {
            if (document.readyState == "complete") {
                $(".loading-screen").removeClass('loading-active');
                console.log("fully loaded")
            }
        }
        $(window).on('beforeunload', function() {
            $(".loading-screen").addClass('loading-active');
        });
    </script>
@stop
