<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iot Dashboard</title>

    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="{{ asset('/') }}css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
    <link href="{{asset('/')}}css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="{{asset('/')}}css/core.css" rel="stylesheet" type="text/css">
    <link href="{{asset('/')}}css/components.css" rel="stylesheet" type="text/css">
    <link href="{{asset('/')}}css/colors.css" rel="stylesheet" type="text/css">
@yield('additionalCss')
<!-- /global stylesheets -->

    <!-- Core JS files -->
    <script type="text/javascript" src="{{ asset('/') }}js/pace.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}js/libraries/jquery.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}js/libraries/bootstrap.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}js/blockui.min.js"></script>
@yield('additionalCoreJs')
<!-- /core JS files -->

    <!-- Theme JS files -->
    <script type="text/javascript" src="{{ asset('/') }}js/headroom.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}js/headroom_jquery.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}js/nicescroll.min.js"></script>
    @yield('additionalThemeJs')

    <script type="text/javascript" src="{{ asset('/') }}js/app.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}js/layout_fixed_custom.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}js/layout_navbar_hideable_sidebar.js"></script>
@yield('additionalAppJs')
<!-- /theme JS files -->

</head>

<body class="navbar-top">

<!-- Main navbar -->
<div class="navbar navbar-default navbar-fixed-top header-highlight bg-grey-800">
    <div class="navbar-header" style="background-color: white;">
        <a class="navbar-brand" href="{{ url('/') }}"><img src="{{ asset('/') }}images/telSr.png" alt="Telekom logo" style="width: 200px; height: auto;" ></a>
    </div>

    <div class="navbar-collapse collapse " id="navbar-mobile">
        <ul class="nav navbar-nav">
            <li><a class="sidebar-control sidebar-main-toggle hidden-xs"><i class="icon-paragraph-justify3" style="color: white;"></i></a></li>
        </ul>

        <ul class="nav navbar-nav navbar-right" >
            <li class="dropdown dropdown-user">
                <a class="dropdown-toggle test" data-toggle="dropdown">
                    <span>{{ \Illuminate\Support\Facades\Auth::user()->getUloga->naziv }} | {{ \Illuminate\Support\Facades\Auth::user()->ime.' '.\Illuminate\Support\Facades\Auth::user()->prezime  }} <i class="caret"></i></span>
                </a>

                <ul class="dropdown-menu dropdown-menu-right">
                    <li><a href="{{ url('/profile/'.\Illuminate\Support\Facades\Auth::id()) }}"><i class="icon-user"></i> Profil</a></li>
                    <li><a href="{{ url('/logout') }}"><i class="icon-switch2"></i> Logout</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>
<!-- /main navbar -->


<!-- Page container -->
<div class="page-container">

    <!-- Page content -->
    <div class="page-content">

        <!-- Main sidebar -->
        <div class="sidebar sidebar-main sidebar-fixed bg-grey-800">
            <div class="sidebar-content">

                <!-- Main navigation -->
                <div class="sidebar-category sidebar-category-visible">
                    <div class="category-content no-padding">
                        <ul class="navigation navigation-main navigation-accordion">

                            <li class="navigation-header"><span>Glavni meni</span> <i class="icon-menu" title="Main pages"></i></li>
                            <li @yield('homePageActive') ><a href="{{ url('/home') }}"><i class="icon-home4"></i> <span>Početna</span></a></li>

                            @if(\Illuminate\Support\Facades\Auth::user()->getUloga->id == 1 || \Illuminate\Support\Facades\Auth::user()->getUloga->id == 2)
                                <li  @yield('addNewActive')  ><a href="{{ url('/addnew') }}"><i class="icon-plus3"></i> <span>Dodaj novi ugovor</span></a></li>
                            @endif

                            @yield('edictcontract')

                            @if(\Illuminate\Support\Facades\Auth::user()->getUloga->id == 1)
                                <li class="navigation-header"><span>Administrator podrske</span> <i class="icon-menu" title="Main pages"></i></li>
                                <li @yield('systemmanaging') >
                                    <a href="#"><i class="icon-gear"></i> <span>Menadzment sistema</span></a>
                                    <ul>
                                        <li @yield('stavkafakture')><a href="{{ url('/menage/stavkafakture') }}">Stavka fakture</a></li>
                                        <li @yield('tipugovora')><a href="{{ url('/menage/tipugovora') }}">Tip ugovora</a></li>
                                        <li @yield('tipservisa')><a href="{{ url('/menage/tipservisa') }}">Tip servisa</a></li>
                                        <li @yield('tehnologija')><a href="{{ url('/menage/tehnologije') }}">Tip tehnologije</a></li>
                                        <li @yield('partner')><a href="{{ url('/menage/partner') }}">Partner</a></li>
                                        <li @yield('nazivservisa')><a href="{{ url('/menage/nazivservisa') }}">Nazivi servisa</a></li>
                                        <li @yield('vrstasenzora')><a href="{{ url('/menage/vrstasenzora') }}">Vrste senzora</a></li>
                                        <li @yield('lokacijaapp')><a href="{{ url('/menage/lokacijaapp') }}">Lokacija aplikacije</a></li>
                                    </ul>
                                </li>
                            @endif
{{--                            @if(\Illuminate\Support\Facades\Auth::user()->getUloga->id == 1 || \Illuminate\Support\Facades\Auth::user()->getUloga->id == 2)--}}
{{--                            @if(\Illuminate\Support\Facades\Auth::user()->getUloga->id == 1)--}}
{{--                                <li class="navigation-header"><span>Administratorski meni</span> <i class="icon-menu" title="Main pages"></i></li>--}}
{{--                                <li @yield('addNewUser') ><a href="{{ url('/addnewuser') }}"><i class="icon-user-plus"></i> <span>Dodaj novog korisnika portala</span></a></li>--}}
{{--                            @endif--}}

                        </ul>
                    </div>
                </div>
                <!-- /main navigation -->

            </div>
        </div>
        <!-- /main sidebar -->


        <!-- Main content -->
        <div class="content-wrapper">

        @yield('pageHeader')

        <!-- Content area -->
            <div class="content">

            @yield('content')

            <!-- Footer -->
                <div class="footer text-muted">
                    &copy; 2021. <a href="{{ url('/home') }}">IoT ugovori</a> by <a href="#" target="">TERI Engineering</a>
                </div>
                <!-- /footer -->

            </div>
            <!-- /content area -->

        </div>
        <!-- /main content -->

    </div>
    <!-- /page content -->

</div>
<!-- /page container -->

</body>
</html>
