<!-- Top nav bar -->
<nav class="navbar navbar-expand-lg navbar-light sticky-top" style="padding: 20px 80px 0">
    <a class="navbar-brand" href="{{ route('dashboard.index') }}">
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
                @can('staff')
                    <a class="dropdown-item" href="{{ route('users.index') }}">{{ __('User List') }}</a>
                    <a class="dropdown-item" href="#">Setting</a>
                @endcan
                @can('admin')
                <a class="dropdown-item" href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
                @endcan
                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
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