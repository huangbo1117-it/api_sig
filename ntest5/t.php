<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once 'constant.php';

$host = 'http://localhost:88/wordpress';
$url_cron = $host.'/wp-content/plugins/kindredrest-plugin';
$response = curl_get_key(array(
    'host' => $host,
    'url_cron' => $host
        ));
if (is_array($response)){
    if(isset($response['activation_code'])){
        $row = $response['activation_code'];
        $code_code = $row['code_code'];
        $code_id = $row['code_id'];
        $code_host = $row['code_host'];
        $code_url_cron = $row['code_url_cron'];
        var_dump($row);
        
    }
}