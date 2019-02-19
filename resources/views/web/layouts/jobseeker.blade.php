<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="author" content="">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="shortcut icon" href="{{asset('web/images/favicon.png')}}" type="image/x-png"/>
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
  <link rel="stylesheet" href="{{asset('web/plugins/font-awesome-4.6.2/css/font-awesome.min.css')}}">
  <link rel="stylesheet" href="{{asset('web/css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{asset('web/css/bootstrap-select.css')}}">
  <link rel="stylesheet" href="{{asset('web/css/bootstrap-custom-theme.css')}}">

  <link rel="stylesheet" href="{{asset('web/plugins/parsley/css/parsley.css')}}">
  <link rel="stylesheet" href="{{asset('web/css/dentaIcon.css')}}">
  <link rel="stylesheet" href="{{asset('web/css/dashboard.css')}}">
  <link rel="stylesheet" href="{{asset('web/css/bootstrap-multiselect.css')}}">
  <link rel="stylesheet" href="{{asset('web/css/checkBox.css')}}">
  <link rel="stylesheet" href="{{asset('web/css/dentaIcon.css')}}">
  <link rel="stylesheet" href="{{asset('web/css/bootstrap-datetimepicker.css')}}">
  <link rel="stylesheet" href="{{asset('web/plugins/calender/css/fullcalendar.css')}}">
  <link rel="stylesheet" href="{{asset('web/plugins/custom-scroller/css/mCustomScrollbar.min.css')}}">
  <link rel="stylesheet" href="{{asset('web/css/custom.css')}}">
  <link rel="stylesheet" href="{{asset('web/css/style.css')}}">
  <link rel="stylesheet" href="{{asset('web/css/editor.css')}}">
  <link rel="stylesheet" href="{{asset('web/plugins/rating/tipi.css')}}">
  <script src="{{asset('web/scripts/jQuery-2.2.0.min.js')}}"></script>
  <script src="{{asset('web/plugins/rating/tipi.jquery.js')}}"></script>
  <script src="{{asset('web/plugins/rating/rating.jquery.js')}}"></script>
  <script src="{{asset('web/scripts/editor.js')}}"></script>
  @yield('css')

  <title>DentaMatch| Home</title>
</head>

<body>
<nav class="customNav navbar navbar-default navbar-fixed-top">

  <div class="container pos-rel">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header ">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
              data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" style="padding-top:5px; padding-bottom:5px;" href="#"><img
                src="{{asset('web/images/dentaMatchLogo.png')}}" alt=""></a>
    </div>
  </div><!-- /.container-fluid -->
</nav>
@yield('content')

<!-- Bootstrap 3.3.6 -->
<script src="{{asset('web/scripts/bootstrap.min.js')}}"></script>
<script src="{{asset('web/scripts/bootstrap-select.js')}}"></script>

<script src="{{asset('web/plugins/parsley/js/parsley.js')}}"></script>
<script src="{{asset('web/scripts/moment.min.js')}}"></script>
<script src="{{asset('web/scripts/bootstrap-datetimepicker.js')}}"></script>


@yield('js')

</body>

</html>
