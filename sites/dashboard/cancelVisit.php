<?php
require_once __DIR__ . '/../../SaySoft/master.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$visitId = $data['visitId'] ?? null;
$userId = $_SESSION['userId'] ?? null;

if (!$visitId || !$userId) {
    echo json_encode(['success' => false]);
    exit;
}

$dashboard = new Dashboard;
$ok = $dashboard->cancelVisit($visitId);

echo json_encode(['success' => $ok]);
