<?php
require_once __DIR__ . '/../SaySoft/dbconn.php';

class Dashboard {

    private PDO $pdo;

    public function __construct() {
        $this->pdo = db_connect();
    }

    public function writeContent() {
        $html = "";

        
        return $html;
    }
}
