<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title')</title>
    @include('common.header')
    @yield('css')
</head>
<style>
    .loading-screen{ display:none; position:fixed; z-index:10; background:rgba(0, 0, 0, .2); width:100%; height:100%; text-align:center; padding-top:200px} .loading-active{ display:block} .loading-img{ width:15%; -webkit-animation:breathing 0.7s ease-out infinite normal; animation:breathing 0.7s ease-out infinite normal;} @-webkit-keyframes breathing{ 0%{ -webkit-transform:scale(0.9); transform:scale(0.9);} 25%{ -webkit-transform:scale(1); transform:scale(1);} 60%{ -webkit-transform:scale(0.9); transform:scale(0.9);} 100%{ -webkit-transform:scale(0.9); transform:scale(0.9);}} @keyframes breathing{ 0%{ -webkit-transform:scale(0.9); -ms-transform:scale(0.9); transform:scale(0.9);} 25%{ -webkit-transform:scale(1); -ms-transform:scale(1); transform:scale(1);} 60%{ -webkit-transform:scale(0.9); -ms-transform:scale(0.9); transform:scale(0.9);} 100%{ -webkit-transform:scale(0.9); -ms-transform:scale(0.9); transform:scale(0.9);}}

    .share-wrapper a {
        text-decoration: none;
        color: #000;
    }
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
<!-- javascript -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script>
    //check the page is loaded or not

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
@yield('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
@if(Session::has('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: '{!!Session::get("success")!!}',
        showConfirmButton: false,
        timer: 2000
    })
</script>
@endif
@if(Session::has('error'))
<script>
    Swal.fire({
        title: 'Oops...',
        html: '{!!Session::get("error")!!}',
        icon: 'error'
    })
</script>
@endif
@if(Session::has('missing'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    Swal.fire({
        title: '{!!Session::get("missing")!!}',
        text: 'Data is not available',
        icon: 'warning',
        showCloseButton: true,
        showConfirmButton: false,
    })
</script>
@endif
</html>