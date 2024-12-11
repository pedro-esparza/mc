<nav class="navbar navbar-expand-md navbar-light bg-light text-dark shadow-lg sticky-top py-0">
    <div class="container px-md-0 px-sm-3 px-3">
        <a id="thi-dau" class="navbar-brand text-dark" href="{{ url('/') }}">
            <img src="{{ URL::to('/') }}/img/app-icons/logo-chess.png" class="chess-logo" alt="chess logo">
            <h1 class="d-inline" style="font-size: inherit !important;"><strong>Chess</strong></h1>
        </a>
        <button class="navbar-toggler border-secondary text-secondary bg-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            {{-- <ul class="navbar-nav me-auto">
            </ul> --}}

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto" style="margin-right: 2px;">
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}"><i class="far fa-house"></i> Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a id="dashboardDropdown" class="nav-link dashboard dropdown-toggle" href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre><i class="far fa-trophy-alt"></i> Play</a>
                    <div class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dashboardDropdown">
                        <a class="dropdown-item{{ url()->current() == url('/rooms') ? ' active disabled' : '' }}" href="{{ url('/rooms') }}"><i class="far fa-list-alt"></i> Rooms</a>
                        <a class="dropdown-item{{ url()->current() == url('/tournament') ? ' active disabled' : '' }}" href="{{ url('/tournament') }}"><i class="far fa-list"></i> Tournament</a>
                        <a class="dropdown-item{{ url()->current() == url('/ranking') ? ' active disabled' : '' }}" href="{{ url('/ranking') }}"><i class="far fa-star"></i> Ranking</a>
                        <a class="dropdown-item{{ url()->current() == url('/search') ? ' active disabled' : '' }}" href="{{ url('/search') }}"><i class="far fa-search"></i> Players search</a>
                        <a class="dropdown-item{{ url()->current() == url('/history') ? ' active disabled' : '' }}" href="{{ url('/history') }}"><i class="far fa-archive"></i> History</a>
                        <a class="dropdown-item{{ url()->current() == url('/puzzle') ? ' active disabled' : '' }}" href="{{ url('/puzzle') }}"><i class="far fa-puzzle-piece"></i> Puzzle</a>
                    </div>
                </li>
                <!-- Authentication Links -->
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link login" href="{{ route('login') }}"><i class="far fa-sign-in"></i> {{ __('Login') }}</a>
                        </li>
                    @endif

                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link register" href="{{ route('register') }}"><i class="far fa-user-plus"></i> {{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle text-ligh profile" href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            <img src="{{ Avatar::create(Auth::user()->name)->setDimension(24)->setFontSize(12) }}" /> {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="navbarDropdown">
                            <a href="{{ url('/my-profile') }}" class="dropdown-item{{ url()->current() == url('/my-profile') ? ' active disabled' : '' }}"><i class="far fa-id-card"></i> My profile</a>
                            <a href="{{ url('/change-name') }}" class="dropdown-item{{ url()->current() == url('/change-name') ? ' active disabled' : '' }}"><i class="far fa-user-edit"></i> Change name</a>
                            <a href="{{ url('/change-password') }}" class="dropdown-item{{ url()->current() == url('/change-password') ? ' active disabled' : '' }}"><i class="far fa-lock-alt"></i> Change password</a>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                <i class="far fa-sign-out"></i> {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>