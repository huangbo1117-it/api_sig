<?php

// var_dump( dirname( __FILE__,2 ));
// var_dump( dirname( __FILE__,1 ) . '/vendor/autoload.php' );
// var_dump(file_exists( dirname( __FILE__,1 ) . '/vendor/autoload.php' ));

require_once '../functions.php';
require_once 'cm.php';

$key = 'letmein';
$raw = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.';
$meta = ['name' => 'Rich', 'email' => 'rich@richjenks.com'];


$file = file_get_contents("./key_encrypted1");
$dater = unserialize($file);
var_dump($dater);

$decrypted = array();
$decrypted['key'] = decrypt($key, $dater['key'], $meta);
$decrypted['secret'] = decrypt($key, $dater['secret'], $meta);

var_dump($decrypted);
?>
