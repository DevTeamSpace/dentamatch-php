<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>DentaMatch CMS</title>

    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>

    <!-- Styles -->

    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}
    <!--<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">-->
    <link href="{{ asset('bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset('styles/jquery.dataTables.css')}}" >
<link rel="stylesheet" type="text/css" href="{{ asset('styles/dataTables.responsive.css')}}" >
@yield('css')
@yield('innerViewCss')
    <style>
        body {
            font-family: 'Lato';
        }
        
        .fa-btn {
            margin-right: 6px;
        }
        
        .navbar-brand {
            padding: 7px 15px;
        }
        .mr-t13{margin-top:13px !important;}
    </style>
</head>
<body id="app-layout">
    <nav class="navbar navbar-default">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#spark-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <!-- Branding Image -->
                <a class="navbar-brand" href="{{url('/cms')}}">
                    <!--<img src="{{ asset('images/denta-logo.png')}}">-->
                    DentaMatch
                </a>
            </div>

            <div class="collapse navbar-collapse" id="spark-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <!--                <ul class="nav navbar-nav">
                    <li><a href="{{url('/cms')}}">Home</a></li>
                </ul>-->

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('cms/login') }}">Login</a></li>
                        <!--li><a href="{{ url('/register') }}">Register</a></li-->
                    @else
                        @if (Auth::user()->userGroup->group_id==1)
                            <li><a href="{{ url('cms/index') }}">Home</a></li>
                            <li><a href="{{ url('cms/affiliation/index') }}">Affiliations</a></li>
<!--                            <li><a href="{{ url('cms/payments/index') }}">Payments</a></li>
                            <li><a href="{{ url('cms/event/index') }}">Manage Events</a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                        Manage <span class="caret"></span>
                                    </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="{{ url('cms/group/index') }}">Terms & Conditions</a></li>
                                    <li><a href="{{ url('cms/user/index') }}">Users</a></li>
                                    <li><a href="{{ url('cms/eventType/index') }}">Event-type</a></li>
                                    <li><a href="{{ url('cms/location/index') }}">Location</a></li>
                                    <li><a href="{{ url('cms/coupon/index') }}">Coupons</a></li>
                                    <li><a href="{{ url('cms/appFeedback/index') }}">App Feedbacks</a></li>
                                    <li><a href="{{ url('cms/rating/index') }}">Reviews & Ratings</a></li>
                                    <li><a href="{{ url('cms/chatMessage/index') }}">Chat Messages</a></li>
                                    <li><a href="{{ url('cms/notify/index') }}">Notify Users</a></li>
                                </ul>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                        Configs <span class="caret"></span>
                                    </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="{{url('cms/config/1/updateConfig')}}"><i class="fa fa-btn"></i>Event Per 15 mins Charges</a></li>
                                    <li><a href="{{url('cms/config/2/updateConfig')}}"><i class="fa fa-btn"></i>Event Extension Charges</a></li>
                                    <li><a href="{{url('cms/config/3/updateConfig')}}"><i class="fa fa-btn"></i>Photo Enhancement Charges</a></li>
                                    li><a href="{{url('cms/config/4/updateConfig')}}"><i class="fa fa-btn"></i>Enhanced Free Photo Count</a></li
                                    <li><a href="{{url('cms/config/4/updateConfig')}}"><i class="fa fa-btn"></i>Consumer Signup Referral Bonus</a></li>
                                    <li><a href="{{url('cms/config/5/updateConfig')}}"><i class="fa fa-btn"></i>Admin Share On Photographer Next Transaction</a></li>
                                    <li><a href="{{url('cms/config/6/updateConfig')}}"><i class="fa fa-btn"></i>Birthday Special Coupon</a></li>
                                    <li><a href="{{url('cms/config/7/updateConfig')}}"><i class="fa fa-btn"></i>Come Back Special Coupon</a></li>
                                </ul>
                            </li>-->
                        @endif
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->email }} <span class="caret"></span>
                                </a>

                            <ul class="dropdown-menu" role="menu">
                                <!--<li><a href="{{url('cms/user/changePassword')}}"><i class="fa fa-btn"></i>Change Password</a></li>-->
                                <li><a href="{{url('cms/logout')}}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <!-- JavaScripts -->
    <script>
        var public_path = '<?php echo URL::to('cms');?>/';
    </script>
    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('script/jquery.dataTables.min.js')}}" ></script>
    <script type="text/javascript" src="{{ asset('script/dataTables.responsive.js')}}"></script>
    <script type="text/javascript" src="{{ asset('script/datepicker/js/bootstrap-datepicker.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('script/datepicker/css/datepicker.css')}}" >
<script type="text/javascript" src="{{ asset('script/common.js')}}" ></script>
    @yield('js')
    @yield('innerViewJs')
</body>
</html>