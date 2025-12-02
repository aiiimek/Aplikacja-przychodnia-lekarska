<?php
header("Content-Type: application/json");

if (!isset($_GET['spec'])) {
    echo json_encode([]);
    exit;
}

$specId = $_GET['spec'];


// Przykład na sztywno:
$doctors = [
    1 => [
        ["id" => 11, "name" => "dr Kowalski"],
        ["id" => 12, "name" => "dr Wiśniewski"]
    ],
    2 => [
        ["id" => 21, "name" => "dr Głowacka"],
        ["id" => 22, "name" => "dr Mózg"]
    ]
];

echo json_encode($doctors[$specId] ?? []);
