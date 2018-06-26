<?php

if ( file_exists( dirname( __FILE__,2 ) . '/vendor/autoload.php' ) ) {
	require_once dirname( __FILE__,2 ) . '/vendor/autoload.php';
}

use PhilipBrown\Signature\Token;
use PhilipBrown\Signature\Request;

$data    = ['name' => 'Philip Brown1'];

$file = file_get_contents("../key/key1");
$dater = unserialize($file);
$key = $dater['key'];
$secret = $dater['secret'];

$token   = new Token($key, $secret);
$request = new Request('POST', 'users', $data);

$auth = $request->sign($token);

var_dump($auth);

$sign = $request->signature($data, 'POST', 'users', $secret);
var_dump($sign);
 ?>
