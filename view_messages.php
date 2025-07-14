<?php
include 'database.php';
// Check if the token is provided
$token = $_GET['token'] ?? $_POST['token'] ?? null;
$messages = [];
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && !$token) {
    $error = "Please enter a token.";
}

if ($token) {
    $stmt = $conn->prepare("SELECT recipient_number, message, status, timestamp FROM messages WHERE sender = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $messages = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sent Messages</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>📤 Sent Messages</h2>
            <p>Messages sent using your token</p>
        </div>
        <div class="container">
    <div class="header">
        <h2>📬 View Sent Messages</h2>
        <p>Enter your token to view your sent messages</p>
    </div>

    <?php if (!$token): ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="token">Token</label>
                <input type="text" id="token" name="token" placeholder="Enter your token" required>
            </div>
            <button type="submit" class="register-btn">View Messages</button>
        </form>
        <?php if (!empty($error)) echo "<div class='error-message'>$error</div>"; ?>
    </div>
</body>
</html>
<?php exit; endif; ?>
<?php if (!empty($messages)): ?>
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
<?php else: ?>
    <p>No messages found for this token.</p>
<?php endif; ?>


        <?php if (!empty($messages)) : ?>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Recipient</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($messages as $index => $msg): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo htmlspecialchars($msg['recipient']); ?></td>
                            <td><?php echo htmlspecialchars($msg['message']); ?></td>
                            <td>
                                <span class="status <?php echo strtolower($msg['status']); ?>">
                                    <?php echo strtoupper($msg['status']); ?>
                                </span>
                            </td>
                            <td><?php echo $msg['timestamp']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <div class="error-message">No messages found for the given token.</div>
        <?php endif; ?>
    </div>
</body>
</html>



