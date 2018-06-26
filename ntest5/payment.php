<?php
require "boot.php";
include_once 'constant.php';

if (empty($_POST['payment_method_nonce'])) {
//    header('location: index.php');
    var_dump($_POST);
    die();
}
$code_code = 'Fail to Get';
$result = Braintree_Transaction::sale([
            'amount' => $amount,
            'paymentMethodNonce' => $_POST['payment_method_nonce'],
            'customer' => [
                'firstName' => $_POST['firstName'],
                'lastName' => $_POST['lastName'],
            ],
            'options' => [
                'submitForSettlement' => true
            ]
        ]);

if ($result->success === true) {
    // payment successed
    if (empty($_POST['domainName'])) {
        echo "No Domain Name";
    } else {
        $host = $_POST['domainName'];
        $response = curl_get_key(array(
            'host' => $host,
            'url_cron' => $host."/wp-content/plugins/realestate-connector-mydesktop"
        ));
        $row = $response['activation_code'];
        $code_code = $row['code_code'];
        $code_id = $row['code_id'];
        $code_host = $row['code_host'];
        $code_url_cron = $row['code_url_cron'];
    }
} else {
    print_r($result->errors);
    die();
}

//Now, i think all done. Let's test it out.
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Payment Report</title>
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
            pre.content {
                white-space:pre-wrap; 
                word-wrap:break-word;
            }
        </style>
    </head>
    <body style="text-align: center; margin-top: 100px;">
        <form class="payment-form">
            <label for="ID" class="heading">Transaction ID</label><br>
            <input class="content" type="text" disabled="disabled" name="ID" id="ID" value="<?php echo $result->transaction->id; ?>"><br><br>

            <label for="firstName" class="heading">First Name</label><br>
            <input class="content" type="text" disabled="disabled" name="firstName" id="firstName" value="<?php echo $result->transaction->customer['firstName']; ?>"><br><br>

            <label for="lastName" class="heading">Last Name</label><br>
            <input class="content" type="text" disabled="disabled" name="lastName" id="lastName" value="<?php echo $result->transaction->customer['lastName']; ?>"><br><br>

            <label for="amount" class="heading">Amount (USD)</label><br>
            <input class="content" type="text" disabled="disabled" name="amount" id="amount" value="<?php echo $result->transaction->amount . " " . $result->transaction->currencyIsoCode; ?>"><br><br>

            <label for="status" class="heading">Status</label><br>
            <input class="content" type="text" disabled="disabled" name="status" id="status" value="Successful"><br><br>

            <label for="amount" class="heading">Activation Code</label><br>
            <pre class="content"><?php echo $code_code; ?></pre>

            <br><br>


        </form>

    </body>
</html>
