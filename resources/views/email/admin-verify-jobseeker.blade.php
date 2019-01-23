<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>DentaMatch - Verify Candidate</title>
        <link href='https://fonts.googleapis.com/css?family=Lato:400,700,300' rel='stylesheet' type='text/css'>
    </head>
    <body  style="margin: 0; padding: 0; outline:0 none; font-family: 'Lato', sans-serif;">
        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; max-width:640px;">
            <tr>
                <td align="left" style="line-height:20px;"><div style="border-left:1px solid #f4f4f4; border-right:1px solid #f4f4f4; border-bottom:1px solid #f4f4f4; padding:30px 24px 19px;">
                        <h3 style="margin:0 0 15px 0">Hello Admin,</h3>
                        <div style="font-size:15px;">
                            <p style="line-height: 24px;">
                                A candidate requires your approval.<br/><br/>
                                Please login to the DentaMatch Admin panel to approve or reject license and state for the candidate below.<br/><br/>
                                Candidate Name: {{ $name }}<br/>
                                Email: {{ $email }}<br/>
                            </p>
                            <p style="margin:20px 0 0 0; padding: 0;">Thank You,</p>
                            <p style="margin: 0">The DentaMatch Team</p>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </body>
</html>
