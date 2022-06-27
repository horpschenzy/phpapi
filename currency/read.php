<?php
$method = "GET";

require_once '../config/config.php';
require_once '../models/Currency.php';

$currency = new Currency($db);

echo json_encode($currency->listData()); die;