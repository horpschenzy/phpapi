<?php
$method = "GET";

require_once '../config/config.php';
require_once '../models/Country.php';

$country = new Country($db);

echo json_encode($country->listData()); die;