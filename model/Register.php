<?php
require_once __DIR__ . '/../SaySoft/dbconn.php';


class Register {

    private PDO $pdo;
    private string $key = 'wolyzerka69'; 

    public function __construct() {
        $this->pdo = db_connect();
    }

    public function registerUser(array $data): array {
        $requiredFields = ['firstName', 'lastName', 'username', 'email', 'password', 'password2', 'agr'];

        // Walidacja wymaganych pól
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || trim($data[$field]) === '') {
                return [
                    'success' => false,
                    'message' => "Brak pola: $field"
                ];
            }
        }

        // Walidacja haseł
        if ($data['password'] !== $data['password2']) {
            return [
                'success' => false,
                'message' => "Hasła nie są takie same"
            ];
        }

        try {
            // Wywołanie procedury MySQL
            $stmt = $this->pdo->prepare("CALL registerUser(:key, :data, @status)");
            $stmt->execute([
                ':key' => $this->key,
                ':data' => json_encode($data)
            ]);

            // Pobranie statusu OUT
            $statusRow = $this->pdo->query("SELECT @status AS status")->fetch();
            $status = $statusRow['status'] ?? 'ERROR';

            if ($status === 'OK') {
                return ['success' => true, 'message' => 'Zapisano'];
            } elseif ($status === 'EXISTS') {
                return ['success' => false, 'message' => 'Użytkownik już istnieje'];
            } else {
                return ['success' => false, 'message' => 'Błąd rejestracji'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Błąd bazy danych: ' . $e->getMessage()];
        }
    }
}

