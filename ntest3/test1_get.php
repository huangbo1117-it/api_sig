<?php

// var_dump( dirname( __FILE__,2 ));
// var_dump( dirname( __FILE__,1 ) . '/vendor/autoload.php' );
// var_dump(file_exists( dirname( __FILE__,1 ) . '/vendor/autoload.php' ));

if (file_exists(dirname(__FILE__, 2) . '/vendor/autoload.php')) {
    require_once dirname(__FILE__, 2) . '/vendor/autoload.php';
}

use PhilipBrown\Signature\Token;
use PhilipBrown\Signature\Request;

$auth_data = ['host' => 'www.google.com', 'email' => 'huangbo1117@gmail.com'];

$file = file_get_contents("../key/key1");
$dater = unserialize($file);
$key = $dater['key'];
$secret = $dater['secret'];

$token = new Token($key, $secret);
$request = new Request('POST', 'users', $auth_data);

$auth = $request->sign($token);

var_dump($auth);
unset($auth['auth_key']);

$request_data = array('action' => 'getalllist', 'offset' => '10');
$request_data['auth_data'] = array_merge($auth, $auth_data);
;

var_dump($request_data);

$fields_string = http_build_query($request_data);

$url = "http://localhost:88/api_sig/ntest2/test2.php?" . $fields_string;
var_dump($url);
$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//execute post
$result = curl_exec($ch);
// echo $result;
//close connection
curl_close($ch);

// var_dump($fields_string);
var_dump($result);
?>
