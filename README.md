# Messaging App

A simple web-based messaging system built with PHP, HTML, CSS, and JavaScript. This app allows users to register, send SMS messages, and view sent messages through a user-friendly dashboard interface.

## Features

- **User Registration:** Register new users with unique tokens.
- **Send SMS:** Send messages to registered users.
- **View Messages:** See a table of all sent messages.
- **Responsive Design:** Works well on desktop and mobile devices.
- **Feedback Messages:** Success and error notifications for user actions.

## Folder Structure

```
messaging-app/
│
├── dashboard.php         # Main dashboard and frontend
├── register_user.php     # Backend script for user registration
├── send_sms.php          # Backend script for sending SMS
├── view_messages.php     # Backend script for viewing messages
├── database.php          # Database connection and setup
├── style.css             # Additional CSS styles (if any)
├── test_db.php           # (Optional) Script to test database connection
└── README.md             # This file
```

## Requirements

- [XAMPP](https://www.apachefriends.org/) or any PHP server
- PHP 7.0 or higher
- MySQL (for storing users and messages)
- Modern web browser

## Setup Instructions

1. **Clone or Download the Repository**
   - Place the `messaging-app` folder in your XAMPP `htdocs` directory:
     ```
     C:\xampp\htdocs\messaging-app
     ```

2. **Start XAMPP**
   - Open XAMPP Control Panel.
   - Start **Apache** and **MySQL**.

3. **Database Setup**
   - Import the provided SQL file (if available) into phpMyAdmin to create the necessary tables.
   - Or, open `database.php` and follow any setup instructions.

4. **Access the App**
   - Open your browser and go to:
     ```
     http://localhost/messaging-app/dashboard.php
     ```

## Usage

- **Register User:** Fill out the registration form to create a new user and receive a unique token.
- **Send SMS:** Use the token to send a message to a registered user.
- **View Messages:** See all sent messages in a table.

## Notes

- Make sure all required PHP files are present in the project folder.
- If you encounter errors, check your database connection settings in `database.php`.
-
