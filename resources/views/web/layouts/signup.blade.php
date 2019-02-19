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
  <link rel="stylesheet" href="{{asset('web/css/bootstrap-custom-theme.css')}}">

  <link rel="stylesheet" href="{{asset('web/plugins/parsley/css/parsley.css')}}">
  <link rel="stylesheet" href="{{asset('web/css/custom.css')}}">

  @yield('css')

  <title>DentaMatch| Home</title>
</head>
<body class="denta-access-bg pos-rel">

@yield('content')

<script src="{{asset('web/scripts/jQuery-2.2.0.min.js')}}"></script>

<!-- Bootstrap 3.3.6 -->
<script src="{{asset('web/scripts/bootstrap.min.js')}}"></script>
<script src="{{asset('web/plugins/parsley/js/parsley.js')}}"></script>
<script src="{{asset('web/scripts/main.js')}}"></script>
<script src="{{asset('web/scripts/bootstrap-select.js')}}"></script>
<script src="{{asset('web/scripts/bootstrap-datepicker.js')}}"></script>

@yield('js')

</body>
</html>