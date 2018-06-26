<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$path = "../data/user2/";
//$url_cron = $row_code['code_url_cron'];
$url_cron = 'http://localhost:88/wordpress/wp-content/plugins/kindredrest-plugin';
$files = glob($path . 'kindred/*.{txt}', GLOB_BRACE);


$altapi = $url_cron . '/cron-job.php';
echo $altapi . "\r\n";
$altapi = $url_cron . '/cron-job.php';
//get_prop($apiKey, $pageID, $baseurl, $access_token, $iteration, $altapi);


$tmp_array = array();
foreach ($files as $file) {
    $filename = basename($file);
    if (substr($filename, 0, strlen("data")) == "data" && substr($filename, strlen($filename) - 4) == ".txt") {
        $number = substr($filename, strlen("data"));
        $number = substr($number, 0, strlen($number) - 4);
//        echo "-------------------" . $number . "--------------------";
        if (is_numeric($number)) {
            $number = intval($number);
            $tmp_array[$number] = $file;
        }
    }
}

ksort($tmp_array);
$files = $tmp_array;


$skip = 135;
$isready = false;

foreach ($files as $key=>$file) {
    // $file name
    $filename = basename($file);
    if($key>=$skip){
        $isready = true;
    }
    
    if(!$isready){
        continue;
    }
    
    
    echo "-------------------" . $key . "--------------------";
//    echo "-------------------".substr($filename, strlen($filename)-4)."--------------------";

    $altapi = $url_cron . '/cron-job2.php?file=' . $filename;
//    get_prop($apiKey, $pageID, $baseurl, $access_token, $iteration, $altapi);
//    echo $altapi."\r\n";
}
$altapi = $url_cron . '/cron-job3.php';
//get_prop($apiKey, $pageID, $baseurl, $access_token, $iteration, $altapi);
echo $altapi . "\r\n";
