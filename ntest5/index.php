<?php
include_once 'constant.php';
?>  
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Pay with BrainTree</title>

        <!-- jquery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

        <!-- braintree -->
        <script src="https://js.braintreegateway.com/js/braintree-2.32.1.min.js"></script>

        <!-- setting up braintree -->
        <script>
            $.ajax({
                url: "token.php",
                type: "get",
                dataType: "json",
                success: function (data) {
                    braintree.setup(data, 'dropin', {container: 'dropin-container'});
                }
            })
        </script>



        <style>
            label.heading {
                font-weight: 600;
            }
            .payment-form {
                width: 400px;
                margin-left: auto;
                margin-right: auto;
                padding: 10px;
                border: 1px #333 solid;
            }
            input.content {
                width:300px;
            }
        </style>

    </head>
    <body style="text-align: center; margin-top: 100px;">
        <form action="payment.php" method="post" class="payment-form" id='frm_payment' onsubmit="return validateForm()">
            <label for="domainName" class="heading">Domain Name</label><br>
            <input class="content" type="text" name="domainName" id="domainName"><br><br>

            <label for="firstName" class="heading">First Name</label><br>
            <input class="content" type="text" name="firstName" id="firstName"><br><br>

            <label for="lastName" class="heading">Last Name</label><br>
            <input class="content" type="text" name="lastName" id="lastName"><br><br>

            <label for="amount" class="heading">Amount</label><br>
            <label ><?php echo $amount; ?> (USD)</label><br><br>

            <div id="dropin-container"></div>
            <br><br>
            <!--<button type="button" id="btn_pay">Pay with BrainTree</button>-->
            <button type="submit">Pay with BrainTree</button>

        </form>
        <script>
            function validateForm() {
                var domain = $('#domainName').val();
                if (domain != undefined && domain.length > 0) {
                    if (validate(domain)) {
//                        alert('it\'s valid');
                        var res = domain.substring(0, 5);
                        if (res == 'https') {
                            return true;
                        } else {
                            alert('Your domain should be https');
                            return false;
                        }
                    } else {
                        alert('Invalid Domain');
                        return false;
                    }
                } else {
                    alert('Input Domain');
                    return false;
                }
            }
            function validate(url) {
                var pattern = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
                if (pattern.test(url)) {
                    return true;
                }
                return false;

            }
            $("#btn_pay").click(function (e) {
                var domain = $('#domainName').val();
                if (domain != undefined && domain.length > 0) {
                    if (validate(domain)) {
//                        alert('it\'s valid');
                        var res = domain.substring(0, 5);
                        if (res == 'https') {
                            $("#frm_payment").submit();
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