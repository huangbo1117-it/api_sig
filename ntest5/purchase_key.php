<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>Bootstrap 101 Template</title>

        <!-- Bootstrap -->
        <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <h1>Hello, world!</h1>

        <h3>Domain Name</h3>
        <!--<input type='text' placeholder="eg. https://kindred.com.au" id='text_domain' value='https://localhost:88'/>-->
        <input type='text' placeholder="eg. https://kindred.com.au" id='text_domain' />
        <input type='button' id='btn_get_code' value='Get Code' ></input>
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="../bootstrap/js/bootstrap.min.js"></script>
        <script src="https://js.braintreegateway.com/js/braintree-2.32.1.min.js"></script>

        <script>
            function validate(url) {
                var pattern = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
                if (pattern.test(url)) {
                    return true;
                }
                return false;

            }

            $("#btn_get_code").click(function (e) {
                var domain = $('#text_domain').val();
                if (domain != undefined && domain.length > 0) {
                    if (validate(domain)) {
//                        alert('it\'s valid');
                        var res = domain.substring(0, 5);
                        if (res == 'https') {
                            
//                            $.ajax({
//                                type: "POST",
//                                url: 'get_key_1.php',
//                                data: {
//                                    host: domain,
//                                    url_cron: domain,
//                                    action: 'get_one'
//                                },
//                                dataType: 'json',
//                                success: function (data) {
//                                    console.log(data);
//                                },
//                                error: function (e) {
//                                    console.log(e);
//                                }
//                            });
                        } else {
                            alert('Your domain should be https');
                        }



                    } else {
                        alert('Invalid Domain');
                    }
                } else {
                    alert('Input Domain');
                }
            });
        </script>


    </body>
</html>
<?php

?>