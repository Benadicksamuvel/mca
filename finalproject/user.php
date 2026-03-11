<?php
session_start();

// Guard: redirect to login if not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

require_once 'db.php';

// Fetch full user data
$stmt = $conn->prepare(
    "SELECT name, phone, address, email FROM register WHERE user_id = ?"
);
$stmt->bind_param("i", $_SESSION["user_id"]);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>User – Book Inventory</title>
    <meta name="description" content="Your Book Inventory Details." />
    <link rel="stylesheet" href="css/style.css" />
</head>

<body>
    <div class="menu-bar">
        <nav>

            <a href="user.php">User Page</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="">Cart</a>
            <a href="">Contact Us</a>
            <a href="logout.php">Logout</a>
        </nav>

    </div>
    <div class="dashboard-wrapper">
        <div class="dashboard-card">

            <div class="dashboard-header">
                <div>
                    <h2>Welcome back,
                        <?= htmlspecialchars($user["name"]) ?>! 👋
                    </h2>
                    <p style="color:var(--text-muted);font-size:13px;margin-top:4px;">
                        You are signed in to your Book Inventory account.
                    </p>
                </div>
                <span class="badge">Active</span>
            </div>

            <div class="user-info-grid">
                <div class="info-item">
                    <div class="label">Full Name</div>
                    <div class="value">
                        <?= htmlspecialchars($user["name"]) ?>
                    </div>
                </div>
                <div class="info-item">
                    <div class="label">Email</div>
                    <div class="value">
                        <?= htmlspecialchars($user["email"]) ?>
                    </div>
                </div>
                <div class="info-item">
                    <div class="label">Phone</div>
                    <div class="value">
                        <?= htmlspecialchars($user["phone"]) ?>
                    </div>
                </div>
                <div class="info-item">
                    <div class="label">Address</div>
                    <div class="value">
                        <?= htmlspecialchars($user["address"]) ?>
                    </div>
                </div>
            </div>

            <a href="logout.php" class="btn-logout">🚪 Logout</a>

        </div>
    </div>

</body>

</html>