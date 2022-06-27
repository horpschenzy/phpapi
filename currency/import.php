<?php
$method = "POST";

require_once '../config/config.php';
require_once '../models/Currency.php';

$currency = new Currency($db);

if (!isset($_FILES) || empty($_FILES)) {
    echo json_encode(
        ['status' => false, 'message' => 'Kindly upload a csv file'], 
        http_response_code(422)
    );
    die;
}

// Allowed mime types
$fileMimes = array(
    'text/x-comma-separated-values',
    'text/comma-separated-values',
    'application/octet-stream',
    'application/vnd.ms-excel',
    'application/x-csv',
    'text/x-csv',
    'text/csv',
    'application/csv',
    'application/excel',
    'application/vnd.msexcel',
    'text/plain'
);
$arr = [];
if (! empty($_FILES['currency']['name'])  
    && in_array($_FILES['currency']['type'], $fileMimes)
) {
    $fileName = $_FILES["currency"]["tmp_name"];
    if ($_FILES["currency"]["size"] > 0) {
        $file = fopen($fileName, "r");
        while (($column = fgetcsv($file)) !== false) {
            $arr[] = $column;
        }
        unset($arr[0]);
        echo json_encode($currency->insertCurrency($arr)); die;
    } else {

        echo json_encode(
            ['status' => false, 'message' => 'Empty file uploaded'], 
            http_response_code(422)
        );
        die;
    }
} else {

    echo json_encode(
        ['status' => false, 'message' => 'Kindly upload a csv file'], 
        http_response_code(422)
    );
    die;
}

