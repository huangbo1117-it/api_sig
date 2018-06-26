<?php

/**
 * Description of config
 *
 * @author hgc
 */
$g_env = 1;     //  0   local       1   server
$g_pushmode = 1;    //  0   dev     1   product
$g_config = array();
if ($g_env == 0) {
    $db = array(
        'host' => 'localhost',
        'dbname' => 'mesmo_krest',
        'username' => 'root',
        'password' => '111',
    );

    $g_config = array(
        "host" => $db['host'],
        "dbname" => $db['dbname'],
        "username" => $db['username'],
        "password" => $db['password']);

    if ($g_pushmode == 0) {
        $g_config["pushconfig"] = array(
            // The APNS server that we will use
            'server' => 'gateway.sandbox.push.apple.com:2195',
            // The SSL certificate that allows us to connect to the APNS servers
            'certificate' => 'C:\xampp\htdocs\iospush1\push\ckDev.pem',
            'passphrase' => 'twinklestar',
            // Configuration of the MySQL database
            'db' => $db,
            // Name and path of our log file
            'logfile' => 'd:\push_development.log',
        );
    } else {
        $g_config["pushconfig"] = array(
            // The APNS server that we will use
            'server' => 'gateway.push.apple.com:2195',
            // The SSL certificate that allows us to connect to the APNS servers
            'certificate' => 'C:\xampp\htdocs\iospush1\push\ckPro.pem',
            'passphrase' => 'twinklestar',
            // Configuration of the MySQL database
            'db' => $db,
            // Name and path of our log file
            'logfile' => 'd:\push_production.log',
        );
    }
} else {
    //  travpholer 8VjdF#xQ1pB4w   root bohuang29@mysql     139.162.42.92
    $db = array(
        'host' => 'localhost',
        'dbname' => 'mesmo_krest',
        'username' => 'mesmo_krest_hgc',
        'password' => 'g~@W8N[355mz',
    );

    $g_config = array(
        "host" => $db['host'],
        "dbname" => $db['dbname'],
        "username" => $db['username'],
        "password" => $db['password']);

    if ($g_pushmode == 0) {
        $g_config["pushconfig"] = array(
            // The APNS server that we will use
            'server' => 'gateway.sandbox.push.apple.com:2195',
            // The SSL certificate that allows us to connect to the APNS servers
            'certificate' => '/var/www/V0003/htdocs/shareasuccess.com/iospush1/push/ckDev.pem',
            'passphrase' => 'twinklestar',
            // Configuration of the MySQL database
            'db' => $db,
            // Name and path of our log file
            'logfile' => '/root/log/push_development.log',
        );
    } else {
        $g_config["pushconfig"] = array(
            // The APNS server that we will use
            'server' => 'gateway.push.apple.com:2195',
            // The SSL certificate that allows us to connect to the APNS servers
            'certificate' => '/var/www/V0003/htdocs/shareasuccess.com/iospush1/push/ckPro.pem',
            'passphrase' => 'twinklestar',
            // Configuration of the MySQL database
            'db' => $db,
            // Name and path of our log file
            'logfile' => '/root/log/push_production.log',
        );
    }
}


if (isset($_REQUEST['printmode']) && $_REQUEST['printmode'] == 1) {
    
} else {
//    header('Content-Type: application/json');
}

$g_manufacturer_aliases = array("id", "alias_id");
$g_manufacturers = array("id", "name");
$g_oem_printer_series = array("id", "manufacturer_id", "name");
$g_oem_printer_types = array("code", "description");
$g_oem_printers = array("id", "name", "manufacturer_id", "manufacturer_name", "series_id", "series_name", "type");
$g_oem_supplies = array("id", "part_number", "manufacturer_id", "manufacturer_name", "type", "description");
$g_oem_supply_types = array("code", "description");
$g_printer_oem_printers = array("printer_sku", "oem_printer_id");
$g_printer_oem_supplies = array("printer_sku", "oem_supply_id");
$g_printer_supplies = array("printer_sku", "supply_sku");
$g_printers = array("sku");
$g_printers_to_oem_printers = array("printer_sku", "oem_printer_name", "oem_printer_manufacturer_name", "oem_printer_series_name", "oem_printer_type");
$g_printers_to_oem_supplies = array("printer_sku", "oem_supply_part_number", "oem_supply_manufacturer_name", "oem_supply_type", "oem_supply_description");
$g_printers_to_supplies = array("printer_sku", "supply_sku");
$g_supplies = array("sku");
$g_supplies_to_oem_printers = array("supply_sku", "oem_printer_name", "oem_printer_manufacturer_name", "oem_printer_series_name", "oem_printer_type");
$g_supplies_to_oem_supplies = array("supply_sku", "oem_supply_part_number", "oem_supply_manufacturer_name", "oem_supply_type", "oem_supply_description");
$g_supply_oem_printers = array("supply_sku", "oem_printer_id");
$g_supply_oem_supplies = array("supply_sku", "oem_supply_id");
$g_v_category = array("categoryid", "parentid", "categoryname");
$g_v_product = array("pid", "sku", "pname");

// $primarys = array("id","id","id","code",
// "id","id","code",
// "printer_sku","printer_sku","printer_sku",
// "sku","printer_sku","printer_sku","printer_sku",
// "sku","supply_sku","supply_sku","supply_sku","supply_sku");
$g_additional_fields = array("likes", "commentcount", "likescount", "ilikethis", "bucketcount");

$g_table_status = array("initial" => 2, "published" => 0, "deleted" => 3, "trip_completed" => 4);
$g_bucket_type = array("personal" => 0, "shared" => 1);
$g_trip_status = array("initial" => 2, "published" => 0, "deleted" => 3, "trip_completed" => 4);
$g_trip_type = array("photo" => 0, "video" => 1, "trip" => 2);
$g_reserve_type = array("trip" => 0, "reserve" => 1);
$g_tablenames = array(
    "tbl_itin_day" => "tbl_itin_day",
    "tbl_itin_transport" => "tbl_itin_transport",
    "tbl_itin_rest" => "tbl_itin_rest",
    "tbl_photos" => "tbl_photos",
    "tbl_trip_day" => "tbl_trip_day",
    "tbl_trip_transport" => "tbl_trip_transport",
    "tbl_trip_rest" => "tbl_trip_rest",
    "tbl_trip_photos" => "tbl_trip_photos"
);
//$g_searchterm = array("tu_id", "tp_countryid", "tp_id_viewtop", "tp_id_viewbottom", "tp_steps", "tp_fetcharrow", "tp_location", "create_datetime", "tp_category", //"tp_key","ti_type");

$g_searchterm = array("tu_id", "tp_id_viewtop", "tp_id_viewbottom", "tp_steps", "tp_fetcharrow", "tp_location", "create_datetime", "tp_countryid", "visitor_id",
    "tp_category", "tp_key", "bucket_id", "view_mode", "ti_type", "tp_ids", "tu_last_noti", "action", "map_zoom", "map_distance", "age_start", "age_end");
$g_trip_joinstatus = array("accept" => 2, "reject" => 1, "initial" => 0);
$g_tbl_make_itin = array("tp_id", "tp_status", "itin_id", "itin_days", "itin_stories", "tu_id", "action");
$g_tbl_nature = array("itin" => "1", "trip" => "2");
$g_tbl_role = array("user" => "0", "admin" => "1", "premium" => "2");
$g_poly_type = array('dots' => 0, 'polygon' => 1);
