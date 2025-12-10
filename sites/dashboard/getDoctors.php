<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../SaySoft/master.php';

if (!isset($_GET['spec'])) {
    echo json_encode([]);
    exit;
}

$specId = $_GET['spec'];


$dashboard = new Dashboard();
$doctors = $dashboard->getDoctors();


echo json_encode($doctors[$specId] ?? []);

