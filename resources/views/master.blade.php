<?php
$loggedIn = Auth::check();

if ($loggedIn)
{
    $user = Auth::user();
}

?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <![endif]-->
    <title>@yield('title')</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="/assets/css/readable.bootstrap.min.css" rel="stylesheet" />
    <!-- FONT AWESOME ICONS  -->
    <link href="/assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="/assets/css/style.css" rel="stylesheet" />
    <script src="/assets/js/jquery-1.11.1.js" defer></script>
    <script src="/assets/js/bootstrap.js" defer></script>
    <script src="/assets/js/scripts.js" defer></script>
    <!-- HTML5 Shiv and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <nav class="navbar menu-section">
        <div class="container container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false">
                    Menu
                </button>
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <img src="/assets/img/abtl_logo.png" id="header-logo" />
            </a>
            </div>
            <div class="collapse navbar-collapse" id="navbar">
                <ul id="menu-top" class="nav navbar-nav navbar-right">
                @if($loggedIn)
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('website.index') }}">Websites</a></li>
                    <li><a href="{{ route('account') }}">Account</a></li>
                @endif
                    <li><a href="{{ route('pricing') }}">Pricing</a></li>
                    <li><a href="{{ route('help') }}">FAQ</a></li>
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                @if($loggedIn)
                    <li><a onclick="{{ route('logout') }}">Log out</a></li>
                @else
                    <li><a href="{{ route('register') }}">Sign up</a></li>
                    <li><a href="{{ route('login') }}">Log in</a></li>
                @endif
                </ul>
            </div>
        </div>
    </nav>
    <!-- MENU SECTION END-->
    <div class="content-wrapper">
        <div class="container">
            @yield('breadcrumbs')
            <div class="row">
                <div class="col-md-12">
                    <h4 class="page-head-line">@yield('title')</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    @if(Session::has('success'))
                        <div class="alert alert-success">
                            <strong>Success!</strong> {{ Session::get('success') }}
                        </div>
                    @endif
                    @if(Session::has('fail'))
                        <div class="alert alert-danger">
                            <strong>Oops...</strong> {{ Session::get('fail') }}
                        </div>
                    @endif
                    @if(Session::has('warning'))
                        <div class="alert alert-warning">
                            <strong>Warning</strong> {{ Session::get('warning') }}
                        </div>
                    @endif

                    @if(!Session::has('warning') && $loggedIn && $user->getAvailableResources() === 0)
                        <div class="alert alert-warning">
                            <strong>Uh-oh</strong> You can no longer run any tests because you ran out of resources. Please consider <a href="{{ route('pricing') }}">purchasing more</a> to resume testing.
                        </div>

                    @elseif($loggedIn && $user->lowResources())
                        <div class="alert alert-info">
                            <strong>Just so you know</strong> Currently you have {{ $user->getAvailableResources() }} visitors left but you need {{ $user->getCurrentlyNeededResources() }} to complete currently running tests.
                            <p>You've created more tests that you can run with available reach. To avoid them stopping abruptly consider <a href="{{ route('pricing') }}">securing more resources</a> or reduce the reach of your tests to avoid any hiccups.</p>
                        </div>
                    @endif


                    @yield('content')

                </div>
            </div>
        </div>
    </div>
    <!-- CONTENT-WRAPPER SECTION END-->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    &copy; 2015 A/B Testing Lab
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
