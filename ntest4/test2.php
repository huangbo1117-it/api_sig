<?php

if (file_exists(dirname(__FILE__, 2) . '/vendor/autoload.php')) {
    require_once dirname(__FILE__, 2) . '/vendor/autoload.php';
}

use PhilipBrown\Signature\Auth;
use PhilipBrown\Signature\Token;
use PhilipBrown\Signature\Guards\CheckKey;
use PhilipBrown\Signature\Guards\CheckVersion;
use PhilipBrown\Signature\Guards\CheckTimestamp;
use PhilipBrown\Signature\Guards\CheckSignature;
use PhilipBrown\Signature\Exceptions\SignatureException;

$ret = array('response' => 200, 'msg' => 'success');
if (isset($_REQUEST) && isset($_REQUEST['auth_data'])) {
    $auth_data = $_REQUEST['auth_data'];
}

$auth = new Auth('POST', 'users', $auth_data, [
    // new CheckKey,
    new CheckVersion,
    new CheckTimestamp,
    new CheckSignature
        ]);

$file = file_get_contents("../key/key1");
$dater = unserialize($file);
$key = $dater['key'];
$secret = $dater['secret'];
// $secret = 'e69a16c509b0d3ffeb48abdf0db2c06f1dbbc13f641e3a1477bcccf51c14f7c';

$token = new Token($key, $secret);

try {
    $auth->attempt($token);
    echo json_encode($ret);
} catch (SignatureException $e) {
    // return 4xx
    $ret = array('response' => 400, 'msg' => 'SignatureException');
    var_dump($e);
    return "fail";
}
?>
