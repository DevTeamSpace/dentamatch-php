<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1" name="viewport">
  <link href="//fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet">
  <link rel="shortcut icon" href="img/favicon.ico" sizes="16x16" type="image/x-icon">
  <link href="{{ asset('bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">

  <title>DentaMatch Mobile app</title>
  <style>
    h1 {
      margin-top: 50px;
      margin-bottom: 30px;
    }

    .download-buttons {
      display: flex;
      margin: 0 auto;
      margin-top: 50px;
    }

    .download-buttons a:first-child {
      margin-right: 10px;
    }

    .download-buttons img {
      width: 100%;
      opacity: 1;
    }

    .download-buttons img:hover {
      opacity: 0.8;
    }

    @media (max-width: 480px) {
      .download-buttons {
        width: 60%;
        margin-top: 40px;
        flex-direction: column;
      }

      .download-buttons a:first-child {
        margin: 0;
        margin-bottom: 30px;
      }
    }
  </style>

  <script>

    function getMobileOperatingSystem() {
      var userAgent = navigator.userAgent || navigator.vendor || window.opera;
      // Windows Phone must come first because its UA also contains "Android"
      if (/windows phone/i.test(userAgent)) {
        return "Windows Phone";
      }
      if (/android/i.test(userAgent)) {
        return "Android";
      }
      // iOS detection from: http://stackoverflow.com/a/9039885/177710
      if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
        return "iOS";
      }
      return "unknown";
    }

    window.onload = function () {

      window.location = 'dentamatch://app';

      setTimeout(function () {
        var os = getMobileOperatingSystem();
        if (os === 'iOS')
          window.location = 'https://itunes.apple.com/us/app/id1377024736';
        if (os === 'Android')
          window.location = 'https://play.google.com/store/apps/details?id=ru.doubletapp.umn';
      }, 1000);
    };

  </script>

</head>

<body>
<section class="container doc-cont terms">
  <h1 class="pg-heading">DentaMatch mobile app</h1>

  <div class="download-buttons">
    <a href="https://itunes.apple.com/us/app/id1377024736"><img src="{{ asset('web/images/app-store.png') }}" alt=""></a>
    <a href="https://play.google.com/store/apps/details?id=ru.doubletapp.umn"><img src="{{ asset('web/images/google-play.png') }}" alt=""></a>
  </div>

</section>
</body>
</html>