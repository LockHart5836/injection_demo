<?php
require_once 'config.php';

$error_message = '';
$success_message = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get and sanitize input
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        // Validate input
        if (empty($username) || empty($password)) {
            $error_message = "Please enter both username and password.";
        } elseif (!validateUsername($username)) {
            $error_message = "Invalid username format.";
        } elseif (!validatePassword($password)) {
            $error_message = "Invalid password format.";
        } else {
            // Log login attempt
            logActivity($username, "Login attempt");
            // Attempt authentication
            if (authenticateUser($username, $password)) {
                logActivity($username, "Successful login");
                // Regenerate session ID for security
                session_regenerate_id(true);
                // Redirect to dashboard
                header("Location: dashboard.php");
                exit;
            } else {
                $error_message = "Invalid username or password.";
                logActivity($username, "Failed login attempt");
            }
        }
    } catch (Exception $e) {
        $error_message = "An error occurred during login. Please try again.";
        error_log("Login error: " . $e->getMessage());
    }
}
// If already logged in, redirect to dashboard
if (isLoggedIn()) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Secure System</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #e0f7fa 0%, #f8fafc 100%);
            min-height: 100vh;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .login-box {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(60, 72, 88, 0.18);
            padding: 2.5rem 2rem 2rem 2rem;
            max-width: 400px;
            width: 100%;
            position: relative;
        }
        .system-badge.secure {
            background: #e0f7fa;
            color: #0d9488;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-weight: 600;
            font-size: 1rem;
            display: inline-block;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(13, 148, 136, 0.08);
        }
        h1 {
            margin: 0 0 0.5rem 0;
            font-size: 2rem;
            font-weight: 700;
            color: #0d9488;
        }
        .subtitle {
            color: #64748b;
            margin-bottom: 1.5rem;
        }
        .login-form {
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
            margin-bottom: 2rem;
        }
        .form-group {
            display: flex;
            flex-direction: column;
        }
        label {
            font-size: 1rem;
            color: #334155;
            margin-bottom: 0.3rem;
        }
        input[type="text"], input[type="password"] {
            padding: 0.7rem;
            border-radius: 6px;
            border: 1px solid #cbd5e1;
            font-size: 1rem;
            background: #f1f5f9;
            transition: border 0.2s;
        }
        input:focus {
            border-color: #0d9488;
            outline: none;
        }
        .login-btn {
            background: linear-gradient(90deg, #0d9488 0%, #38bdf8 100%);
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 0.8rem;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(13, 148, 136, 0.08);
            transition: background 0.2s;
        }
        .login-btn:hover {
            background: linear-gradient(90deg, #0e7490 0%, #2563eb 100%);
        }
        .error-message {
            background: #fee2e2;
            color: #b91c1c;
            border-radius: 8px;
            padding: 0.7rem 1rem;
            margin-bottom: 1rem;
            font-size: 1rem;
            box-shadow: 0 2px 8px rgba(185, 28, 28, 0.08);
        }
        .success-message {
            background: #d1fae5;
            color: #065f46;
            border-radius: 8px;
            padding: 0.7rem 1rem;
            margin-bottom: 1rem;
            font-size: 1rem;
            box-shadow: 0 2px 8px rgba(6, 95, 70, 0.08);
        }
        .demo-info {
            background: #f1f5f9;
            border-radius: 10px;
            padding: 1rem;
            margin-top: 1rem;
            font-size: 0.98rem;
            color: #334155;
            box-shadow: 0 1px 4px rgba(51, 65, 85, 0.06);
        }
        .demo-info h3 {
            margin-top: 0.5rem;
            margin-bottom: 0.3rem;
            font-size: 1.08rem;
            color: #0d9488;
        }
        .demo-info code {
            background: #e0f7fa;
            color: #0d9488;
            border-radius: 4px;
            padding: 2px 6px;
            font-size: 0.97em;
        }
        .demo-info ul {
            margin: 0.5rem 0 0 1.2rem;
            padding: 0;
        }
        .demo-info li {
            margin-bottom: 0.3rem;
            font-size: 0.97em;
        }
        .small-text {
            font-size: 0.92em;
            color: #64748b;
            margin-top: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-box">
            <div class="system-badge secure">
                <span>🔒 SECURE SYSTEM</span>
            </div>
            <h1>Login Portal</h1>
            <p class="subtitle">Educational Demo - Secure Version</p>
            <?php if (!empty($error_message)): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($success_message)): ?>
                <div class="success-message">
                    <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>
            <form action="login.php" method="POST" class="login-form">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" 
                           id="username" 
                           name="username" 
                           required 
                           maxlength="50"
                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                           autocomplete="username">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           required 
                           maxlength="255"
                           autocomplete="current-password">
                </div>
                <button type="submit" class="login-btn">Login</button>
            </form>
            <div class="demo-info">
                <h3>Test Credentials:</h3>
                <p><strong>Admin:</strong> admin / admin123</p>
                <p><strong>User:</strong> user / user123</p>
                <h3>Security Features:</h3>
                <ul>
                    <li>✅ Prepared statements prevent SQL injection</li>
                    <li>✅ Input validation and sanitization</li>
                    <li>✅ No system command execution</li>
                    <li>✅ Password hashing with bcrypt</li>
                    <li>✅ Secure session management</li>
                    <li>✅ Proper error handling</li>
                </ul>
                <h3>Try These Injection Attempts:</h3>
                <p>❌ <code>admin' OR '1'='1' --</code></p>
                <p>❌ <code>admin; ls -la</code></p>
                <p>❌ <code>' UNION SELECT * FROM users --</code></p>
                <p class="small-text">All injection attempts will be safely blocked!</p>
            </div>
        </div>
    </div>
    <script>
        // Enhanced client-side validation
        document.querySelector('.login-form').addEventListener('submit', function(e) {
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            // Basic validation
            if (username.length < 3) {
                alert('Username must be at least 3 characters long');
                e.preventDefault();
                return;
            }
            if (password.length < 6) {
                alert('Password must be at least 6 characters long');
                e.preventDefault();
                return;
            }
            // Check for suspicious patterns
            const suspiciousPatterns = [/'/, /;/, /--/, /union/i, /select/i, /drop/i, /insert/i, /update/i, /delete/i];
            for (let pattern of suspiciousPatterns) {
                if (pattern.test(username) || pattern.test(password)) {
                    alert('Invalid characters detected. Please use only alphanumeric characters.');
                    e.preventDefault();
                    return;
                }
            }
        });
    </script>
</body>
</html>
