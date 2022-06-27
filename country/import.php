<?php
$method = "POST";

require_once '../config/config.php';
require_once '../models/Country.php';

$country = new Country($db);

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
if (! empty($_FILES['country']['name'])  
    && in_array($_FILES['country']['type'], $fileMimes)
) {
    $fileName = $_FILES["country"]["tmp_name"];
    if ($_FILES["country"]["size"] > 0) {
        $file = fopen($fileName, "r");
        while (($column = fgetcsv($file)) !== false) {
            $arr[] = $column;
        }
        unset($arr[0]);
        echo json_encode($country->insertCountry($arr)); die;
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

