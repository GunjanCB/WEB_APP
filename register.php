<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include('db.php');

$registerError = "";
$registerMessage = "";

// Handle Registration
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $username = $_POST['register_username'];
    $firstName = $_POST['register_firstname'];
    $lastName = $_POST['register_lastname'];
    $email = $_POST['register_email'];
    $phone = $_POST['register_phone'];
    $password = $_POST['register_password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

     // Profile photo upload
    $profilePhoto = NULL;
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    $maxFileSize = 2 * 1024 * 1024; // 2MB

    if (!empty($_FILES['register_photo']['name'])) {
        $photoTmpPath = $_FILES['register_photo']['tmp_name'];
        $photoName = uniqid() . '_' . basename($_FILES['register_photo']['name']);
        $uploadDirectory = "uploads/";

        // Ensure uploads directory exists
        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0777, true);
        }

        // Validate file type and size
        $fileExtension = strtolower(pathinfo($photoName, PATHINFO_EXTENSION));
        if (!in_array($fileExtension, $allowedExtensions)) {
            $registerError = "Only JPG, JPEG, PNG & GIF files are allowed.";
        } elseif ($_FILES['register_photo']['size'] > $maxFileSize) {
            $registerError = "File size must be less than 2MB.";
        } else {
            // Move uploaded file
            $photoDestination = $uploadDirectory . $photoName;
            if (move_uploaded_file($photoTmpPath, $photoDestination)) {
                $profilePhoto = $photoDestination;
            } else {
                $registerError = "File upload failed.";
            }
        }
    }

    // Check if email already exists
    $checkQuery = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $registerError = "Email already registered! Try logging in.";
    } else {
        // Insert user data into database
        $query = "INSERT INTO users (username, first_name, last_name, email, phone, password, profile_photo) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssss", $username, $firstName, $lastName, $email, $phone, $hashedPassword, $profilePhoto);

        if ($stmt->execute()) {
            echo "<script>
                    alert('Registration successful! Please log in.');
                    window.location.href = 'login.php';
                  </script>";
            exit();
        } else {
            $registerError = "Error: " . $conn->error;
        }
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include('header.php'); ?>

<main>
    <h2>Register</h2>
    <?php if (!empty($registerError)) echo "<p class='error'>$registerError</p>"; ?>
    <form action="register.php" method="post" enctype="multipart/form-data">
        <label>Username:</label>
        <input type="text" name="register_username" required>
        <label>First Name:</label>
        <input type="text" name="register_firstname" required>
        <label>Last Name:</label>
        <input type="text" name="register_lastname" required>
        <label>Email:</label>
        <input type="email" name="register_email" required>
        <label>Phone:</label>
        <input type="text" name="register_phone" required>
        <label>Password:</label>
        <input type="password" name="register_password" required>
        <label>Profile Photo:</label>
        <input type="file" name="register_photo">
        <button type="submit" name="register">Register</button>
    </form>
</main>

<?php include('footer.php'); ?>

</body>
</html>
