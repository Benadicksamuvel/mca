<?php
session_start();
require_once 'db.php';

$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $password = trim($_POST["password"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $address = trim($_POST["address"] ?? "");
    $email = trim($_POST["email"] ?? "");

    // --- Server-side validation ---
    if ($name === "")
        $errors[] = "Name is required.";
    if (strlen($password) < 4)
        $errors[] = "Password must be at least 4 characters.";
    if (!preg_match('/^[0-9]{7,15}$/', $phone))
        $errors[] = "Enter a valid phone number (7–15 digits).";
    if ($address === "")
        $errors[] = "Address is required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors[] = "Enter a valid email address.";

    if (empty($errors)) {
        // Check duplicate email
        $stmt = $conn->prepare("SELECT user_id FROM register WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "An account with this email already exists.";
        } else {
            $insertStmt = $conn->prepare(
                "INSERT INTO register (name, password, phone, address, email) VALUES (?, ?, ?, ?, ?)"
            );
            $insertStmt->bind_param("sssss", $name, $password, $phone, $address, $email);

            if ($insertStmt->execute()) {
                $success = "Registration successful! <a href='index.php'>Login here &rarr;</a>";
            } else {
                $errors[] = "Registration failed. Please try again.";
            }
            $insertStmt->close();
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
    <title>Register – Book Inventory</title>
    <meta name="description" content="Create your Book Inventory account." />
    <link rel="stylesheet" href="css/style.css" />
</head>

<body>
    <div class="auth-wrapper">
        <div class="auth-card">

            <div class="brand">
                <div class="brand-icon">📚</div>
                <h1>Book Inventory</h1>
                <p>Create your account</p>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <span>⚠️</span>
                    <div>
                        <?php foreach ($errors as $err)
                            echo htmlspecialchars($err) . "<br>"; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">✅
                    <?= $success ?>
                </div>
            <?php endif; ?>

            <form id="registerForm" method="POST" action="register.php" novalidate>

                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" placeholder="John Doe"
                        value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" />
                    <span class="field-error" id="name_error"></span>
                </div>

                <div class="form-group">
                    <label for="password">Password <span style="color:#64748b;font-size:11px;">(min 4
                            chars)</span></label>
                    <input type="password" id="password" name="password" placeholder="••••••••" />
                    <span class="field-error" id="password_error"></span>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" placeholder="9876543210"
                        value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" />
                    <span class="field-error" id="phone_error"></span>
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea id="address" name="address"
                        placeholder="123 Main St, City, State"><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
                    <span class="field-error" id="address_error"></span>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="john@example.com"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" />
                    <span class="field-error" id="email_error"></span>
                </div>

                <button type="submit" class="btn-primary">Create Account</button>
            </form>

            <div class="auth-switch">
                Already have an account? <a href="index.php">Sign in</a>
            </div>
        </div>
    </div>

    <script src="js/validation.js"></script>
</body>

</html>