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
    <link href="{{ asset('bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset('styles/jquery.dataTables.css')}}" >
<link rel="stylesheet" type="text/css" href="{{ asset('styles/dataTables.responsive.css')}}" >
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css">
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
        
        table.dataTable.dtr-inline.collapsed > tbody > tr > td:first-child, 
        table.dataTable.dtr-inline.collapsed > tbody > tr > th:first-child{white-space: normal;}
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
                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('cms/login') }}">Login</a></li>
                    @else
                        @if (Auth::user()->userGroup->group_id==1)
                        
                            <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    Menu <span class="caret"></span>
                                </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('cms/location/index') }}">Location</a></li>
                                <li><a href="{{ url('cms/affiliation/index') }}">Affiliations</a></li>
                                <li><a href="{{ url('cms/jobtitle/index') }}">Job Title</a></li>
                                <li><a href="{{ url('cms/officetype/index') }}">Office Type</a></li>
                                <li><a href="{{ url('cms/certificate/index') }}">Certification</a></li>
<!--                                <li><a href="{{ url('cms/config/create-radius') }}">Search Radius</a></li>-->
                                <li><a href="{{ url('cms/skill/index') }}">Skills</a></li>
                                <li><a href="{{ url('cms/school/index') }}">Schooling</a></li>
                                <li><a href="{{ url('cms/jobseeker/index') }}">Job Seeker</a></li>
                                <li><a href="{{ url('cms/jobseeker/verification') }}">Verification Status</a></li>
                                <li><a href="{{ url('cms/recruiter/index') }}">Recruiter</a></li>
                                <li><a href="{{ url('cms/notify/index') }}">Notify Users</a></li>
                                <li><a href="{{ url('cms/config/pay-rate') }}">Pay-rate</a></li>
                               
<!--                                <li><a href="{{ url('cms/report/search-location') }}">Search Location</a></li>-->
                            </ul>
                        </li>
                        
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    Job Seeker Report<span class="caret"></span>
                                </a>

                            <ul class="dropdown-menu" role="jobseeker">
                                <li><a href="{{ url('cms/jobseeker/unverified') }}">Unverified Profiles</a></li>
                                <li><a href="{{ url('cms/jobseeker/incomplete') }}">Incomplete Profiles</a></li>
                                <li><a href="{{ url('cms/jobseeker/nonavailableusers') }}">Non Available Users Profiles</a></li>
                                <li><a href="{{ url('cms/jobseeker/invited') }}">Invited Candidate Profiles</a></li>
                                 <li><a href="{{ url('cms/report/index') }}">Job Lists</a></li>
                                <li><a href="{{ url('cms/report/cancellist') }}">Cancel Lists</a></li>
                                <li><a href="{{ url('cms/report/responselist') }}">Response Rate</a></li>
                            </ul>
                        </li>
                        
                        @endif
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->email }} <span class="caret"></span>
                                </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{url('cms/user/changePassword')}}"><i class="fa fa-btn"></i>Change Password</a></li>
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
    <script type="text/javascript" src="{{asset('web/scripts/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('script/datepicker/js/bootstrap-datepicker_new.js')}}"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('script/datepicker/css/bootstrap-datepicker_new.css')}}" >
<script type="text/javascript" src="{{ asset('script/common.js')}}" ></script>
    @yield('js')
    @yield('innerViewJs')
</body>
</html>