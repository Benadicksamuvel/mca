<?php
session_start();
if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
    header("Location: admin-dashboard.php");
    exit;
}
$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $u = trim($_POST["username"] ?? "");
    $p = trim($_POST["password"] ?? "");
    if ($u === "sham" && $p === "bensham07") {
        $_SESSION['admin'] = true;
        header("Location: admin-dashboard.php");
        exit;
    }
    $error = "Invalid credentials. Please try again.";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Login – Book Inventory</title>
    <link rel="stylesheet" href="css/style.css" />
    <style>
        /* Toggle tabs */
        .login-toggle {
            display: flex;
            border-radius: 10px 10px 0 0;
            overflow: hidden;
            margin-bottom: 28px;
            border: 1.5px solid #6c3ce1;
        }

        .login-toggle a {
            flex: 1;
            text-align: center;
            padding: 12px 0;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            transition: background .25s, color .25s;
        }

        .login-toggle a.active {
            background: linear-gradient(135deg, #6c3ce1, #a855f7);
            color: #fff;
        }

        .login-toggle a.inactive {
            background: rgba(255, 255, 255, .04);
            color: #a78bfa;
        }

        .login-toggle a.inactive:hover {
            background: rgba(108, 60, 225, .15);
        }

        .role-badge {
            display: inline-block;
            background: rgba(168, 85, 247, .18);
            color: #a855f7;
            border-radius: 20px;
            padding: 4px 14px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }
    </style>
</head>

<body>
    <div class="auth-wrapper">
        <div class="auth-card">

            <!-- Toggle at the very top -->
            <div class="login-toggle">
                <a href="admin-login.php" class="active">🔑 Admin Login</a>
                <a href="index.php" class="inactive">👤 Customer Login</a>
            </div>

            <div class="brand">
                <div class="brand-icon">🛡️</div>
                <span class="role-badge">Admin</span>
                <h1>Book Inventory</h1>
                <p>Sign in to the Admin Panel</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger">⚠️
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="admin-login.php">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Admin username"
                        value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required />
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required />
                </div>
                <button type="submit" class="btn-primary">🔓 Sign In as Admin</button>
            </form>

        </div>
    </div>
</body>

</html>