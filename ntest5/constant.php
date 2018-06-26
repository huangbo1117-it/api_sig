<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$amount = "1";
$g_env = 1;

function curl_get_key($input_param) {
    global $g_env;
    if (isset($input_param['host']) && isset($input_param['url_cron'])) {
        $host = $input_param['host'];
        $url_cron = $input_param['url_cron'];
        $urlApi = '';
        // request to mesmo server
        if ($g_env == 1) {
            $urlApi = "https://mesmo.co/api_sig/ntest5/get_key.php?action=get_one&host=$host&url_cron=$url_cron";
        } else {
            $urlApi = "http://localhost:88/api_sig/ntest5/get_key.php?action=get_one&host=$host&url_cron=$url_cron";
        }
        var_dump($urlApi);

        $ch = curl_init($urlApi);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
//            curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, 'progress');
//            curl_setopt($ch, CURLOPT_NOPROGRESS, false); // needed to make progress function work
//            curl_setopt($ch, CURLOPT_USERPWD, $access_token . ":" . "");
        $result = curl_exec($ch);

        return json_decode($result, true);
    }
    return array();
}
