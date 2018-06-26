<?php

if ( file_exists( dirname( __FILE__,2 ) . '/vendor/autoload.php' ) ) {
	require_once dirname( __FILE__,2 ) . '/vendor/autoload.php';
}

use PhilipBrown\Signature\Token;
use PhilipBrown\Signature\Request;

$data    = ['name' => 'Philip Brown2'];

$file = file_get_contents("../key/key1");
$dater = unserialize($file);
$key = $dater['key'];
$secret = $dater['secret'];

$token   = new Token($key, $secret);
$request = new Request('POST', 'users', $data);

$auth = $request->sign($token);

//


$query_data = array_merge($auth, $data);
var_dump($query_data);

$fields_string = http_build_query($query_data);

$url = "http://localhost:88/api_sig/ntest/test2_get.php?".$fields_string;

$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//execute post
$result = curl_exec($ch);
// echo $result;

//close connection
curl_close($ch);

// var_dump($fields_string);
var_dump($result);
echo $url;
 ?>
