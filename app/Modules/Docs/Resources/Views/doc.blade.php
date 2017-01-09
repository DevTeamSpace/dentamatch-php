<html>
    <head>
        <title>
            API Documentation
        </title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    </head>
    <body>
        <script>
            function syntaxHighlight(json) {
                json = JSON.stringify(json, undefined, 4);
                json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
                    var cls = 'number';
                    if (/^"/.test(match)) {
                        if (/:$/.test(match)) {
                            cls = 'key';
                        } else {
                            cls = 'string';
                        }
                    } else if (/true|false/.test(match)) {
                        cls = 'boolean';
                    } else if (/null/.test(match)) {
                        cls = 'null';
                    }
                    return '<span class="' + cls + '">' + match + '</span>';
                });
            }
        </script>

        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">PAYIX (API Documentation)</a>
                </div>
                <div id="navbar" class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="{{asset('/docs')}}">Home</a></li>
                        <li><a href="#about">About</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div><!--/.nav-collapse -->
            </div>
        </nav>

        <div class="container">
            <br>
            <br>
            <br>
            <br>
            <div class="panel-group" id="klassAccordion" role="tablist" aria-multiselectable="true">
                
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="klassBlkE">
                            <h4 class="panel-title">
                                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseKlassE" aria-expanded="true" aria-controls="collapseKlassE">
                                    Endpoints and Credentials
                                </a>
                            </h4>
                        </div>
                        <div id="collapseKlassE" class="panel-collapse collapse" role="tabpanel" aria-labelledby="E">
                            <div class="panel-body">
                                <table class="table table-responsive table-striped">
                                    <tr>
                                        <td>Dev Endpoint:</td>
                                        <td>35.161.222.71/payix/payix-php/public/docs</td>
                                    </tr>
                                </table>
                                
                                <div>
                                    <p>Please send "Accept" = "application/json","Content-Type" = "application/json" and "accessToken" = "FqpkLQNftwbexLgZDur3WobDZmIN2L2x2iIE"  in the headers of every request.</p>
                                </div>
                                <div>
                                    <p>For API logs http://35.161.222.71/payix/payix-php/public/api.log  this will show last API request and response and even other details.</p>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="col-md-12">
                <div class="col-md-6"><a href="{{asset('docs/api-doc')}}" style="padding:30px;" class="btn btn-primary btn-block">API</a></div>
                <div class="col-md-6"><a href="{{asset('docs/web-doc')}}" style="padding:30px;" class="btn btn-info btn-block">WEB</a></div>
            </div>
        </div>
        <script>
            $('.json').each(function () {
                var v = $(this).html();
                try {
                    v = JSON.parse(v);
                    $(this).html(syntaxHighlight(v));
                } catch (e) {
                    console.log(e)
                }

            });
        </script>
        <style type="text/css">
            pre {outline: 1px solid #ccc; padding: 5px; margin: 5px; }
            .string { color: green; }
            .number { color: darkorange; }
            .boolean { color: blue; }
            .null { color: magenta;
            </style>

        </div><!-- /.container -->
    </body>
</html>