<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="author" content="">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link href="//fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet">
  <link rel="shortcut icon" href="img/favicon.ico" sizes="16x16" type="image/x-icon">
  <link href="{{ asset('bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">


  <title>DentaMatch</title>
  <style>
    body {
      padding: 0 10px;
    }

    p {
      color: #686868;
      margin-top: 30px
    }
    h1 {
      margin-top: 50px;
      margin-bottom: 30px
    }

    h4 {
      margin-top: 35px;
      margin-bottom: 15px;
      font-weight: 500;
    }
  </style>

</head>

<body>
<div class="container doc-cont terms">
  <h1 class="pg-heading mr-t-30 mr-b-30">Terms and Conditions</h1>
  @include('shared.terms-and-conditions')
</div>
</body>
</html>

