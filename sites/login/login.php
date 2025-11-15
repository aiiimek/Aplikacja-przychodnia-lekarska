<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../SaySoft/master.php';

$raw = $_POST['dataArr'] ?? '';
$dataArr = json_decode($raw, true);

if (!is_array($dataArr)) {
    echo json_encode(['success' => false, 'message' => 'Błędne dane wejściowe']);
    exit;
}

$auth = new Login();
$result = $auth->loginUser($dataArr);


echo json_encode($result);
