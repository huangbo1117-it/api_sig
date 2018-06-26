<?php

// var_dump( dirname( __FILE__,2 ));
// var_dump( dirname( __FILE__,1 ) . '/vendor/autoload.php' );
// var_dump(file_exists( dirname( __FILE__,1 ) . '/vendor/autoload.php' ));

require_once '../functions.php';
require_once 'cm.php';

$key = 'letmein';
$raw = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.';
$meta = ['name' => 'Rich', 'email' => 'rich@richjenks.com'];



$key_data = retrivekey();
$encrypted = array();
$encrypted['key'] = encrypt($key, $key_data['key'], $meta);
$encrypted['secret'] = encrypt($key, $key_data['secret'], $meta);

$fp = fopen('./key_encrypted1', 'w');
fwrite($fp, serialize($encrypted));
fclose($fp);

var_dump($key_data);
var_dump($encrypted);
?>
