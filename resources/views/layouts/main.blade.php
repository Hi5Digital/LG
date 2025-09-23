<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LushGirls Admin</title>
    <link rel="stylesheet" href="{{ asset('assets/vendors/core/core.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather-font/css/iconfont.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/flag-icon-css/css/flag-icon.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/demo_1/style.css') }}">
    <link rel="shortcut icon" href="https://www.lushgirls.co.za/img/icon320.png" />
</head>
<body>
<div class="main-wrapper">
    <nav class="sidebar">
        <div class="sidebar-header">
            <a href="/" class="sidebar-brand">
                LushGirls
            </a>
            <div class="sidebar-toggler not-active">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        <div class="sidebar-body">
            <ul class="nav">
                <li class="nav-item nav-category">Main</li>
                <li class="nav-item {{ Route::currentRouteNamed(['home']) ? 'active' : '' }}">
                    <a href="/" class="nav-link">
                        <i class="link-icon" data-feather="box"></i>
                        <span class="link-title">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item {{ Route::currentRouteNamed(['models', 'models.details']) ? 'active' : '' }}">
                    <a href="{{ route('models') }}" class="nav-link">
                        <i class="link-icon" data-feather="users"></i>
                        <span class="link-title">Models</span>
                    </a>
                </li>
                <li class="nav-item {{ Route::currentRouteNamed(['body', 'bust', 'ethnics', 'orientations', 'towns']) ? 'active' : '' }}">
                    <a class="nav-link" data-toggle="collapse" href="#tables" role="button" aria-expanded="false" aria-controls="tables">
                        <i class="link-icon" data-feather="list"></i>
                        <span class="link-title">Tables</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse {{ Route::currentRouteNamed(['body', 'bust', 'ethnics', 'orientations', 'towns']) ? 'show' : '' }}" id="tables">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('body') }}" class="nav-link {{ Route::currentRouteNamed(['body']) ? 'active' : '' }}">Body Types</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('bust') }}" class="nav-link {{ Route::currentRouteNamed(['bust']) ? 'active' : '' }}">Bust Sizes</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('ethnics') }}" class="nav-link {{ Route::currentRouteNamed(['ethnics']) ? 'active' : '' }}">Ethnics</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('orientations') }}" class="nav-link {{ Route::currentRouteNamed(['orientations']) ? 'active' : '' }}">Orientations</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('towns') }}" class="nav-link {{ Route::currentRouteNamed(['towns']) ? 'active' : '' }}">Towns</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item {{ Route::currentRouteNamed(['others']) ? 'active' : '' }}">
                    <a href="{{ route('others') }}" class="nav-link">
                        <i class="link-icon" data-feather="users"></i>
                        <span class="link-title">Others</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="page-wrapper">
        <nav class="navbar">
            <a href="#" class="sidebar-toggler">
                <i data-feather="menu"></i>
            </a>
            <div class="navbar-content">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown nav-profile">
                        <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="{{ asset('assets/images/avatar.png') }}" alt="profile">
                        </a>
                        <div class="dropdown-menu" aria-labelledby="profileDropdown">
                            <div class="dropdown-header d-flex flex-column align-items-center">
                                <div class="figure mb-3">
                                    <img src="{{ asset('assets/images/avatar.png') }}" alt="">
                                </div>
                                <div class="info text-center">
                                    <p class="name font-weight-bold mb-0">{{ auth()->user()['name'] }}</p>
                                    <p class="email text-muted mb-3">{{ auth()->user()['email'] }}</p>
                                </div>
                            </div>
                            <div class="dropdown-body">
                                <ul class="profile-nav p-0 pt-3">
                                    <li class="nav-item">
                                        <a href="javascript:void(0);" onclick="$('#logout-form').submit()" class="nav-link">
                                            <i data-feather="log-out"></i>
                                            <span>Log Out</span>
                                        </a>
                                        <form action="{{ route('logout') }}" id="logout-form" class="d-none" method="post">@csrf</form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- partial -->

        <div class="page-content">
            @if(session('success'))
                <div class="alert alert-fill-success alert-dismissible fade show" role="alert">
                    <i data-feather="check-circle"></i>
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @elseif(session('error'))
                <div class="alert alert-fill-danger alert-dismissible fade show" role="alert">
                    <i data-feather="x-circle"></i>
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @yield('content')
        </div>

        <!-- partial:partials/_footer.html -->
        <footer class="footer d-flex flex-column flex-md-row align-items-center justify-content-between">
            <p class="text-muted text-center text-md-left">Copyright Â© {{ date('Y') }} <a href="https://lushgirls.co.za" target="_blank">LushGirls.co.za</a>. All rights reserved</p>
        </footer>
        <!-- partial -->

    </div>
</div>

<script src="{{ asset('assets/vendors/core/core.js') }}"></script>
<script src="{{ asset('assets/vendors/chartjs/Chart.min.js') }}"></script>
<script src="{{ asset('assets/vendors/datatables.net/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>
<script src="{{ asset('assets/vendors/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/vendors/feather-icons/feather.min.js') }}"></script>
<script src="{{ asset('assets/js/template.js') }}"></script>
<script src="{{ asset('assets/js/data-table.js') }}"></script>
<script src="{{ asset('assets/js/dashboard.js') }}"></script>
<script>

</script>
@yield('script')
</body>
</html>
