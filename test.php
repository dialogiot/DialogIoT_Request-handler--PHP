<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

include 'DialogIoTAPIHandler.php';
$auth = new DialogIoTAPIHandler();

$url = "https://iot.dialog.lk/developer/api/userdevicemgt/v1/devices/10313";
//$body = '{}';

$a = $auth->sendAPICall($url,RequestMethod::GET,null);

var_dump($a['body']);
