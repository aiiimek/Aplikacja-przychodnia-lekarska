<?php
require_once __DIR__ . '/../../SaySoft/master.php';

$dashboard = new Dashboard();
$dashboard->setVisit(
    $_POST['spec'] ?? '',
    $_POST['doctorid'] ?? '',
    $_POST['visitDate'] ?? '',
    $_POST['visitDesc'] ?? '',
    $_SESSION['userId']
);

