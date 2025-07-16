<?php
// Vulnerable Database Configuration
// WARNING: This file contains intentional security vulnerabilities for educational purposes

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'injection_demo');

function getConnection() {
    $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if (!$connection) {
        die("Database connection failed: " . mysqli_connect_error() . 
            "<br>Host: " . DB_HOST . 
            "<br>User: " . DB_USER . 
            "<br>Database: " . DB_NAME);
    }
    return $connection;
}

function logActivity($username, $action) {
    // VULNERABLE: Direct command execution without sanitization
    $logEntry = date('Y-m-d H:i:s') . " - User: $username, Action: $action";
    $command = "echo '$logEntry' >> /tmp/app_logs.txt";
    system($command);
    // Also log to database (vulnerable query)
    $conn = getConnection();
    $ip = $_SERVER['REMOTE_ADDR'];
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $query = "INSERT INTO system_logs (username, action, ip_address, user_agent) 
              VALUES ('$username', '$action', '$ip', '$userAgent')";
    mysqli_query($conn, $query);
    mysqli_close($conn);
}

function getSystemInfo($command = 'whoami') {
    // VULNERABLE: Direct system command execution
    $output = shell_exec($command);
    return $output;
}

ini_set('session.cookie_httponly', 0); // Vulnerable to XSS
ini_set('session.cookie_secure', 0);   // Not using HTTPS only
ini_set('session.use_strict_mode', 0); // Allows session fixation
session_start();

define('DEBUG_MODE', true);
if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    if (isset($_GET['debug']) && $_GET['debug'] == 'phpinfo') {
        phpinfo();
        exit;
    }
}

function sanitizeInput($input) {
    return $input; // No sanitization!
}

function validateUser($username, $password) {
    return true;
}

$GLOBALS['current_user'] = null;
$GLOBALS['user_role'] = null;
$GLOBALS['debug_info'] = array();
