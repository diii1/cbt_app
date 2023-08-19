<header class="header-navbar fixed">
    <div class="toggle-mobile action-toggle"><i class="fas fa-bars"></i></div>
    <div class="header-wrapper">
        <div class="header-left" style="width: 28%;">
            {{-- <div class="theme-switch-icon"></div> --}}
            <div id="clock" class="d-flex justify-content-between align-items-center h-100">
                <div id="date"></div>
                <div id="time"></div>
            </div>
        </div>
        <div class="header-content">
            <div class="dropdown dropdown-menu-end">
                <a href="#" class="user-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="label">
                        <span></span>
                        <div>{{ auth()->user()->name }}</div>
                    </div>
                    {{-- <img class="img-user" src="../assets/images/avatar1.png" alt="user"srcset=""> --}}
                    <img class="img-user" src="{{ asset('assets/images/avatar1.png') }}" alt="user"srcset="">
                </a>
                <ul class="dropdown-menu small">
                    <li class="menu-content ps-menu">
                        {{-- @hasanyrole('admin|guru')
                            <a href="{{ route('user_setting') }}">
                                <div class="description">
                                    <i class="ti-settings"></i> Setting
                                </div>
                            </a>
                        @endhasanyrole --}}
                        {{-- <a href="{{ route('user_profile') }}">
                            <div class="description">
                                <i class="ti-user"></i> Profile
                            </div>
                        </a>
                        <a href="{{ route('user_setting') }}">
                            <div class="description">
                                <i class="ti-settings"></i> Setting
                            </div>
                        </a> --}}
                        <form action="{{ route('logout') }}" method="post">
                            @csrf
                            <a href="#" onclick="this.parentNode.submit();">
                                <div class="description">
                                    <i class="ti-power-off"></i> Logout
                                </div>
                            </a>
                        </form>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</header>
