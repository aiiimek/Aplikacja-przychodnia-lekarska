<?php
require_once __DIR__ . '/../SaySoft/dbconn.php';

class Login {

    private PDO $pdo;

    public function __construct() {
        $this->pdo = db_connect();
    }

    public function loginUser(array $data): array {
        if (empty($data['username']) || empty($data['password'])) {
            return ['success' => false, 'message' => 'Podaj login i hasło'];
        }

        try {
            $stmt = $this->pdo->prepare("CALL loginUser(:username, :password, @status, @userId, @role)");
            $stmt->execute([
                ':username' => $data['username'],
                ':password' => $data['password']
            ]);

            $statusRow = $this->pdo->query("SELECT @status AS status, @userId AS userId, @role AS role")->fetch();
            $status = $statusRow['status'] ?? 'WRONG';

            if ($status === 'OK') {
                session_start();
                $_SESSION['userId'] = $statusRow['userId'];
                $_SESSION['login'] = $data['username'];
                $_SESSION['role'] = $statusRow['role'];
                return ['success' => true, 'message' => 'Zalogowano'];
            } else {
                return ['success' => false, 'message' => 'Nieprawidłowy login lub hasło'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Błąd bazy danych: ' . $e->getMessage()];
        }
    }
}
