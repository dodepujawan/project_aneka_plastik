 <!-- Topbar -->
 <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
        <li class="nav-item dropdown no-arrow d-sm-none">
            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
            </a>
            <!-- Dropdown - Messages -->
            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small"
                            placeholder="Search for..." aria-label="Search"
                            aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{session('name')}}</span>
                <img class="img-profile rounded-circle" src="{{ asset('assets/gambar/user-286-512.png') }}">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="userDropdown">
                @php
                $user = Auth::user();
                $allowedRoles = ['programmer', 'admin'];
                @endphp

                @if (in_array($user->roles, $allowedRoles))
                <a class="dropdown-item user-create-register" href="{{ route('register') }}">
                    <i class="fas fa-address-card fa-sm fa-fw mr-2 text-primary"></i>
                    Register User
                </a>
                @endif
                <a class="dropdown-item edit-register" href="#">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-primary"></i>
                    Edit Account
                </a>
                {{-- @php
                $user = Auth::user();
                $allowedRoles = ['programmer', 'admin'];
                @endphp --}}

                @if (in_array($user->roles, $allowedRoles))
                <a class="dropdown-item list-register" href="#">
                    <i class="fas fa-address-book fa-sm fa-fw mr-2 text-primary"></i>
                    List Users
                </a>
                <div class="dropdown-divider"></div>
                @endif
                {{-- Cabang --}}
                {{-- @php
                $user = Auth::user();
                $allowedRoles = ['admin'];
                @endphp --}}

                @if (in_array($user->roles, $allowedRoles))
                <a class="dropdown-item cabang-create" href="#">
                    <i class="fas fa-building fa-sm fa-fw mr-2 text-primary"></i>
                    Create Cabang
                </a>
                @endif

                @php
                $user = Auth::user();
                $allowedRoles = ['admin'];
                @endphp

                @if (in_array($user->roles, $allowedRoles))
                <a class="dropdown-item cabang-list" href="#">
                    <i class="fas fa-city fa-sm fa-fw mr-2 text-primary"></i>
                    List Cabang
                </a>
                <div class="dropdown-divider"></div>
                @endif

                @php
                $user = Auth::user();
                $allowedRoles = ['admin'];
                @endphp

                @if (in_array($user->roles, $allowedRoles))
                <a class="dropdown-item edit-pajak" href="#">
                    <i class="fas fa-money-bill fa-sm fa-fw mr-2 text-primary"></i>
                    Update Pajak
                </a>
                <div class="dropdown-divider"></div>
                @endif
                {{-- End Of Cabang --}}
                {{-- <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">
                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                    Settings
                </a>
                <a class="dropdown-item" href="#">
                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                    Activity Log
                </a> --}}
                <a class="dropdown-item" href="{{route('actionlogout')}}">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-danger"></i>
                    Logout
                </a>
            </div>
        </li>

    </ul>

</nav>
<!-- End of Topbar -->
