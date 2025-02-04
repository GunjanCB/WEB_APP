User Management Web App 🎯💻🚀
Overview 🌟📌🔍
This project is a PHP-based web application designed for user authentication, profile management, and CRUD operations.
Users can register, log in, update their profile, and delete their account.

🔹 Key Features
✅ User Authentication: Secure login & registration with session management.
✅ Profile Management: Update profile details and upload profile pictures.
✅ CRUD Operations: Create, Read, Update, and Delete user data.
✅ Database Integration: Uses MySQL with prepared statements for security.
✅ Responsive Design: Works on desktop & mobile devices.

🚀 Features
🔑 User Authentication
Secure login/logout using PHP sessions.
Hashed passwords for security.
Validation for email, phone, and username uniqueness.
Redirects to homepage after successful login.
Prevents unauthorized access.
👤 Profile Management
Displays user details: Name, Email, Phone, Username, Profile Picture.
Users can update profile information.
Profile picture upload with file validation.
Users can delete their account permanently.
🛠️ CRUD Operations
✔ Create: Users can register and create a profile.
✔ Read: Profile details are retrieved securely.
✔ Update: Users can edit their details and update profile pictures.
✔ Delete: Users can remove their account permanently.

📊 Database Integration
Uses MySQL to store user data.
Prepared statements prevent SQL injection.
Profile pictures are stored in uploads/.
📱 Responsive Design
Works on mobile, tablet, and desktop.
Modern UI with a gradient background for aesthetics.
📁 Project File Structure
bash
Copy
Edit
web_app/
│── index.php # Homepage
│── login.php # User login page
│── register.php # User registration page
│── profile.php # User profile page
│── update_profile.php # Profile update functionality
│── delete_account.php # User account deletion
│── logout.php # Logout functionality
│── db.php # Database connection
│── header.php # Navigation bar
│── footer.php # Footer section
│── uploads/ # Stores user profile pictures
│── style.css # Stylesheet
│── README.md # Documentation file
🛢️ Database Schema
This project uses a users table to store user details.

sql
Copy
Edit
CREATE TABLE users (
id INT AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(50) UNIQUE NOT NULL,
first_name VARCHAR(50) NOT NULL,
last_name VARCHAR(50) NOT NULL,
email VARCHAR(100) UNIQUE NOT NULL,
phone VARCHAR(15) NOT NULL,
password VARCHAR(255) NOT NULL,
profile_photo VARCHAR(255) DEFAULT 'uploads/default-avatar.png'
);
🔧 Installation & Setup
1️⃣ Install XAMPP
Download and install XAMPP from Apache Friends.
Start Apache and MySQL from the XAMPP Control Panel.
2️⃣ Setup the Project
Move the project folder (web_app/) to htdocs/ (inside XAMPP).
Open phpMyAdmin and create a database (e.g., web_app_db).
Import the provided database.sql file.
Update db.php with your database credentials:
php
Copy
Edit
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "web_app_db";
3️⃣ Run the Application
Open your browser.
Navigate to http://localhost/web_app/index.php.
Register a new user and explore the app!
📄 Pages & Functionality
🏠 index.php (Homepage)
Welcome message with login/register buttons.
Displays user profile & logout options when logged in.
🔑 login.php (Login Page)
Users log in with email & password.
Redirects to profile page after successful login.
📝 register.php (Registration Page)
Users register with full details & profile photo.
Redirects to login after successful registration.
👤 profile.php (Profile Page)
Displays user information & profile picture.
Edit profile and delete account options.
⚙️ update_profile.php (Update Profile)
Allows editing personal details & changing profile photo.
Uses AJAX for smooth updates.
🗑️ delete_account.php (Delete Account)
Permanently removes user data & profile picture.
Redirects to registration page after deletion.
🔓 logout.php (Logout)
Ends the session securely and redirects to login.
🛠️ Troubleshooting
Database Connection Issues
Ensure MySQL is running in XAMPP.
Verify db.php contains correct database credentials.
JavaScript Errors
Check browser console (F12 → Console in Chrome).
Ensure JavaScript files are correctly linked.
PHP Debugging
Enable error reporting for troubleshooting:

php
Copy
Edit
error_reporting(E_ALL);
ini_set('display_errors', 1);
🚀 Future Improvements
Password reset functionality via email.
Admin panel for user management.
Two-factor authentication for security.
Bootstrap or Tailwind CSS for a better UI.
✍️ Author
Gunjan Chandra Bhattacharya
📌 Course: Web Applications Programming (ITS64504)

📜 License
This project is for educational purposes only.
