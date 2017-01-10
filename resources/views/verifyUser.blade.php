
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
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

   
        <title>DentaMatch</title>
        <style>
            body{background-color:rgb(240,240,240);padding:0 10px;}
            p{color:rgb(62,57,53);}
        </style>

    </head>

    <body>
        <div class="container doc-cont terms">
            <h3 class="pg-heading mr-t-30 mr-b-30">User Verification</h3>
            @if ($verifyUser == 1)
            <p style=""><h4>Account verified successfully</h4></p>
            @else
            <p style=""><h4>Invalid token</h4></p>
            @endif

            


        </div>
    </body>
</html>

