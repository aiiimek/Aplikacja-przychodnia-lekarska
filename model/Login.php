<?php
declare(strict_types=1);

// --- Settings ---
if (!defined('MAX_LOGIN_ATTEMPTS')) define('MAX_LOGIN_ATTEMPTS', 5);
if (!defined('LOCKOUT_SECONDS')) define('LOCKOUT_SECONDS', 300); // 5 minut

function login_init(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 0;
        $_SESSION['last_attempt'] = 0;
    }

    // Try restore session from remember-me cookie if present
    if (empty($_SESSION['user_id']) && !empty($_COOKIE['remember'])) {
        // cookie format: selector:validator (both base64 or hex)
        if (function_exists('verify_remember_cookie')) {
            $res = verify_remember_cookie($_COOKIE['remember']);
            if ($res && isset($res['user'])) {
                $_SESSION['user_id'] = $res['user']['id'];
                $_SESSION['user_email'] = $res['user']['email'];
                $_SESSION['user_role'] = $res['user']['role'] ?? null;
            }
        }
    }
}

// Note: CSRF helpers removed per request. If you need CSRF later, reintroduce a token system.



// --- Remember-me helpers ---
function generate_remember_token(): array {
    // returns ['selector' => ..., 'validator' => ..., 'token' => selector:validator]
    $selector = bin2hex(random_bytes(6)); // 12 chars
    $validator = bin2hex(random_bytes(32)); // 64 chars
    $token = $selector . ':' . $validator;
    return ['selector' => $selector, 'validator' => $validator, 'token' => $token];
}

function store_remember_token(int $userId, string $selector, string $validator, int $durationSeconds = 2592000): bool {
    try {
        $pdo = db_connect();
        $tokenHash = hash('sha256', $validator);
        $expiresAt = date('Y-m-d H:i:s', time() + $durationSeconds);
        $stmt = $pdo->prepare('INSERT INTO user_remember_tokens (user_id, selector, token_hash, expires_at) VALUES (:uid, :sel, :hash, :exp)');
        return $stmt->execute([':uid' => $userId, ':sel' => $selector, ':hash' => $tokenHash, ':exp' => $expiresAt]);
    } catch (PDOException $e) {
        error_log('store_remember_token DB error: ' . $e->getMessage());
        return false;
    }
}

function clear_remember_tokens_for_user(int $userId): void {
    try {
        $pdo = db_connect();
        $stmt = $pdo->prepare('DELETE FROM user_remember_tokens WHERE user_id = :uid');
        $stmt->execute([':uid' => $userId]);
    } catch (PDOException $e) {
        error_log('clear_remember_tokens_for_user DB error: ' . $e->getMessage());
    }
}

function verify_remember_cookie(string $cookie): ?array {
    // returns ['user'=>..., 'token_row'=>...] or null
    [$selector, $validator] = explode(':', $cookie) + [null, null];
    if (!$selector || !$validator) return null;
    try {
        $pdo = db_connect();
        $stmt = $pdo->prepare('SELECT urt.*, u.id AS uid, u.email, u.role FROM user_remember_tokens urt JOIN users u ON urt.user_id = u.id WHERE urt.selector = :sel LIMIT 1');
        $stmt->execute([':sel' => $selector]);
        $row = $stmt->fetch();
        if (!$row) return null;
        if (strtotime($row['expires_at']) < time()) {
            // expired -> remove
            $stmtDel = $pdo->prepare('DELETE FROM user_remember_tokens WHERE id = :id');
            $stmtDel->execute([':id' => $row['id']]);
            return null;
        }
        $hash = hash('sha256', $validator);
        if (hash_equals($row['token_hash'], $hash)) {
            return ['user' => ['id' => $row['uid'], 'email' => $row['email'], 'role' => $row['role']], 'token_row' => $row];
        }
        return null;
    } catch (PDOException $e) {
        error_log('verify_remember_cookie DB error: ' . $e->getMessage());
        return null;
    }
}

function is_locked(): bool {
    $attempts = (int)($_SESSION['login_attempts'] ?? 0);
    $last = (int)($_SESSION['last_attempt'] ?? 0);
    if ($attempts >= MAX_LOGIN_ATTEMPTS) {
        return (time() - $last) < LOCKOUT_SECONDS;
    }
    return false;
}

function record_failed_attempt(): void {
    $_SESSION['login_attempts'] = (int)($_SESSION['login_attempts'] ?? 0) + 1;
    $_SESSION['last_attempt'] = time();
}

function reset_attempts(): void {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['last_attempt'] = 0;
}

/**
 * Attempt to authenticate a user.
 * @param string $email
 * @param string $password
 * @param array  $errors  reference to collect error messages
 * @return bool true on success (session is prepared), false otherwise
 */
function attempt_login(string $login, string $password, array & $errors = [], array & $fieldErrors = null, bool $remember = false): bool {
    $login = trim($login);
    if ($login === '') {
        $msg = 'Podaj nazwę użytkownika.';
        $errors[] = $msg;
        if (is_array($fieldErrors)) $fieldErrors['login'][] = $msg;
        return false;
    }
    if (!$password || mb_strlen($password) < 6) {
        $msg = 'Podaj hasło (minimum 6 znaków).';
        $errors[] = $msg;
        if (is_array($fieldErrors)) $fieldErrors['password'][] = $msg;
        return false;
    }

    if (is_locked()) {
        $msg = 'Przekroczono liczbę prób. Spróbuj ponownie po kilku minutach.';
        $errors[] = $msg;
        if (is_array($fieldErrors)) $fieldErrors['general'][] = $msg;
        return false;
    }

    try {
        $pdo = db_connect();
        // Authenticate by username (login)
        $stmt = $pdo->prepare('SELECT id, username, email, password_hash, role FROM users WHERE username = :login LIMIT 1');
        $stmt->execute([':login' => $login]);
        $user = $stmt->fetch();

        if ($user && !empty($user['password_hash']) && password_verify($password, $user['password_hash'])) {
            // success
            reset_attempts();
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_username'] = $user['username'] ?? null;
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'] ?? null;

            // Handle remember-me
            if ($remember) {
                // clear old tokens for this user (optional: keep multiple)
                clear_remember_tokens_for_user((int)$user['id']);
                $rt = generate_remember_token();
                if (store_remember_token((int)$user['id'], $rt['selector'], $rt['validator'])) {
                    // set cookie for 30 days
                    setcookie('remember', $rt['token'], time() + 60*60*24*30, '/', '', isset($_SERVER['HTTPS']), true);
                }
            }
            return true;
        }

        // failed
        record_failed_attempt();
        $msg = 'Nieprawidłowy e‑mail lub hasło.';
        $errors[] = $msg;
        if (is_array($fieldErrors)) {
            // We can't know which field exactly is wrong (could be email or password), mark both
            $fieldErrors['email'][] = $msg;
            $fieldErrors['password'][] = $msg;
        }
        return false;

    } catch (PDOException $e) {
        error_log('DB error: ' . $e->getMessage());
        $errors[] = 'Błąd połączenia z bazą danych.';
        return false;
    }
}
