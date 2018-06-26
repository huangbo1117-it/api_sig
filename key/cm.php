<?php

function gen_oauth_creds() {
    // Get a whole bunch of random characters from the OS
    // $fp = fopen('./urandom','rb');
    // $entropy = fread($fp, 32);
    // fclose($fp);
    $entropy = random(32);

    // Takes our binary entropy, and concatenates a string which represents the current time to the microsecond
    $entropy .= uniqid(mt_rand(), true);

    // Hash the binary entropy
    $hash = hash('sha512', $entropy);

    // Base62 Encode the hash, resulting in an 86 or 85 character string
    $hash = gmp_strval(gmp_init($hash, 16), 62);

    // Chop and send the first 80 characters back to the client
    return array(
        'consumer_key' => substr($hash, 0, 32),
        'shared_secret' => substr($hash, 32, 48)
    );
}

function random($length, $chars = '') {
    if (!$chars) {
        $chars = implode(range('a', 'f'));
        $chars .= implode(range('0', '9'));
    }
    $shuffled = str_shuffle($chars);
    return substr($shuffled, 0, $length);
}

function serialkey() {
    return random(4) . '-' . random(4) . '-' . random(4) . '-' . random(4);
}

function generatekey() {
    $ran1 = random(32);
    $ran2 = random(32);

    $ripemd160 = hash_hmac('ripemd160', $ran1, $ran2);

    $ret = array();
    $ret['ran1'] = $ran1;
    $ret['ran2'] = $ran2;
    $ret['key'] = $ripemd160;

    $ran3 = random(32);
    $ran4 = random(32);
    $ret['ran3'] = $ran3;
    $ret['ran4'] = $ran4;
    $ret['secret'] = hash_hmac('sha256', $ran3, $ran4);

    $data = array("key" => $ripemd160, "secret" => $ret['secret']);
    $fp = fopen('./key1', 'w');
    fwrite($fp, serialize($data));
    fclose($fp);

    return $data;
}

function retrivekey() {
    $file = file_get_contents("./key1");
    $dater = unserialize($file);
    return $dater;
}
