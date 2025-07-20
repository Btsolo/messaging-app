<?php
include 'database.php';

// Initialize variables
$feedback = "";
$messages = [];
$current_tab = $_POST['tab'] ?? $_GET['tab'] ?? 'register';

// Function to generate token
function generateToken() {
    return bin2hex(random_bytes(16));
}

// Handle POST requests based on action
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'register':
            $username = $_POST["username"];
            $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
            $token = generateToken();
            
            $stmt = $conn->prepare("INSERT INTO users (username, password, token) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $password, $token);
            
            if ($stmt->execute()) {
                $feedback = "<div class='success-message'>
                    <span class='check-icon'>✅</span>
                    <div class='success-text'>User registered successfully!</div>
                    <div class='token-label'>Your Token:</div>
                    <div class='token-value' id='userToken'>$token</div>
                    <button class='copy-btn' onclick=\"copyToken()\">Copy Token</button>
                </div>";
            } else {
                $feedback = "<div class='error-message'>❌ Error: " . $stmt->error . "</div>";
            }
            $stmt->close();
            $current_tab = 'register';
            break;
            
        case 'send_sms':
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
                $feedback = "<div class='success-message'>✅ Message sent to <strong>$success</strong> recipient(s) successfully.</div>";
            } else {
                $feedback = "<div class='error-message'>❌ Invalid token. Message not sent.</div>";
            }
            $stmt->close();
            $current_tab = 'send_sms';
            break;
            
        case 'view_messages':
            $token = $_POST["token"];
            if ($token) {
                $stmt = $conn->prepare("SELECT recipient, message, status, timestamp FROM messages WHERE sender = ? ORDER BY timestamp DESC");
                $stmt->bind_param("s", $token);
                $stmt->execute();
                $result = $stmt->get_result();
                $messages = $result->fetch_all(MYSQLI_ASSOC);
                $stmt->close();
                
                if (empty($messages)) {
                    $feedback = "<div class='info-message'>📭 No messages found for this token.</div>";
                }
            } else {
                $feedback = "<div class='error-message'>❌ Please enter a token.</div>";
            }
            $current_tab = 'view_messages';
            break;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messaging Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Additional styles for the dashboard */
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .dashboard-header {
            text-align: center;
            margin-bottom: 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        
        .dashboard-header h1 {
            margin: 0 0 10px 0;
            font-size: 2.5em;
        }
        
        .dashboard-header p {
            margin: 0;
            opacity: 0.9;
        }
        
        .tab-navigation {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .tab-btn {
            flex: 1;
            padding: 15px 20px;
            border: none;
            background: transparent;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .tab-btn:hover {
            background: #f8f9ff;
        }
        
        .tab-btn.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .tab-content {
            display: none;
            animation: fadeIn 0.3s ease-in;
        }
        
        .tab-content.active {
            display: block;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .success-message, .error-message, .info-message {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .success-message {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        
        .error-message {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        
        .info-message {
            background: #cce7ff;
            border: 1px solid #99d6ff;
            color: #004085;
        }
        
        .token-value {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            font-family: monospace;
            margin: 10px 0;
            word-break: break-all;
            font-weight: bold;
        }
        
        .copy-btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
        
        .copy-btn:hover {
            background: #218838;
        }
        
        .message-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .message-table th,
        .message-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        .message-table th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 600;
        }
        
        .message-table tr:hover {
            background: #f8f9ff;
        }
        
        .status {
            padding: 4px 8px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status.sent {
            background: #d4edda;
            color: #155724;
        }
        
        .card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .form-header {
            text-align: center;
            margin-bottom: 25px;
        }
        
        .form-header h3 {
            margin: 0 0 10px 0;
            color: #333;
        }
        
        .form-header p {
            margin: 0;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Main Header -->
        <div class="dashboard-header">
            <h1>📱 Messaging Dashboard</h1>
            <p>Manage your account, send messages, and view your message history all in one place</p>
        </div>
        
        <!-- Feedback Messages -->
        <?php if (!empty($feedback)) echo $feedback; ?>
        
        <!-- Tab Navigation -->
        <div class="tab-navigation">
            <button class="tab-btn <?= $current_tab == 'register' ? 'active' : '' ?>" onclick="switchTab('register')">
                👤 Register User
            </button>
            <button class="tab-btn <?= $current_tab == 'send_sms' ? 'active' : '' ?>" onclick="switchTab('send_sms')">
                📨 Send SMS
            </button>
            <button class="tab-btn <?= $current_tab == 'view_messages' ? 'active' : '' ?>" onclick="switchTab('view_messages')">
                📤 View Messages
            </button>
        </div>
        
        <!-- Register User Tab -->
        <div id="register" class="tab-content <?= $current_tab == 'register' ? 'active' : '' ?>">
            <div class="card">
                <div class="form-header">
                    <h3>👤 Register New User</h3>
                    <p>Create your account to get started with messaging</p>
                </div>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="register">
                    <input type="hidden" name="tab" value="register">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" placeholder="Enter your username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    </div>
                    <button type="submit" class="register-btn">Register Account</button>
                </form>
            </div>
        </div>
        
        <!-- Send SMS Tab -->
        <div id="send_sms" class="tab-content <?= $current_tab == 'send_sms' ? 'active' : '' ?>">
            <div class="card">
                <div class="form-header">
                    <h3>📨 Send Bulk SMS</h3>
                    <p>Send messages to multiple recipients using your token</p>
                </div>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="send_sms">
                    <input type="hidden" name="tab" value="send_sms">
                    <div class="form-group">
                        <label for="sms_token">Your Token</label>
                        <input type="text" id="sms_token" name="token" placeholder="Enter your token" required>
                    </div>
                    <div class="form-group">
                        <label for="recipients">Recipients (comma-separated)</label>
                        <textarea id="recipients" name="recipients" rows="3" placeholder="e.g. 0701234567,0712345678,0723456789" required></textarea>
                        <small>Separate multiple phone numbers with commas</small>
                    </div>
                    <div class="form-group">
                        <label for="message">Message Content</label>
                        <textarea id="message" name="message" rows="5" placeholder="Type your message here..." required></textarea>
                    </div>
                    <button type="submit" class="register-btn">Send Message</button>
                </form>
            </div>
        </div>
        
        <!-- View Messages Tab -->
        <div id="view_messages" class="tab-content <?= $current_tab == 'view_messages' ? 'active' : '' ?>">
            <div class="card">
                <div class="form-header">
                    <h3>📤 View Sent Messages</h3>
                    <p>Enter your token to view your message history</p>
                </div>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="view_messages">
                    <input type="hidden" name="tab" value="view_messages">
                    <div class="form-group">
                        <label for="view_token">Your Token</label>
                        <input type="text" id="view_token" name="token" placeholder="Enter your token" required>
                    </div>
                    <button type="submit" class="register-btn">View Messages</button>
                </form>
                
                <?php if (!empty($messages)): ?>
                    <table class="message-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Recipient</th>
                                <th>Message</th>
                                <th>Status</th>
                                <th>Timestamp</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($messages as $index => $msg): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars($msg['recipient']) ?></td>
                                    <td><?= htmlspecialchars(substr($msg['message'], 0, 50)) . (strlen($msg['message']) > 50 ? '...' : '') ?></td>
                                    <td><span class="status <?= strtolower($msg['status']) ?>"><?= htmlspecialchars($msg['status']) ?></span></td>
                                    <td><?= htmlspecialchars($msg['timestamp']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script>
        // Tab switching functionality
        function switchTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            
            // Remove active class from all tab buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Show selected tab content
            document.getElementById(tabName).classList.add('active');
            
            // Add active class to clicked tab button
            event.target.classList.add('active');
        }
        
        // Copy token functionality
        function copyToken() {
            var tokenText = document.getElementById('userToken').innerText;
            navigator.clipboard.writeText(tokenText).then(function() {
                // Change button text temporarily
                const btn = event.target;
                const originalText = btn.innerText;
                btn.innerText = 'Copied!';
                btn.style.background = '#218838';
                
                setTimeout(() => {
                    btn.innerText = originalText;
                    btn.style.background = '#28a745';
                }, 2000);
            }, function(err) {
                alert('Failed to copy token: ' + err);
            });
        }
        
        // Auto-switch to relevant tab after form submission
        document.addEventListener('DOMContentLoaded', function() {
            // If there are messages to display, ensure the view_messages tab is active
            <?php if (!empty($messages)): ?>
                switchTabProgrammatically('view_messages');
            <?php endif; ?>
        });
        
        function switchTabProgrammatically(tabName) {
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            document.getElementById(tabName).classList.add('active');
            document.querySelector(`.tab-btn[onclick="switchTab('${tabName}')"]`).classList.add('active');
        }
    </script>
</body>
</html>