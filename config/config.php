<?php
/**
 * Config file to connect to Database
 * 
 * PHP version 8
 * 
 * @category Database_Connection
 * @package  Agpaytech
 * @author   Horpschenzy <opeoluwa.lanre@gmail.com>
 * @license  1.0 agpaytech.test
 * @link     agpaytech.test
 */

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
if ($method != $_SERVER['REQUEST_METHOD']) {
    header('Allow: ');
    header('HTTP/1.1 405 Method Not Allowed');
    header('Access-Control-Allow-Methods: '.$method);
    echo json_encode(
        ['status' => false, 
        'message' => "only $method method allowed"]
    );
    exit;
}

require_once 'Database.php';
// Instantiate DB & connect
$database = new Database();
$db = $database->connect();
