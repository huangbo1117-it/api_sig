<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function log_var($tmp_log, $mode = 0) {
    $filename = './log/cron_log1';
    if ($mode == 0) {
        if (is_string($tmp_log)) {
            echo $tmp_log;
        } else if (is_array($tmp_log)) {
            print_r($tmp_log);
        } else {
            var_dump($tmp_log);
        }
        $myfile = file_put_contents($filename, $tmp_log . PHP_EOL, FILE_APPEND | LOCK_EX);
    } else if ($mode == 1) {
        $myfile = file_put_contents($filename, $tmp_log . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}

$tmp_log = "Date: " . date("D M d, Y G:i:s");
log_var($tmp_log);

log_var(json_encode($_REQUEST));
