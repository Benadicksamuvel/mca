<?php
session_start();

// Redirect already-logged-in users
if (isset($_SESSION["user_id"])) {
    header("Location: user.php");
    exit;
}

require_once 'db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $password = trim($_POST["password"] ?? "");

    if ($name === "" || $password === "") {
        $error = "Both fields are required.";
    } else {
        // Fetch user by name
        $stmt = $conn->prepare("SELECT user_id, name, password FROM register WHERE name = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if ($password === $user["password"]) {
                // Successful login – start session
                $_SESSION["user_id"] = $user["user_id"];
                $_SESSION["name"] = $user["name"];
                header("Location: user.php");
                exit;
            } else {
                $error = "Incorrect password. Please try again.";
            }
        } else {
            $error = "No account found with that name.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login – Book Inventory</title>
    <meta name="description" content="Sign in to your Book Inventory account." />
    <link rel="stylesheet" href="css/style.css" />
    <style>
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
    </style>
</head>

<body>
    <div class="auth-wrapper">
        <div class="auth-card">

            <!-- Admin / Customer toggle at very top -->
            <div class="login-toggle">
                <a href="admin-login.php" class="inactive">🔑 Admin Login</a>
                <a href="index.php" class="active">👤 Customer Login</a>
            </div>

            <div class="brand">
                <div class="brand-icon">📚</div>
                <h1>Book Inventory</h1>
                <p>Sign in to your account</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger">⚠️
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form id="loginForm" method="POST" action="index.php">

                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" placeholder="John Doe"
                        value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required />
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required />
                </div>

                <button type="submit" class="btn-primary">Sign In</button>
            </form>

            <div class="auth-switch">
                Don't have an account? <a href="register.php">Register</a>
            </div>

        </div>
    </div>
</body>

</html>