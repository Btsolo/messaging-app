<?php
include 'database.php';

function generateToken() {
    return bin2hex(random_bytes(16)); // 32-character token
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $token = generateToken();

    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (username, password, token) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $token);

    if ($stmt->execute()) {
        // --- Begin: Success message with copy button ---
        echo "<div class='token-box'>
                <span class='check-icon'>✅</span>
                <div class='success-text'>User registered successfully!</div>
                <div class='token-label'>Your Token:</div>
                <div class='token-value' id='userToken'>$token</div>
                <button class='copy-btn' onclick=\"copyToken()\">Copy Token</button>
              </div>";
        // --- End: Success message with copy button ---
    } else {
        echo "<div class='error-message'>❌ Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <!-- --- Begin: Move CSS to style.css --- -->
    <link rel="stylesheet" href="style.css">
    <!-- --- End: Move CSS to style.css --- -->
    <script>
    // --- Begin: Copy Token Button Script ---
    function copyToken() {
        var tokenText = document.getElementById('userToken').innerText;
        navigator.clipboard.writeText(tokenText).then(function() {
            alert('Token copied to clipboard!');
        }, function(err) {
            alert('Failed to copy token: ' + err);
        });
    }
    // --- End: Copy Token Button Script ---
    </script>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h2>Register New User</h2>
            <p>Create your account to get started</p>
        </div>

        <!-- Registration Form -->
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <div class="input-icon username-input">
                    <input type="text" id="username" name="username" placeholder="Enter your username" required>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-icon password-input">
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
            </div>

            <button type="submit" class="register-btn">Register</button>
        </form>
    </div>
</body>
</html>
