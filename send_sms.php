<?php
echo "<pre>";
print_r($_POST);
echo "</pre>";

include 'database.php';

function generateToken() {
    return bin2hex(random_bytes(16)); // 32-character token
}

$message = "";
$token = $_GET['token'] ?? $_POST['token'] ?? null;
$messages = [];
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $token = generateToken();

    $stmt = $conn->prepare("INSERT INTO users (username, password, token) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $token);

    if ($stmt->execute()) {
        $message = "<div class='token-box'>
                        <span class='check-icon'>&#x2705;</span>
                        <div class='success-text'>User registered successfully!</div>
                        <div class='token-label'>Your Token:</div>
                        <div class='token-value' id='copyToken'>$token</div>
                        <button class='copy-btn' onclick='copyToken()'>📋 Copy Token</button>
                    </div>";
    } else {
        $message = "<div class='error-message'>❌ Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['view'])) {
    if (!$token) {
        $error = "Please enter a token.";
    } else {
        $filterRecipient = $_POST['recipient'] ?? '';
        $filterDate = $_POST['date'] ?? '';

        $query = "SELECT recipient_number, message, status, timestamp FROM messages WHERE sender = ?";
        $types = "s";
        $params = [$token];

        if (!empty($filterRecipient)) {
            $query .= " AND recipient_number LIKE ?";
            $types .= "s";
            $params[] = "%$filterRecipient%";
        }
        if (!empty($filterDate)) {
            $query .= " AND DATE(timestamp) = ?";
            $types .= "s";
            $params[] = $filterDate;
        }

        $stmt = $conn->prepare($query);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        $messages = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Portal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <?php if (!empty($message)) echo $message; ?>

    <div class="header">
        <h2>Register New User</h2>
        <p>Create your account to get started</p>
    </div>

    <form method="POST" action="">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Enter your username" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
        </div>

        <button type="submit" class="register-btn" name="register">Register</button>
    </form>

    <hr>

    <div class="header">
        <h2>📬 View Sent Messages</h2>
        <p>Enter your token to view your sent messages</p>
    </div>

    <form method="POST" action="">
        <div class="form-group">
            <label for="token">Token</label>
            <input type="text" id="token" name="token" placeholder="Enter your token" value="<?= htmlspecialchars($token ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="recipient">Filter by Recipient</label>
            <input type="text" id="recipient" name="recipient" placeholder="Recipient phone (optional)">
        </div>

        <div class="form-group">
            <label for="date">Filter by Date</label>
            <input type="date" id="date" name="date">
        </div>

        <button type="submit" class="register-btn" name="view">View Messages</button>
        <a href="index.php" class="back-btn">🏠 Back to Home</a>
    </form>

    <?php if (!empty($error)): ?>
        <div class="error-message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (!empty($messages)): ?>
        <div class="form-group">
            <h3>Sent Messages:</h3>
            <table class="message-table">
                <thead>
                    <tr>
                        <th>Recipient</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($messages as $msg): ?>
                        <tr>
                            <td><?= htmlspecialchars($msg['recipient_number']) ?></td>
                            <td><?= htmlspecialchars($msg['message']) ?></td>
                            <td class="<?= strtolower($msg['status']) ?>"><?= htmlspecialchars($msg['status']) ?></td>
                            <td><?= htmlspecialchars($msg['timestamp']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php elseif ($token && empty($messages)): ?>
        <p>No messages found for this token.</p>
    <?php endif; ?>
</div>

<script>
function copyToken() {
    const token = document.getElementById('copyToken').innerText;
    navigator.clipboard.writeText(token).then(() => {
        alert("✅ Token copied to clipboard!");
    }).catch(() => {
        alert("❌ Failed to copy token.");
    });
}
</script>
</body>
</html>
