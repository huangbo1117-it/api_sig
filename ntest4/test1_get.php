<?php

// var_dump( dirname( __FILE__,2 ));
// var_dump( dirname( __FILE__,1 ) . '/vendor/autoload.php' );
// var_dump(file_exists( dirname( __FILE__,1 ) . '/vendor/autoload.php' ));

if (file_exists(dirname(__FILE__, 2) . '/vendor/autoload.php')) {
    require_once dirname(__FILE__, 2) . '/vendor/autoload.php';
}

use PhilipBrown\Signature\Token;
use PhilipBrown\Signature\Request;

$host   = 'http://localhost:88/wordpress';
$secret = 'f2c9b0e3cb563f3b7bf4dfaef875457de289d1896dd00587ee71b0113cbe2351';
$auth_data = ['host' => 'http://localhost:88/wordpress', 'code_id'=>2];


$secret = 'f2c9b0e3cb563f3b7bf4dfaef875457de289d1896dd00587ee71b0113cbe2351';
$auth_data = ['host' => "localhost:88", 'code_id' => '1'];

$key = $host;
$secret = $secret;

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

$url = "http://localhost:88/api_sig/ntest4/test2.php?" . $fields_string;
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
