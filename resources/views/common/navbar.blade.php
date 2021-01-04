<!-- Top nav bar -->
<nav class="navbar navbar-expand-lg navbar-light fixed-top">
    <a class="navbar-brand" href="{{ route('dashboard.index') }}">
        <h4>HFMD Dashboard</h4>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
        <ul class="navbar-nav">
            @yield('data-active')
                <a class="nav-link" href="{{ route('data.index') }}">Data</span></a>
            </li>

            @yield('climatic-active')
                <a class="nav-link" href="{{ route('climatic', ['year'=>'2009', 'state'=>'JOHOR']) }}">Climatic</span></a>
            </li>
            
            @yield('analytics-active')
                <a class="nav-link" href="{{ route('analytics') }}">Analytics</a>
            </li>
            @yield('faqs-active')
                <a class="nav-link" href="{{ route('faqs') }}">FAQ</a>
            </li>
                
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;">
                    {{ Auth::user()->fname }} {{ Auth::user()->lname }}
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" data-toggle="modal" data-target="#shareDashboardModal"><i class="far fa-share-alt float-right"></i> Share</a>
                    @can('staff')
                    <!-- <a class="dropdown-item" href="#"><i class="far fa-cog float-right"></i>Setting</a> -->
                    @endcan
                    @can('admin')
                    <a class="dropdown-item" href="{{ route('admin.dashboard') }}">Admin Board</a>
                    @endcan
                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                        {{ __('Logout') }} <i class="far fa-sign-out-alt float-right"></i>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
    </div>
</nav>


<!-- Modal -->
<div class="modal fade p-0" id="shareDashboardModal" tabindex="-1" role="dialog" aria-labelledby="shareDashboardModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shareDashboardModalLabel">Share Dashboard</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body share-wrapper">
                <div class="row">
                    <div class="col col-sm-4 vertical-center">
                        <div class="social-media-wrapper">
                            <div class="social-media-img">
                                <img src="{{ asset('img/social-icon/gmail.jpg') }}" alt="Gmail Logo" title="Share through Gmail">
                            </div>
                            <p>Gmail</p>
                        </div>
                    </div>
                    <div class="col col-sm-4 vertical-center">
                        <div class="social-media-wrapper">
                            <div class="social-media-img">
                                <img src="{{ asset('img/social-icon/facebook.jpg') }}" alt="Gmail Logo" title="Share through Facebook">
                            </div>
                            <p>Facebook</p>
                        </div>
                    </div>
                    <div class="col col-sm-4 vertical-center">
                        <a onclick="whatsappMe()">
                            <div class="social-media-wrapper">
                                <div class="social-media-img">
                                    <img src="{{ asset('img/social-icon/whatsapp.jpg') }}" alt="Gmail Logo" title="Share through Whatsapp">
                                </div>
                                <p>Whatsapp</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <p class="share-dashboard-message">* You may share this dashboard to your family and friends through the above platform.</p>
            </div>
        </div>
    </div>
</div>

@section('js')
<script>
    function whatsappMe() {
        window.open('https://api.whatsapp.com/send?phone=&text={{ route("dashboard.index") }}','_blank')
    }
</script>
@endsection