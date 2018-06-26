<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if (file_exists(dirname(__FILE__, 2) . '/vendor/autoload.php')) {
    require_once dirname(__FILE__, 2) . '/vendor/autoload.php';

//    $gateway = new Braintree_Gateway([
//        'environment' => 'sandbox',
//        'merchantId' => 'dcvrmvh5jhdg5k7w',
//        'publicKey' => 'h8fmgkdcx684wpkp',
//        'privateKey' => 'f3481b952557879b12a27b792a7bd688'
//    ]);
//    Braintree_Configuration::environment('sandbox');
//    Braintree_Configuration::merchantId('dcvrmvh5jhdg5k7w');
//    Braintree_Configuration::publicKey('h8fmgkdcx684wpkp');
//    Braintree_Configuration::privateKey('f3481b952557879b12a27b792a7bd688');

    Braintree_Configuration::environment('sandbox');
    Braintree_Configuration::merchantId('k25hhtz9mrz7vwgs');
    Braintree_Configuration::publicKey('8mk3w368d598zv38');
    Braintree_Configuration::privateKey('5f73bc122619609ba8a2bf5541eb72dc');
}
