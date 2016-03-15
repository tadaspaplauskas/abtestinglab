<?php
$loggedIn = Auth::check();

if ($loggedIn)
{
    $user = Auth::user();
}

?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript" src="https://cdn.abtestinglab.com/c4ca4238a0b923820dcc509a6f75849b/db576a7d2453575f29eab4bac787b919/tests.js"></script><script type="text/javascript">var s=document.createElement("style");s.type="text/css";s.appendChild(document.createTextNode("body{visibility:hidden;}"));document.getElementsByTagName("head")[0].appendChild(s);var t=setInterval(function(){if(document.body!=null){document.body.style.visibility="visible";clearInterval(t);}},200);</script>

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
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="/assets/img/abtl_logo.png" id="header-logo" />
                </a>
            </div>
            <div class="collapse navbar-collapse" id="navbar">
                <ul id="menu-top" class="nav navbar-nav navbar-right">
                @if($loggedIn)
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('websites.index') }}">Websites</a></li>
                    <li><a href="{{ route('account') }}">Account</a></li>
                    <li><a href="{{ route('pricing') }}">Buy</a></li>
                @else
                    <li><a href="{{ route('pricing') }}">Pricing</a></li>
                @endif
                    <li><a href="{{ route('faq') }}">FAQ</a></li>
                @if($loggedIn)
                    <li><a href="{{ route('logout') }}">Log out</a></li>
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
            @if (!isset($noDefaultHeadline))
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="page-head-line">@yield('title')</h4>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    @if(Session::has('success'))
                        <div class="alert alert-success">
                            <strong>Success!</strong> {!! Session::get('success') !!}
                        </div>
                    @endif
                    @if(Session::has('fail'))
                        <div class="alert alert-danger">
                            <strong>Oops...</strong> {!! Session::get('fail') !!}
                        </div>
                    @endif
                    @if(Session::has('warning'))
                        <div class="alert alert-warning">
                            <strong>Warning</strong> {!! Session::get('warning') !!}
                        </div>
                    @endif

                    @if(!Session::has('warning') && $loggedIn && $user->getAvailableResources() === 0)
                        <div class="alert alert-warning">
                            <strong>Uh-oh</strong> You can no longer run any tests because you ran out of resources. Please consider <a href="{{ route('pricing') }}">purchasing more</a> to resume testing.
                        </div>

                    @elseif($loggedIn && $user->lowResources())
                        <div class="alert alert-info">
                            <strong>Just so you know</strong> Currently you have {{ $user->getAvailableResources() }} visitors left but you need {{ $user->getCurrentlyNeededResources() }} to complete the tests.
                            <p>You've created more tests than you can run with the available reach. To avoid them stopping abruptly consider <a href="{{ route('pricing') }}">securing more resources</a> or reducing the reach of your tests.</p>
                        </div>
                    @endif

                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
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
                    &copy; 2016 A/B Testing Lab.
                    <a href=" {{ URL::route('about') }}">About</a> |
                    <a href=" {{ URL::route('contact') }}">Contact</a> |
                    <a href="{{ URL::route('privacy') }}">Privacy Policy</a>
                </div>
            </div>
        </div>
    </footer>
@if(app()->environment('production'))
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-73205213-1', 'auto');
        ga('send', 'pageview');
    </script>
@endif
</body>
</html>
