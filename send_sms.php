<?php
 include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST["token"];
    $message = $_POST["message"];
    $recipients = explode(",", $_POST["recipients"]);

    $stmt = $conn->prepare("SELECT * FROM users WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $success = 0;
        foreach ($recipients as $number) {
            $number = trim($number);

            $insert = $conn->prepare("INSERT INTO messages (sender, recipient, message, status, timestamp) VALUES (?, ?, ?, 'SENT', NOW())");
            $insert->bind_param('sss', $token, $number, $message);

            if ($insert->execute()) {
                $success++;
            }
        }
        $feedback = "<div class='token-box'>✅ Message sent to <strong>$success</strong> recipient(s) successfully.</div>";
    } else {
        $feedback = "<div class='error-message'>❌ Invalid token. Message not sent.</div>";
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
    <title>Send Bulk SMS</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <?php if (!empty($feedback)) echo $feedback; ?>

        <div class="header">
            <h2>📨 Send Bulk SMS</h2>
            <p>Distribute messages to multiple recipients using your token</p>
        </div>

        <form method="POST" action="">
            <div class="form-group">
                <label for="token">Token</label>
                <input type="text" id="token" name="token" placeholder="Enter your token" required>
            </div>

            <div class="form-group">
                <label for="recipients">Recipients (comma-separated)</label>
                <textarea id="recipients" name="recipients" rows="3" placeholder="e.g. 0701234567,0712345678" required></textarea>
            </div>

            <div class="form-group">
                <label for="message">Message</label>
                <textarea id="message" name="message" rows="5" placeholder="Type your message here..." required></textarea>
            </div>

            <button type="submit" class="register-btn">Send Message</button>
        </form>
    </div>
</body>
</html>