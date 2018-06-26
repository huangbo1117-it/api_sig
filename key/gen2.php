<?php

$file = file_get_contents("./key1");
$dater = unserialize($file);
var_dump($dater);
?>
