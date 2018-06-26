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
            case 'json':
                $code_id = 2;
                $sql = "select * from activation_code where code_id = $code_id ";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (is_array($rows) && count($rows) > 0) {
                    $row = $rows[0];
                    $ret['activation_code'] = $row;
                    $ret['response'] = 200;
                }
            case 'get_mesmo_id':
                if (isset($_REQUEST['mesmo_code'])) {
                    $code = $_REQUEST['mesmo_code'];
                    $sql = "select * from activation_code where code_code = '$code' ";
                    $stmt = $this->pdo->prepare($sql);
                    $stmt->execute();
                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    if (is_array($rows) && count($rows) > 0) {
                        $row = $rows[0];
                        $ret['activation_code'] = $row;
                        $ret['response'] = 200;
                    }
                }
            case "generate_f":
                $code_id = 1;
                if (isset($_REQUEST['code_id'])) {
                    $code_id = $_REQUEST['code_id'];
                }
                $this->generateF($code_id);
                break;
        }
        return $ret;
    }

    function generateK($input_param) {
        $url_cron = $input_param['url_cron'];
        $host = $input_param['host'];
        $hash = generatekey();
        $key = $hash['key'];
        $secret = $hash['secret'];

        $sql = "INSERT INTO `activation_code`(`code_host`, `code_code`, `code_url_cron`) VALUES (:host,:code,:url_cron)";
        $stmt = $this->pdo->prepare("INSERT INTO `activation_code`(`code_host`, `code_code`, `code_url_cron`) VALUES (:host,:code,:url_cron)");
        $stmt->bindParam(':host', $host);
        $stmt->bindParam(':code', $secret);
        $stmt->bindParam(':url_cron', $url_cron);

        $stmt->execute();

        $sql = "SELECT * FROM activation_code WHERE code_host = ? order by code_id desc";
        $stmt = $this->pdo->prepare($sql);
        if ($stmt->execute(array($host))) {
            while ($row = $stmt->fetch()) {
                $ret['activation_code'] = $row;
                $ret['response'] = 200;

                // generate
                $this->generateF($row['code_id']);

                return $ret;
                break;
            }
        }
        return array();
    }

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

}

?>
