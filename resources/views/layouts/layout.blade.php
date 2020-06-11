<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Admin Dashboard</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:500,600" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <!-- Styles -->
        <link rel="stylesheet" type="text/css" href="/css/home.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    </head>
    <body>
        <!-- Top nav bar -->
        <nav class="navbar navbar-expand-lg navbar-light sticky-top" style="padding: 20px 80px 0">
        <a class="navbar-brand" href="{{ route('data.index') }}">
            <!-- <img src="/img/logo.svg" width="30px" height= "100%" alt="logo"> -->
            <h4>Admin Dashboard</h4>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
            <ul class="navbar-nav">
            @yield('data-active')
                <a class="nav-link" href="{{ route('data.index') }}">Data</span></a>
            </li>
            @yield('analytics-active')
                <a class="nav-link" href="{{ route('analytics') }}">Analytics</a>
            <!-- </li>
            @yield('news-active')
                <a class="nav-link" href="{{ route('news') }}">News</a>
            </li> -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;">
                {{ Auth::user()->fname }} {{ Auth::user()->lname }}
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                <a class="dropdown-item" href="{{ route('profile.index') }}">{{ __('User List') }}</a>
                <a class="dropdown-item" href="#">Setting</a>
                <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                </div>
            </li>
            </ul>
        </div>
    </nav>

    <main>
        <!-- Main content -->
        @yield('content')
    </main>

    <!-- javascript -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    </body>
    
    <!-- Footer -->
    <footer class="footer"> 
            <p>Hand, Foot, Mouth Disease Dashboard | FCSIT, Universtiy Malaya | 2020 - 2021</p>
            <p>&copy All rights reserved | 2020 Copyright</p>
        <!-- <a class="grey-text text-lighten-4 right" href="#!">Case Reporting System</a> -->
    </footer>
</html>        