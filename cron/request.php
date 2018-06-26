<?php

date_default_timezone_set("UTC");

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

    function start() {
        $ret = array('response' => 400);
        $action = $_REQUEST['action'];
        switch ($action) {
            case 'cron':
            default:
                // call wordpress cron job for registered domains
                $sql = "select * from activation_code where 1";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $urls = array();
                if (is_array($rows) && count($rows) > 0) {
                    foreach ($rows as $row) {
                        $url_cron = $row['code_url_cron'];
                        if (strpos($url_cron, '/wp-content/') !== false) {
                            $pos = strpos($url_cron, '/wp-content/');
                            $path1 = substr($url_cron, 0,$pos);
                            $urls[] = $path1;
                        }
                    }
                }
                
                $urls = array_unique($urls);
                $this->callCronJob($urls);

                break;
        }
        return $ret;
    }
    
    public function callCronJob($urls){
        $path_end = "/wp-cron.php?doing_wp_cron";
        foreach($urls as $url){
            $url_cron = $url.$path_end;
            var_dump($url_cron);
            
            get_call_api2($url_cron);
            // call cron 
        }
    }

}

/**
*_30 * * * * /usr/local/bin/php /home/mesmo/public_html/api_sig/cron/request.php
   */     

?>

