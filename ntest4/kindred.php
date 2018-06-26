<?php

//*******************************************************//
//
//					Matthew Cox
//				Kindred API on Mesmo
//
//
//
//*******************************************************//
// --- This file is designed to output

$apiKey = 'c99cb5ea6069b5926d0a822715496b8312ee65f3';
$access_token = 'eyJhbGciOiJIUzI1NiJ9.eyJhcGlfa2V5IjoiYzk5Y2I1ZWE2MDY5YjU5MjZkMGE4MjI3MTU0OTZiODMxMmVlNjVmMyIsImFnZW50aWQiOjM0OTI2NiwidHlwZSI6Im9mZmljZSIsImdyb3VwaWQiOjI2MzQzLCJwYXNzd29yZF9tb2RkYXRlIjoiMjAxMy0wNi0wNCAwMDowMDowMCJ9.U4QEP8StaZN5gAF3zI6if7LBs8qRIN8pnyDX_vasqlw';
$a = 1;
$propIDArr = array();
$baseurl = 'https://integrations.mydesktop.com.au/api/v1.2';
$nextLinkData = 1;
$iteration = 1;
$pageID = '&page=';

echo "\r\n\r\n--------------------\r\nStart execution at " . date("h:i:s d-m-Y", time()) . "\r\n--------------------\n";

while (null !== ($nextLinkData)) {
    echo $iteration . " Loading...";
    $data = get_prop($apiKey, $pageID, $baseurl, $access_token, $iteration, $altpi = null);
    echo "Complete";

    if (!empty($data->links->next)) {
        $nextLinkData = $data->links->next;
    } else {
        $nextLinkData = null;
    }
//	echo serialize($data);
    $fp = fopen('/home/mesmo/public_html/kindred/data' . $iteration . '.txt', 'w');
    fwrite($fp, serialize($data));
    fclose($fp);
    echo " --> Written";
    echo "\n";

    $iteration++;
}

echo "\r\n\r\n--------------------\r\n Starting Individual Checks  " . date("h:i:s d-m-Y", time()) . "\r\n--------------------\n";

$files = glob('/home/mesmo/public_html/kindred/*.{txt}', GLOB_BRACE);

if (is_file('/home/mesmo/public_html/kindred2/data.m')) {
    $log1 = fopen('/home/mesmo/public_html/kindred2/orig.log', 'w');
    fwrite($log1, '');
    fclose($log1);

    $fp1 = fopen('/home/mesmo/public_html/kindred2/orig.data.m', 'w');
    fwrite($fp1, '');
    fclose($fp1);
}

foreach ($files as $file) {

    $file = file_get_contents($file);
    $dater = unserialize($file);

    foreach ($dater->properties as $properties) {
        $ch1 = curl_init($baseurl . '/properties/' . $properties->id . '/custom?api_key=' . $apiKey);

        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Accept: application/json'));
        curl_setopt($ch1, CURLOPT_USERPWD, $access_token . ":" . "");
        $result1 = curl_exec($ch1);

        $data1 = json_decode($result1);

        $saleData = $data1->fieldgroups[0]->fields[1]->data;
        $rentalData = $data1->fieldgroups[0]->fields[2]->data;

        if ($rentalData == 1) {
            $datf = $properties->id . ',1,0|';
        } elseif ($saleData == 1) {
            $datf = $properties->id . ',0,1|';
        } else {
            $datf = $properties->id . ',0,0|';
        }

        if ($rentalData == 1) {
            $dink = 'RENTAL';
        } elseif ($saleData == 1) {
            $dink = 'SALE';
        } else {
            $dink = '';
        }

        $dattt = $a . " --> " . $properties->id . " --> " . $properties->displayaddress . " -->" . $dink . "\n";
        echo $dattt;

        $log = fopen('/home/mesmo/public_html/kindred2/orig.log', 'a');
        fwrite($log, $dattt);
        fclose($log);

        $fp = fopen('/home/mesmo/public_html/kindred2/orig.data.m', 'a');
        fwrite($fp, $datf);
        fclose($fp);
        $a++;
    }
}

echo "\r\n\r\n--------------------\r\n Ending Individual Checks  " . date("h:i:s d-m-Y", time()) . "\r\n--------------------\n";
echo "\r\n\r\n--------------------\r\n Exporting to Small File  " . date("h:i:s d-m-Y", time()) . "\r\n--------------------\n";

$filee = file_get_contents('/home/mesmo/public_html/kindred2/orig.data.m');
$array1 = explode('|', $filee);

if (is_file('/home/mesmo/public_html/kindred2/data.m')) {
    $log1 = fopen('/home/mesmo/public_html/kindred2/log', 'w');
    fwrite($log1, '');
    fclose($log1);

    $fp1 = fopen('/home/mesmo/public_html/kindred2/data.m', 'w');
    fwrite($fp1, '');
    fclose($fp1);
}

foreach ($array1 as $propertiess) {
    $properties = explode(',', $propertiess);

    if ($properties[1] == 1 || $properties[2] == 1) {

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

        $log = fopen('/home/mesmo/public_html/kindred2/log', 'a');
        fwrite($log, $dattt);
        fclose($log);

        $fp = fopen('/home/mesmo/public_html/kindred2/data.m', 'a');
        fwrite($fp, $datf);
        fclose($fp);
        $a++;
    }
}
echo "\r\n\r\n--------------------\r\n Export Complete  " . date("h:i:s d-m-Y", time()) . "\r\n--------------------\n";
echo "\r\n\r\n--------------------\r\n Starting External Scripts  " . date("h:i:s d-m-Y", time()) . "\r\n--------------------\n";
$altapi = 'https://kindred.com.au/cron-job.php';
get_prop($apiKey, $pageID, $baseurl, $access_token, $iteration, $altapi);
$altapi = 'https://kindred.com.au/cron-job2.php';
get_prop($apiKey, $pageID, $baseurl, $access_token, $iteration, $altapi);

echo "\r\n\r\n--------------------\r\nEnd execution at " . date("h:i:s d-m-Y", time()) . "\r\n--------------------\n";

function progress($resource, $download_size, $downloaded, $upload_size, $uploaded) {
    if ($download_size > 0) {
        echo '.';
        ob_flush();
        flush();
    }
}

function get_prop($apiKey, $pageID, $baseurl, $access_token, $iteration, $altapi) {
    if (!empty($altapi)) {
        $urlApi = $altapi;
    } else {
        $urlApi = $baseurl . '/properties?api_key=' . $apiKey . $pageID . $iteration;
    }

    $ch = curl_init($urlApi);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
    curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, 'progress');
    curl_setopt($ch, CURLOPT_NOPROGRESS, false); // needed to make progress function work
    curl_setopt($ch, CURLOPT_USERPWD, $access_token . ":" . "");
    $result = curl_exec($ch);

    return json_decode($result);
}

?>