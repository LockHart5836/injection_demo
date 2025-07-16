<?php
// Secure Database Configuration
// WARNING: This file contains intentional security vulnerabilities for educational purposes

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'injection_demo');

function getSecureConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_PERSISTENT         => false,
        ];
        return new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        error_log("Database connection failed: " . $e->getMessage());
        throw new Exception("Database connection failed. Please try again later.");
    }
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

function authenticateUser($username, $password) {
    try {
        $conn = getSecureConnection();
        $stmt = $conn->prepare("SELECT id, username, password_hash, role FROM secure_users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        if ($user && verifyPassword($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['last_activity'] = time();
            return true;
        }
        return false;
    } catch (Exception $e) {
        error_log("Authentication error: " . $e->getMessage());
        return false;
    }
}

function isLoggedIn() {
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['last_activity'])) {
        return false;
    }
    // Session timeout: 30 minutes
    if (time() - $_SESSION['last_activity'] > 1800) {
        session_unset();
        session_destroy();
        return false;
    }
    $_SESSION['last_activity'] = time();
    return true;
}

function validateUsername($username) {
    return preg_match('/^[a-zA-Z0-9_]{3,50}$/', $username);
}

function validatePassword($password) {
    return preg_match('/^[a-zA-Z0-9!@#$%^&*()_+=-]{6,255}$/', $password);
}

function logActivity($username, $action) {
    // Secure logging (no command injection)
    $conn = getSecureConnection();
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $stmt = $conn->prepare("INSERT INTO system_logs (username, action, ip_address, user_agent) VALUES (?, ?, ?, ?)");
    $stmt->execute([$username, $action, $ip, $userAgent]);
}

session_start();
