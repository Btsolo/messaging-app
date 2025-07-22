<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messaging Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {-
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: #f0f2f5;
            height: 100vh;
            overflow: hidden;
        }

        .dashboard-container {
            display: flex;
            height: 100vh;
        }

        /* Left Sidebar */
        .left-sidebar {
            width: 320px;
            background: white;
            border-right: 1px solid #e4e6ea;
            display: flex;
            flex-direction: column;
        }

        .sidebar-header {
            padding: 16px 20px;
            border-bottom: 1px solid #e4e6ea;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar-header h2 {
            font-size: 24px;
            font-weight: 700;
            color: #050505;
        }

        .header-icons {
            display: flex;
            gap: 8px;
        }

        .icon-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #f0f2f5;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #65676b;
            font-size: 16px;
            transition: background 0.2s;
        }

        .icon-btn:hover {
            background: #e4e6ea;
        }

        .sidebar-description {
            padding: 16px 20px;
            border-bottom: 1px solid #e4e6ea;
        }

        .sidebar-description p {
            color: #65676b;
            font-size: 14px;
            line-height: 1.4;
        }

        .navigation-menu {
            padding: 16px 12px;
            flex: 1;
        }

        .nav-btn {
            width: 100%;
            display: flex;
            align-items: center;
            padding: 12px 16px;
            border: none;
            background: transparent;
            cursor: pointer;
            font-size: 15px;
            font-weight: 500;
            transition: all 0.2s ease;
            border-radius: 8px;
            margin-bottom: 4px;
            color: #050505;
            text-align: left;
            gap: 12px;
        }

        .nav-btn:hover {
            background: #f0f2f5;
        }

        .nav-btn.active {
            background: #e7f3ff;
            color: #0084ff;
        }

        .nav-icon {
            font-size: 18px;
            width: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: white;
        }

        .content-header {
            padding: 16px 24px;
            border-bottom: 1px solid #e4e6ea;
            background: white;
        }

        .content-header h3 {
            font-size: 20px;
            font-weight: 600;
            color: #050505;
            margin-bottom: 4px;
        }

        .content-header p {
            color: #65676b;
            font-size: 14px;
        }

        .content-area {
            flex: 1;
            background: #f8f9fa;
            overflow-y: auto;
            padding: 24px;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .form-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #e4e6ea;
            max-width: 600px;
            margin: 0 auto;
        }

        .form-row {
            display: flex;
            gap: 16px;
            align-items: end;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group.flex-1 {
            flex: 1;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #050505;
            font-size: 14px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e4e6ea;
            border-radius: 8px;
            font-size: 15px;
            background: #f8f9fa;
            transition: all 0.2s;
            font-family: inherit;
            resize: vertical;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #0084ff;
            background: white;
            box-shadow: 0 0 0 3px rgba(0, 132, 255, 0.1);
        }

        .form-group small {
            color: #65676b;
            font-size: 12px;
            margin-top: 6px;
            display: block;
        }

        .submit-btn {
            width: 100%;
            padding: 14px;
            background: #0084ff;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .submit-btn:hover {
            background: #0066cc;
            transform: translateY(-1px);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        /* Messages Table */
        .messages-table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #e4e6ea;
            margin-top: 24px;
            overflow: hidden;
            display: none;
        }

        .messages-table {
            width: 100%;
            border-collapse: collapse;
        }

        .messages-table th,
        .messages-table td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid #e4e6ea;
            font-size: 14px;
        }

        .messages-table th {
            background: #f8f9fa;
            color: #050505;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .messages-table tr:hover {
            background: #f8f9fa;
        }

        .messages-table tr:last-child td {
            border-bottom: none;
        }

        .status {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: #d1f2eb;
            color: #0e5233;
        }

        /* Feedback Messages */
        .success-message {
            background: #d1f2eb;
            border: 1px solid #a3e4d7;
            color: #0e5233;
            padding: 16px 24px;
            border-radius: 8px;
            margin: 16px 24px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .error-message {
            background: #fadbd8;
            border: 1px solid #f1948a;
            color: #922b21;
            padding: 16px 24px;
            border-radius: 8px;
            margin: 16px 24px;
            text-align: center;
        }

        .info-message {
            background: #d6eaf8;
            border: 1px solid #aed6f1;
            color: #1b4f72;
            padding: 16px 24px;
            border-radius: 8px;
            margin: 16px 24px;
            text-align: center;
        }

        .check-icon {
            font-size: 24px;
        }

        .success-text {
            font-weight: 600;
            font-size: 16px;
        }

        .token-label {
            font-weight: 500;
            margin-top: 8px;
        }

        .token-value {
            background: #f8f9fa;
            padding: 12px 16px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            margin: 8px 0;
            word-break: break-all;
            font-size: 13px;
            border: 1px solid #e4e6ea;
            min-width: 300px;
            text-align: center;
        }

        .copy-btn {
            background: #16a34a;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            transition: background 0.2s;
        }

        .copy-btn:hover {
            background: #15803d;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .dashboard-container {
                flex-direction: column;
            }
            
            .left-sidebar {
                width: 100%;
                height: auto;
            }
            
            .navigation-menu {
                display: flex;
                gap: 8px;
                overflow-x: auto;
                padding: 12px;
            }
            
            .nav-btn {
                white-space: nowrap;
                margin-bottom: 0;
                min-width: 150px;
            }
            
            .form-row {
                flex-direction: column;
            }
            
            .content-area {
                padding: 16px;
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Left Sidebar -->
        <div class="left-sidebar">
            <!-- Sidebar Header -->
            <div class="sidebar-header">
                <h2>📱 Messaging Dashboard</h2>
                <div class="header-icons">
                    <button class="icon-btn">⋯</button>
                    <button class="icon-btn">⚙️</button>
                </div>
            </div>
            <div class="sidebar-description">
                <p>Manage your account, send messages, and view your message history</p>
            </div>
            <!-- Navigation Menu -->
            <div class="navigation-menu">
                <button class="nav-btn active" onclick="switchTab('register')">
                    <span class="nav-icon">👤</span>
                    Register User
                </button>
                <button class="nav-btn" onclick="switchTab('send_sms')">
                    <span class="nav-icon">📨</span>
                    Send SMS
                </button>
                <button class="nav-btn" onclick="switchTab('view_messages')">
                    <span class="nav-icon">📤</span>
                    View Messages
                </button>
            </div>
        </div>
        <!-- Main Content -->
        <div class="main-content">
            <!-- Content Header -->
            <div class="content-header">
                <h3 id="content-title">Register New User</h3>
                <p id="content-description">Create your account to get started with messaging</p>
            </div>

            <!-- Feedback Messages -->
            <div id="feedback-container"></div>

            <!-- Main Content Area -->
            <div class="content-area">
                <!-- Register User Tab -->
                <div id="register" class="tab-content active">
                    <div class="form-card">
                        <form id="registerForm">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" id="username" name="username" placeholder="Enter your username" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" id="password" name="password" placeholder="Enter your password" required>
                            </div>
                            <button type="submit" class="submit-btn">Register Account</button>
                        </form>
                    </div>
                </div>

                <!-- Send SMS Tab -->
                <div id="send_sms" class="tab-content">
                    <div class="form-card">
                        <form id="smsForm">
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
                                <textarea id="message" name="message" rows="4" placeholder="Type your message here..." required></textarea>
                            </div>
                            <button type="submit" class="submit-btn">Send Message</button>
                        </form>
                    </div>
                </div>
               <!-- View Messages Tab -->
                <div id="view_messages" class="tab-content">
                    <div class="form-card">
                        <form id="viewForm">
                            <div class="form-row">
                                <div class="form-group flex-1">
                                    <label for="view_token">Your Token</label>
                                    <input type="text" id="view_token" name="token" placeholder="Enter your token" required>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="submit-btn">View Messages</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div id="messages-container" class="messages-table-container">
                        <table class="messages-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Recipient</th>
                                    <th>Message</th>
                                    <th>Status</th>
                                    <th>Timestamp</th>
                                </tr>
                            </thead>
                            <tbody id="messages-tbody">
                                <!-- Messages will be populated here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Sample data for demonstration
        const users = [];
        const messages = [];
        let currentUser = null;

        // Tab switching functionality
        function switchTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            
            // Remove active class from all nav buttons
            document.querySelectorAll('.nav-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Show selected tab content
            document.getElementById(tabName).classList.add('active');
            
            // Add active class to clicked nav button
            event.target.classList.add('active');
            
            // Update content header
            updateContentHeader(tabName);
            
            // Clear feedback
            clearFeedback();
        }

        function updateContentHeader(tabName) {
            const titles = {
                'register': 'Register New User',
                'send_sms': 'Send Bulk SMS',
                'view_messages': 'View Sent Messages'
            };
            
            const descriptions = {
                'register': 'Create your account to get started with messaging',
                'send_sms': 'Send messages to multiple recipients using your token',
                'view_messages': 'Enter your token to view your message history'
            };
            
            document.getElementById('content-title').textContent = titles[tabName];
            document.getElementById('content-description').textContent = descriptions[tabName];
        }

        // Generate random token
        function generateToken() {
            return Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
        }

        // Show feedback message
        function showFeedback(message, type = 'success') {
            const container = document.getElementById('feedback-container');
            container.innerHTML = `<div class="${type}-message">${message}</div>`;
            
            // Auto-hide after 5 seconds for non-success messages
            if (type !== 'success') {
                setTimeout(() => {
                    clearFeedback();
                }, 5000);
            }
        }

        function clearFeedback() {
            document.getElementById('feedback-container').innerHTML = '';
        }

        // Copy token functionality
        function copyToken() {
            const tokenText = document.getElementById('userToken').innerText;
            navigator.clipboard.writeText(tokenText).then(() => {
                const btn = event.target;
                const originalText = btn.innerText;
                btn.innerText = 'Copied!';
                btn.style.background = '#22c55e';
                
                setTimeout(() => {
                    btn.innerText = originalText;
                    btn.style.background = '#16a34a';
                }, 2000);
            }, (err) => {
                showFeedback('❌ Failed to copy token', 'error');
            });
        }

        // Form handlers
        document.getElementById('registerForm').addEventListener('submit', (e) => {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const username = formData.get('username');
            const password = formData.get('password');
            
            // Check if user already exists
            if (users.find(user => user.username === username)) {
                showFeedback('❌ Username already exists', 'error');
                return;
            }
            
            // Create new user
            const token = generateToken();
            const newUser = {
                id: users.length + 1,
                username: username,
                password: password, // In real app, this would be hashed
                token: token
            };
            
            users.push(newUser);
            currentUser = newUser;
            
            // Show success message with token
            const successMessage = `
                <span class="check-icon">✅</span>
                <div class="success-text">User registered successfully!</div>
                <div class="token-label">Your Token:</div>
                <div class="token-value" id="userToken">${token}</div>
                <button class="copy-btn" onclick="copyToken()">Copy Token</button>
            `;
            
            showFeedback(successMessage, 'success');
            
            // Clear form
            e.target.reset();
        });

        document.getElementById('smsForm').addEventListener('submit', (e) => {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const token = formData.get('token');
            const message = formData.get('message');
            const recipients = formData.get('recipients').split(',').map(r => r.trim());
            
            // Validate token
            const user = users.find(u => u.token === token);
            if (!user) {
                showFeedback('❌ Invalid token. Message not sent.', 'error');
                return;
            }
            
            // Validate recipients
            if (recipients.length === 0 || recipients[0] === '') {
                showFeedback('❌ Please enter at least one recipient.', 'error');
                return;
            }
            
            // Add messages to storage
            recipients.forEach(recipient => {
                if (recipient) {
                    messages.push({
                        id: messages.length + 1,
                        sender: token,
                        recipient: recipient,
                        message: message,
                        status: 'SENT',
                        timestamp: new Date().toLocaleString()
                    });
                }
            });
            
            showFeedback(`✅ Message sent to <strong>${recipients.length}</strong> recipient(s) successfully.`, 'success');
            
            // Clear form
            e.target.reset();
        });

        document.getElementById('viewForm').addEventListener('submit', (e) => {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const token = formData.get('token');
            
            if (!token) {
                showFeedback('❌ Please enter a token.', 'error');
                return;
            }
            
            // Find messages for this token
            const userMessages = messages.filter(msg => msg.sender === token);
            
            if (userMessages.length === 0) {
                showFeedback('📭 No messages found for this token.', 'info');
                document.getElementById('messages-container').style.display = 'none';
                return;
            }
            
            // Display messages
            displayMessages(userMessages);
            clearFeedback();
        });

        function displayMessages(userMessages) {
            const tbody = document.getElementById('messages-tbody');
            tbody.innerHTML = '';
            
            userMessages.forEach((msg, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${msg.recipient}</td>
                    <td>${msg.message.length > 50 ? msg.message.substring(0, 50) + '...' : msg.message}</td>
                    <td><span class="status">${msg.status}</span></td>
                    <td>${msg.timestamp}</td>
                `;
                tbody.appendChild(row);
            });
            
            document.getElementById('messages-container').style.display = 'block';
        }

        // Initialize with some demo data
        function initializeDemoData() {
            // Add a demo user
            const demoToken = 'demo123token456';
            users.push({
                id: 1,
                username: 'demo_user',
                password: 'demo123',
                token: demoToken
            });
            
            // Add some demo messages
            const demoMessages = [
                {
                    id: 1,
                    sender: demoToken,
                    recipient: '0701234567',
                    message: 'Hello, this is a test message from the messaging dashboard.',
                    status: 'SENT',
                    timestamp: new Date(Date.now() - 3600000).toLocaleString()
                },
                {
                    id: 2,
                    sender: demoToken,
                    recipient: '0712345678',
                    message: 'Another test message to demonstrate the functionality.',
                    status: 'SENT',
                    timestamp: new Date(Date.now() - 1800000).toLocaleString()
                },
                {
                    id: 3,
                    sender: demoToken,
                    recipient: '0723456789',
                    message: 'Third message for testing the view messages feature.',
                    status: 'SENT',
                    timestamp: new Date().toLocaleString()
                }
            ];
            
            messages.push(...demoMessages);
        }

        // Initialize demo data when page loads
        document.addEventListener('DOMContentLoaded', () => {
            initializeDemoData();
            
            // Show demo info
            setTimeout(() => {
                showFeedback('💡 Demo token available: <strong>demo123token456</strong> (try it in Send SMS or View Messages)', 'info');
            }, 1000);
        });
    </script>
</body>
</html>
