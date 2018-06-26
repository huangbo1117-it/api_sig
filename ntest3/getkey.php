<?php

// var_dump( dirname( __FILE__,2 ));
// var_dump( dirname( __FILE__,1 ) . '/vendor/autoload.php' );
// var_dump(file_exists( dirname( __FILE__,1 ) . '/vendor/autoload.php' ));

require_once dirname(__FILE__, 2) . '/functions.php';

$key = 'letmein';
$raw = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.';
$meta = ['name' => 'Rich', 'email' => 'rich@richjenks.com'];
$encrypted = encrypt($key, $raw, $meta);
?>
