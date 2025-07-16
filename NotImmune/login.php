<?php
require_once 'config.php';

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    // Vulnerable authentication
    if (validateUser($username, $password)) {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = 'user';
        header('Location: dashboard.php');
        exit;
    } else {
        $error_message = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Vulnerable System</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #e0e7ff 0%, #f8fafc 100%);
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
        .system-badge.vulnerable {
            background: #ffe5e5;
            color: #b91c1c;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-weight: 600;
            font-size: 1rem;
            display: inline-block;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(185, 28, 28, 0.08);
        }
        h1 {
            margin: 0 0 0.5rem 0;
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
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
            border-color: #6366f1;
            outline: none;
        }
        .login-btn {
            background: linear-gradient(90deg, #6366f1 0%, #60a5fa 100%);
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 0.8rem;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(99, 102, 241, 0.08);
            transition: background 0.2s;
        }
        .login-btn:hover {
            background: linear-gradient(90deg, #4338ca 0%, #2563eb 100%);
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
            color: #6366f1;
        }
        .demo-info code {
            background: #e0e7ff;
            color: #1e293b;
            border-radius: 4px;
            padding: 2px 6px;
            font-size: 0.97em;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-box">
            <div class="system-badge vulnerable">
                <span>⚠️ VULNERABLE SYSTEM</span>
            </div>
            <h1>Login Portal</h1>
            <p class="subtitle">Educational Demo - Vulnerable Version</p>
            <?php if (!empty($error_message)): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            <form action="login.php" method="POST" class="login-form">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="login-btn">Login</button>
            </form>
            <div class="demo-info">
                <h3>Test Credentials:</h3>
                <p><strong>Admin:</strong> admin / admin123</p>
                <p><strong>User:</strong> user / user123</p>
                <h3>SQL Injection Examples:</h3>
                <p><code>admin' OR '1'='1' --</code></p>
                <p><code>admin' UNION SELECT username, password FROM users --</code></p>
                <h3>Command Injection Examples:</h3>
                <p><code>admin; ls -la</code></p>
                <p><code>admin && whoami</code></p>
            </div>
        </div>
    </div>
    <script>
        // Add some interactivity
        document.querySelector('.login-form').addEventListener('submit', function(e) {
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            if (username.includes("'") || username.includes(";") || username.includes("--")) {
                if (!confirm("This input looks like an injection attempt. Continue for demonstration?")) {
                    e.preventDefault();
                }
            }
        });
    </script>
</body>
</html>
