<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$path = "../data/user1/";
//$url_cron = $row_code['code_url_cron'];
$url_cron = 'http://localhost:88/wordpress/wp-content/plugins/kindredrest-plugin';
$files = glob($path . 'kindred/*.{txt}', GLOB_BRACE);

$filee = file_get_contents($path . 'kindred2/orig.data.m');
$array1 = explode('|', $filee);

var_dump($array1);

function log_var($tmp_log, $mode = 0) {
//    $filename = './log/cron_log3';
    if ($mode == 0) {
        if (is_string($tmp_log)) {
            print_r($tmp_log);
        } else if (is_array($tmp_log)) {
            print_r($tmp_log);
        } else {
            var_dump($tmp_log);
        }
//        $myfile = file_put_contents($filename, $tmp_log . PHP_EOL, FILE_APPEND | LOCK_EX);
    } else if ($mode == 1) {
//        $myfile = file_put_contents($filename, $tmp_log . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}

$tmp_log = $path . 'kindred2/data.m';
log_var($tmp_log);
if (is_file($path . 'kindred2/data.m')) {
    echo "sss";

    $tmp_log = $path . 'kindred2/log';
    log_var($tmp_log);
    $log1 = fopen($tmp_log, 'w');
    fwrite($log1, '');
    fclose($log1);

    $tmp_log = $path . 'kindred2/data.m';
    log_var($tmp_log);
    $fp1 = fopen($tmp_log, 'w');
    fwrite($fp1, '');
    fclose($fp1);
}
$cnt = 0;

$apiKey = 		'c99cb5ea6069b5926d0a822715496b8312ee65f3';
$access_token = 'eyJhbGciOiJIUzI1NiJ9.eyJhcGlfa2V5IjoiYzk5Y2I1ZWE2MDY5YjU5MjZkMGE4MjI3MTU0OTZiODMxMmVlNjVmMyIsImFnZW50aWQiOjM0OTI2NiwidHlwZSI6Im9mZmljZSIsImdyb3VwaWQiOjI2MzQzLCJwYXNzd29yZF9tb2RkYXRlIjoiMjAxMy0wNi0wNCAwMDowMDowMCJ9.U4QEP8StaZN5gAF3zI6if7LBs8qRIN8pnyDX_vasqlw';

foreach ($array1 as $propertiess) {
    $properties = explode(',', $propertiess);
    echo $cnt." th----\r\n size=".count($properties)."  -------";
    
    if (count($properties) >= 3) {
        if ($properties[1] == 1 || $properties[2] == 1) {

            echo "HHHHHHHHHHHHHH";
            $tmp_log = $baseurl . '/properties/' . $properties[0] . '/custom?api_key=';
            log_var($tmp_log);
            $ch1 = curl_init($baseurl . '/properties/' . $properties[0] . '/custom?api_key=' . $apiKey);

            curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Accept: application/json'));
            curl_setopt($ch1, CURLOPT_USERPWD, $access_token . ":" . "");
            $result1 = curl_exec($ch1);

            $data1 = json_decode($result1);

            $saleData = $data1->fieldgroups[0]->fields[1]->data;
            $rentalData = $data1->fieldgroups[0]->fields[2]->data;

            if ($rentalData == 1) {
                $datf = $properties[0] . ',1,0|';
            } elseif ($saleData == 1) {
                $datf = $properties[0] . ',0,1|';
            } else {
                //No data
            }

            if ($rentalData == 1) {
                $dink = 'RENTAL';
            } elseif ($saleData == 1) {
                $dink = 'SALE';
            } else {
                $dink = '';
            }

            $dattt = $a . " --> " . $properties[0] . " --> " . $properties->displayaddress . " -->" . $dink . "\n";
            echo $dattt;

            $log = fopen($path . 'kindred2/log', 'a');
            fwrite($log, $dattt);
            fclose($log);

            $fp = fopen($path . 'kindred2/data.m', 'a');
            fwrite($fp, $datf);
            fclose($fp);
            $a++;
            
            if($a>3){
                break;
            }
        }
    }
    $cnt++;
}