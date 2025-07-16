<?php
require_once 'config.php';
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}
$username = $_SESSION['username'];
$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'user';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #e0f7fa 0%, #f8fafc 100%);
            min-height: 100vh;
            margin: 0;
        }
        .container {
            max-width: 500px;
            margin: 60px auto;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(60, 72, 88, 0.18);
            padding: 2.5rem 2rem 2rem 2rem;
        }
        h1 {
            color: #6366f1;
            margin-bottom: 0.5rem;
        }
        .info {
            background: #f1f5f9;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            color: #334155;
        }
        .logout-btn {
            background: linear-gradient(90deg, #6366f1 0%, #60a5fa 100%);
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 0.8rem 1.2rem;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(99, 102, 241, 0.08);
            transition: background 0.2s;
        }
        .logout-btn:hover {
            background: linear-gradient(90deg, #4338ca 0%, #2563eb 100%);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
        <div class="info">
            <p><strong>Role:</strong> <?php echo htmlspecialchars($role); ?></p>
        </div>
        <form method="post" action="logout.php">
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>
</body>
</html>
