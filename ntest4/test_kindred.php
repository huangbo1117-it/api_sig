<?php

date_default_timezone_set("UTC");

if (file_exists(dirname(__FILE__, 2) . '/vendor/autoload.php')) {
    require_once dirname(__FILE__, 2) . '/vendor/autoload.php';
}

use PhilipBrown\Signature\Auth;
use PhilipBrown\Signature\Token;
use PhilipBrown\Signature\Guards\CheckKey;
use PhilipBrown\Signature\Guards\CheckVersion;
use PhilipBrown\Signature\Guards\CheckTimestamp;
use PhilipBrown\Signature\Guards\CheckSignature;
use PhilipBrown\Signature\Exceptions\SignatureException;

$ret = array();
try {

    require_once '../push/push_config.php';
    require_once '../push/function.php';
    require_once '../key/cm.php';


    $obj = new UserTable($g_config);
    $ret = $obj->start();
} catch (Exception $e) {
    $ret['response'] = 600;
    $ret['error'] = $e;
}
outputresult($ret);

class UserTable {

    private $fp = NULL;
    private $host = "localhost";
    private $dbname = "reward";
    private $username = "root";
    private $password = "";
    private $config;
    private $limit_datatxt = -1;
    private $limit_datam = -1;

    function __construct($config) {
        $this->host = $config['host'];
        $this->dbname = $config['dbname'];
        $this->username = $config['username'];
        $this->password = $config['password'];
        // Create a connection to the database.
        $this->pdo = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $this->dbname, $this->username, $this->password, array());

        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->pdo->query('SET NAMES utf8mb4');



        $this->config = $config;
    }

    function log_var($tmp_log, $mode = -1) {
        $fname = './log/user_' . $this->code_id;

        if ($mode == 0) {
            if (is_string($tmp_log)) {
//                echo $tmp_log;
            } else if (is_array($tmp_log)) {
//                print_r($tmp_log);
            } else {
//                var_dump($tmp_log);
            }
            $myfile = file_put_contents($fname, $tmp_log . PHP_EOL, FILE_APPEND | LOCK_EX);
        } else if ($mode == 1) {
            $myfile = file_put_contents($fname, $tmp_log . PHP_EOL, FILE_APPEND | LOCK_EX);
        }
    }

    function checkRequest() {
        $ret = array('response' => 400, 'msg' => 'fail');
        if (isset($_REQUEST)) {


            if (isset($_REQUEST['auth_data'])) {
                $tmp_d = $_REQUEST['auth_data'];
                $ret['auth_data'] = $tmp_d;
                $auth_data = $tmp_d;

                if (isset($auth_data['code_id'])) {
                    $code_id = $auth_data['code_id'];
                    $ret['code_id'] = $code_id;
                    $sql = "select * from activation_code where code_id = $code_id order by code_id desc";
                    $stmt = $this->pdo->prepare($sql);
                    $stmt->execute();
                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    if (is_array($rows) && count($rows) > 0) {
                        $row = $rows[0];
                        $ret['row_code'] = $row;
                        $key = $row['code_host'];
                        $secret = $row['code_code'];

                        $auth = new Auth('POST', 'users', $auth_data, [
                            // new CheckKey,
                            new CheckVersion,
                            new CheckTimestamp,
                            new CheckSignature
                        ]);

                        try {
                            $token = new Token($key, $secret);

                            $auth->attempt($token);
                            $ret['response'] = 200;

                            $ret['host'] = $key;

                            if (isset($_REQUEST['action']) && strlen($_REQUEST['action']) > 0) {
                                $ret['action'] = $_REQUEST['action'];
                            }

                            if (isset($_REQUEST['key']) && strlen($_REQUEST['key']) > 0) {
                                $ret['key'] = $_REQUEST['key'];
                            }

                            if (isset($_REQUEST['token']) && strlen($_REQUEST['token']) > 0) {
                                $ret['token'] = $_REQUEST['token'];
                            }

                            if (isset($_REQUEST['url_cron']) && strlen($_REQUEST['url_cron']) > 0) {
                                $ret['url_cron'] = $_REQUEST['url_cron'];
                            }

                            if (isset($_REQUEST['cron_setting'])) {
                                $ret['cron_setting'] = $_REQUEST['cron_setting'];
                            }
                        } catch (SignatureException $e) {
                            // return 4xx
                            $ret['response'] = 301;
                            return $ret;
                        }
                    }
                }
            }
        }

        $required = array("auth_data", "code_id", "key", "token", "action", "row_code");
        foreach ($required as $value) {
            if (!isset($ret[$value])) {
                $ret['response'] = 400;
                $ret['msg'] = "$value is missing";
                break;
            }
        }


        return $ret;
    }

    function start() {
        $ret = array('response' => 400);
        $ret_chkRequest = $this->checkRequest();
        if ($ret_chkRequest['response'] == 200) {
            // all good+
            switch ($ret_chkRequest['action']) {
                case 'script1':
                    $this->script1($ret_chkRequest);
                    break;

                default:
                    // code...
                    break;
            }
        } else {
            $ret['response'] = $ret_chkRequest['response'];
            $ret['msg'] = $ret_chkRequest['msg'];
        }
        return $ret;
    }

    private $code_id = 0;
    private $skip1 = 0;
    private $isready1 = true;

    function generateF($code_id) {
        $structure = "../data/user$code_id/kindred";

        if (!file_exists($structure)) {
            if (!mkdir($structure, 0777, true)) {
                die('Failed to create folders...');
            }
        }

        $structure = "../data/user$code_id/kindred2";
        if (!file_exists($structure)) {
            if (!mkdir($structure, 0777, true)) {
                die('Failed to create folders...');
            }
        }

        return '';
    }

    function script1($input_param) {
        $host = $input_param['host'];
        $row_code = $input_param['row_code'];
        $code_id = $row_code['code_id'];
        $apiKey = $input_param['key'];
        $access_token = $input_param['token'];
        $path = "../data/user$code_id/";
        $url_cron = $row_code['code_url_cron'];
        if (isset($input_param['url_cron'])) {
            $url_cron = $input_param['url_cron'];
        }
        $this->code_id = $code_id;
        $this->generateF($code_id);

        $a = 1;
        $propIDArr = array();
        $baseurl = 'https://integrations.mydesktop.com.au/api/v1.2';
        $nextLinkData = 1;
        $iteration = 1;
        $pageID = '&page=';

        $tmp_log = "\r\n\r\n--------------------\r\nStart execution at " . date("h:i:s d-m-Y", time()) . "\r\n--------------------\n";
        $this->log_var($tmp_log, 0);

        while (null !== ($nextLinkData) && ($this->limit_datatxt > 0 || $this->limit_datatxt == -1 )) {
            $tmp_log = $iteration . " Loading...";
            $this->log_var($tmp_log, 0);
//            if(isset($input_param['cron_setting'])){
//                
//                $altpi = $baseurl . '/properties?classification='.$type.'&api_key=' . $apiKey . $pageID . $iteration;
//                
//                $data = get_prop($apiKey, $pageID, $baseurl, $access_token, $iteration, $altpi);
//            }else{
//                $data = get_prop($apiKey, $pageID, $baseurl, $access_token, $iteration, $altpi = null);    
//            }
            $data = get_prop($apiKey, $pageID, $baseurl, $access_token, $iteration, $altpi = null);

            $tmp_log = "Complete";
            $this->log_var($tmp_log, 0);

            if (!empty($data->links->next)) {
                $nextLinkData = $data->links->next;
            } else {
                $nextLinkData = null;
            }
            //	echo serialize($data);
            $fp = fopen($path . "kindred/data$iteration.txt", 'w');
            fwrite($fp, serialize($data));
            fclose($fp);
            $tmp_log = " --> Written\n";
            $this->log_var($tmp_log, 0);

            $iteration++;

            if ($this->limit_datatxt > 0 && $iteration > $this->limit_datatxt) {
                break;
            }
        }

        $tmp_log = "\r\n\r\n--------------------\r\n Starting Individual Checks  " . date("h:i:s d-m-Y", time()) . "\r\n--------------------\n";
        $this->log_var($tmp_log, 0);

        $files = glob($path . 'kindred/*.{txt}', GLOB_BRACE);

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

//        $this->log_var($files);

        if (($this->limit_datam > 0 || $this->limit_datam == -1)) {
            if (is_file($path . 'kindred2/data.m')) {
                $log1 = fopen($path . 'kindred2/orig.log', 'w');
                fwrite($log1, '');
                fclose($log1);

                $fp1 = fopen($path . 'kindred2/orig.data.m', 'w');
                fwrite($fp1, '');
                fclose($fp1);
            }

            $this->log_var("loop files entry");
            foreach ($files as $key => $file) {


                if ($key >= $this->skip1) {
                    $this->isready1 = true;
                }

                if (!$this->isready1) {
                    $this->log_var("skipped file " . $file);
                    continue;
                }
                $this->log_var($file);

                $file = file_get_contents($file);
                $dater = unserialize($file);

                $cnt_tmp = 0;

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
                        $cnt_tmp++;
                    } elseif ($saleData == 1) {
                        $datf = $properties->id . ',0,1|';
                        $cnt_tmp++;
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
                    $tmp_log = $dattt;
                    $this->log_var($tmp_log);

                    $tmp_log = $a . " --> " . $properties->id . " --> " . $dink . "\n";
                    $this->log_var($tmp_log, 0);

                    $log = fopen($path . 'kindred2/orig.log', 'a');
                    fwrite($log, $dattt);
                    fclose($log);

                    $fp = fopen($path . 'kindred2/orig.data.m', 'a');
                    fwrite($fp, $datf);
                    fclose($fp);
                    $a++;

                    if ($this->limit_datam > 0 && $this->limit_datam < $cnt_tmp) {
                        break;
                    }
                }
            }
        }


        $tmp_log = "\r\n\r\n--------------------\r\n Ending Individual Checks  " . date("h:i:s d-m-Y", time()) . "\r\n--------------------\n";
        $this->log_var($tmp_log, 0);
        $tmp_log = "\r\n\r\n--------------------\r\n Exporting to Small File  " . date("h:i:s d-m-Y", time()) . "\r\n--------------------\n";
        $this->log_var($tmp_log, 0);

        $filee = file_get_contents($path . 'kindred2/orig.data.m');
        $array1 = explode('|', $filee);

        $log1 = fopen($path . 'kindred2/log', 'w');
        fwrite($log1, '');
        fclose($log1);

        $fp1 = fopen($path . 'kindred2/data.m', 'w');
        fwrite($fp1, '');
        fclose($fp1);

        foreach ($array1 as $propertiess) {
            $properties = explode(',', $propertiess);
            if (count($properties) >= 3) {
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
                    $tmp_log = $dattt;
                    $this->log_var($tmp_log);

                    $tmp_log = $a . " --> " . $properties[0] . " --> " . $dink . "\n";
                    $this->log_var($tmp_log, 0);

                    $log = fopen($path . 'kindred2/log', 'a');
                    fwrite($log, $dattt);
                    fclose($log);

                    $fp = fopen($path . 'kindred2/data.m', 'a');
                    fwrite($fp, $datf);
                    fclose($fp);
                    $a++;
                }
            }
        }
        $tmp_log = "\r\n\r\n--------------------\r\n Export Complete  " . date("h:i:s d-m-Y", time()) . "\r\n--------------------\n";
        $this->log_var($tmp_log, 0);

        $tmp_log = "\r\n\r\n--------------------\r\n Starting External Scripts  " . date("h:i:s d-m-Y", time()) . "\r\n--------------------\n";
        $this->log_var($tmp_log, 0);

        $altapi = $url_cron . '/cron-job.php';
        $tmp_log = $altapi . "\n";
        $this->log_var($tmp_log, 0);
        $tmp_log = get_call_api($apiKey, $pageID, $baseurl, $access_token, $iteration, $altapi);
        $this->log_var($tmp_log);
//
        foreach ($files as $file) {
            // $file name
            $filename = basename($file);
            $altapi = $url_cron . '/cron-job2.php?file=' . $filename;
            $tmp_log = $altapi . "\n";
            $this->log_var($tmp_log, 0);
            $tmp_log = get_call_api($apiKey, $pageID, $baseurl, $access_token, $iteration, $altapi);
            $this->log_var($tmp_log);
        }
        $altapi = $url_cron . '/cron-job3.php';
        $tmp_log = get_call_api($apiKey, $pageID, $baseurl, $access_token, $iteration, $altapi);
        $this->log_var($tmp_log);
        $tmp_log = $altapi . "\n";
        $this->log_var($tmp_log, 0);

        $tmp_log = "\r\n\r\n--------------------\r\nEnd execution at " . date("h:i:s d-m-Y", time()) . "\r\n--------------------\n";
        $this->log_var($tmp_log, 0);
    }

    public function test2() {
        $altapi = $url_cron . '/cron-job.php';
        get_prop($apiKey, $pageID, $baseurl, $access_token, $iteration, $altapi);

        foreach ($files as $file) {
            // $file name
            $filename = basename("/etc/sudoers.d");
            $altapi = $url_cron . '/cron-job2.php?file=' . $filename;
            get_prop($apiKey, $pageID, $baseurl, $access_token, $iteration, $altapi);
        }
        $altapi = $url_cron . '/cron-job3.php';
        get_prop($apiKey, $pageID, $baseurl, $access_token, $iteration, $altapi);
    }

}

?>
