<?php
declare(strict_types=1);

function register_user(string $username, string $email, string $password, array & $errors = [], ?string $firstName = null, ?string $lastName = null): ?int {
    $username = trim($username);
    if ($username === '' || mb_strlen($username) < 3) {
        $errors[] = 'Podaj nazwę użytkownika (min. 3 znaki).';
        return null;
    }
    // basic username validation: allow letters, numbers, dash and underscore
    if (!preg_match('/^[A-Za-z0-9_\-]+$/u', $username)) {
        $errors[] = 'Nazwa użytkownika może zawierać tylko litery, cyfry, podkreślenia i myślniki.';
        return null;
    }
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    if (!$email) {
        $errors[] = 'Podaj poprawny adres e‑mail.';
        return null;
    }
    if (mb_strlen($password) < 6) {
        $errors[] = 'Hasło musi mieć minimum 6 znaków.';
        return null;
    }

    try {
        $pdo = db_connect();
        // check existing username or email
        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = :username OR email = :email LIMIT 1');
        $stmt->execute([':username' => $username, ':email' => $email]);
        if ($stmt->fetch()) {
            $errors[] = 'Nazwa użytkownika lub e‑mail są już zajęte.';
            return null;
        }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    // insert user including username and names (schema should include these columns)
    $ins = $pdo->prepare('INSERT INTO users (username, email, password_hash, first_name, last_name) VALUES (:username, :email, :hash, :fname, :lname)');
    $ins->execute([':username' => $username, ':email' => $email, ':hash' => $hash, ':fname' => $firstName, ':lname' => $lastName]);
        return (int)$pdo->lastInsertId();
    } catch (PDOException $e) {
        error_log('register_user DB error: ' . $e->getMessage());
        $errors[] = 'Błąd podczas rejestracji. Spróbuj ponownie później.';
        return null;
    }
}
