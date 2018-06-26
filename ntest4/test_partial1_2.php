<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../push/function.php';

function log_var($tmp_log, $mode = -1) {
    $fname = './log/user_xx';

    if ($mode == 0) {
        if (is_string($tmp_log)) {
            echo $tmp_log;
        } else if (is_array($tmp_log)) {
            print_r($tmp_log);
        } else {
            var_dump($tmp_log);
        }
        $myfile = file_put_contents($fname, $tmp_log . PHP_EOL, FILE_APPEND | LOCK_EX);
    } else if ($mode == 1) {
        $myfile = file_put_contents($fname, $tmp_log . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}

$path = "../data/user2/";
//$url_cron = $row_code['code_url_cron'];
$url_cron = 'https://kindred.com.au/old/wp-content/plugins/kindredrest-plugin';
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


$skip = 0;
$isready = false;

$altapi = $url_cron . '/cron-job.php';
$tmp_log = $altapi . "\n";
log_var($tmp_log);
//        $altapi = $url_cron . '/cron-job.php';
$tmp_log = get_call_api($apiKey, $pageID, $baseurl, $access_token, $iteration, $altapi);
log_var($tmp_log);
//
foreach ($files as $file) {
    // $file name
    $filename = basename($file);
    $altapi = $url_cron . '/cron-job2.php?file=' . $filename;
    $tmp_log = $altapi . "\n";
    log_var($tmp_log,0);
    $tmp_log = get_call_api($apiKey, $pageID, $baseurl, $access_token, $iteration, $altapi);
    log_var($tmp_log);
}
$altapi = $url_cron . '/cron-job3.php';
$tmp_log = get_call_api($apiKey, $pageID, $baseurl, $access_token, $iteration, $altapi);
log_var($tmp_log);
$tmp_log = $altapi . "\n";
log_var($tmp_log);

$tmp_log = "\r\n\r\n--------------------\r\nEnd execution at " . date("h:i:s d-m-Y", time()) . "\r\n--------------------\n";
log_var($tmp_log);
