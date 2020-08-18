<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <link rel="shortcut icon" href="{{asset('web/images/favicon.png')}}" type="image/x-png"/>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet">


  {{--<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">--}}
  <link rel="stylesheet" href="{{asset('web/plugins/font-awesome-4.6.2/css/font-awesome.min.css')}}">
  <link rel="stylesheet" href="{{asset('web/css/bootstrap-select.css')}}">
  <link rel="stylesheet" href="{{asset('web/css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{asset('web/css/bootstrap-custom-theme.css')}}">

  <link rel="stylesheet" href="{{asset('web/plugins/parsley/css/parsley.css')}}">
  <link rel="stylesheet" href="{{asset('web/css/custom.css?v=1.1')}}">

  <link rel="stylesheet" href="{{asset('/css/landing.css?v=3')}}">

  <title>DentaMatch</title>

  @yield('css')

  {{--<title>DentaMatch| Home</title>--}}
</head>
<body class="page-layout">

<header class="d-page-header">
  <a href="/">
    <img src="/assets/img/logo/group.png"
         srcset="/assets/img/logo/group@2x.png 2x,
             /assets/img/logo/group@3x.png 3x"
         class="main-logo" width="162" height="30" alt="DentaMatch logo">
    <img src="/assets/img/logo/2@3x.png" class="main-logo--img-only" width="56" height="60" alt="DentaMatch logo">
  </a>
</header>

@yield('content')

<footer class="main-footer">
  <div class="d-container">
    <ul class="social-links">
      <li class="social-link social-link--facebook"><a href="https://www.facebook.com/pg/dentalpositions/posts/" target="_blank">Facebook</a></li>
    </ul>

    <p class="copyright">
      © DentaMatch <?= date('Y') ?>
    </p>
  </div>
</footer>


<script src="{{asset('web/scripts/jQuery-2.2.0.min.js')}}"></script>

<!-- Bootstrap 3.3.6 -->
<script src="{{asset('web/scripts/bootstrap.min.js')}}"></script>
<script src="{{asset('web/plugins/parsley/js/parsley.js')}}"></script>

{{--<script src="{{asset('web/scripts/main.js')}}"></script>--}}
<script src="{{asset('web/scripts/bootstrap-select.js')}}"></script>
{{--<script src="{{asset('web/scripts/bootstrap-datepicker.js')}}"></script>--}}

@yield('js')

@include('landing._promo')

</body>
</html>
