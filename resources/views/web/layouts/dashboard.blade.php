<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="author" content="">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="shortcut icon" href="{{asset('web/images/favicon.png')}}" type="image/x-png" />
        <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
        <link rel="stylesheet" href="{{asset('web/plugins/font-awesome-4.6.2/css/font-awesome.min.css')}}">
        <link rel="stylesheet" href="{{asset('web/css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('web/css/bootstrap-custom-theme.css')}}">

        <link rel="stylesheet" href="{{asset('web/plugins/parsley/css/parsley.css')}}">
        <link rel="stylesheet" href="{{asset('web/css/custom.css')}}">
        <link rel="stylesheet" href="{{asset('web/css/dashboard.css')}}">
        <link rel="stylesheet" href="{{asset('web/css/bootstrap-multiselect.css')}}">
        <link rel="stylesheet" href="{{asset('web/css/checkBox.css')}}">
        <link rel="stylesheet" href="{{asset('web/css/dentaIcon.css')}}">

        @yield('css')

        <title>DentaMatch| Home</title>
    </head>
    <body>
        <nav class="customNav navbar navbar-default navbar-fixed-top">
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#"><img src="{{asset('web/images/dentaMatchLogo.png')}}" alt=""></a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->


                <ul class=" topIconBox navbar-right customnavRight">
                    <li><a href="#"><span class="icon icon-message"></span></a></li>
                    <li class="notificaionbell"><a href="#"><span class="icon icon-bell "></span><div class="notificationCircle ">2</div></a>
                        <div class="noficationListContainer small-border-radius box-shadow">
                            <span class="fa fa-caret-up notificationCaret"></span>
                            <ul class="notificationList">
                                <li><p><b>Horward Patterson</b> has applied for the <b>Dental Hygienists</b></p>
                                    <span>Just now</span>
                                    <i class="icon icon-deleteicon notificationdelIcon"></i>
                                </li>
                                <li><p><b>Horward Patterson</b> has applied for the <b>Dental Hygienists</b></p>
                                    <span>Just now</span>
                                    <i class="icon icon-deleteicon notificationdelIcon"></i>
                                </li>
                                <li><p><b>Horward Patterson</b> has applied for the <b>Dental Hygienists</b></p>
                                    <span>Just now</span>
                                    <i class="icon icon-deleteicon notificationdelIcon"></i>
                                </li>

                            </ul>
                            <a href="#" class="notificationSeeAll text-center">See All</a>

                        </div>

                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Smiley Care <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Action</a></li>
                            <li><a href="#">Another action</a></li>
                            <li><a href="#">Something else here</a></li>

                        </ul>
                    </li>
                </ul>

                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="#">Calendar</a></li>
                        <li><a href="#">Job Listing</a></li>
                        <li><a href="#">Favorite Jobseeker</a></li>
                        <li><a href="#">Template</a></li>

                    </ul>





                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
        @yield('content')

        <script src="{{asset('web/scripts/jQuery-2.2.0.min.js')}}"></script>

        <!-- Bootstrap 3.3.6 -->
        <script src="{{asset('web/scripts/bootstrap.min.js')}}"></script>
        <script src="{{asset('web/scripts/bootstrap-multiselect.js')}}"></script>
        <script src="{{asset('web/plugins/parsley/js/parsley.js')}}"></script>
        <script src ="{{asset('web/scripts/main.js')}}"></script>

        @yield('js')

    </body>
</html>