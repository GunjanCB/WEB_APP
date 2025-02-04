<?php
session_start();
include('db.php');

// Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch current user data
$query = "SELECT username, first_name, last_name, email, phone, profile_photo FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $firstName, $lastName, $email, $phone, $profilePhoto);
$stmt->fetch();
$stmt->close();

$updateError = "";

// Handle Profile Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $newFirstName = trim($_POST['first_name']);
    $newLastName = trim($_POST['last_name']);
    $newEmail = trim($_POST['email']);
    $newPhone = trim($_POST['phone']);

    // Validate email format
    if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
        $updateError = "Invalid email format.";
    }

    // Profile Photo Upload
    $newProfilePhoto = $profilePhoto; // Keep old photo if no new one is uploaded
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    $maxFileSize = 2 * 1024 * 1024; // 2MB

    if (!empty($_FILES['profile_photo']['name'])) {
        $photoTmpPath = $_FILES['profile_photo']['tmp_name'];
        $photoName = uniqid() . '_' . basename($_FILES['profile_photo']['name']);
        $uploadDirectory = "uploads/";

        // Ensure the uploads directory exists
        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0777, true);
        }

        // Validate file type and size
        $fileExtension = strtolower(pathinfo($photoName, PATHINFO_EXTENSION));
        if (!in_array($fileExtension, $allowedExtensions)) {
            $updateError = "Only JPG, JPEG, PNG & GIF files are allowed.";
        } elseif ($_FILES['profile_photo']['size'] > $maxFileSize) {
            $updateError = "File size must be less than 2MB.";
        } else {
            // Delete old profile photo if exists
            if ($profilePhoto && file_exists($profilePhoto)) {
                unlink($profilePhoto);
            }

            // Move uploaded file
            $photoDestination = $uploadDirectory . $photoName;
            if (move_uploaded_file($photoTmpPath, $photoDestination)) {
                $newProfilePhoto = $photoDestination;
            } else {
                $updateError = "File upload failed.";
            }
        }
    }

    // Proceed with update if no errors
    if (empty($updateError)) {
        $updateQuery = "UPDATE users SET first_name=?, last_name=?, email=?, phone=?, profile_photo=? WHERE id=?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("sssssi", $newFirstName, $newLastName, $newEmail, $newPhone, $newProfilePhoto, $user_id);

        if ($stmt->execute()) {
            $_SESSION['update_message'] = "Profile updated successfully!";
            $_SESSION['email'] = $newEmail;

            // Refresh the page to display the updated profile photo
            header("Location: update_profile.php");
            exit();
        } else {
            $updateError = "Database error: " . $conn->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include('header.php'); ?>

<main>
    <h2>Update Profile</h2>
    
    <?php
    // Show success message if set in session
    if (isset($_SESSION['update_message'])) {
        echo "<p class='success'>" . $_SESSION['update_message'] . "</p>";
        unset($_SESSION['update_message']); // Remove message after displaying it
    }
    ?>
    
    <?php if (!empty($updateError)) echo "<p class='error'>$updateError</p>"; ?>
    
    <form action="update_profile.php" method="post" enctype="multipart/form-data">
        <label>First Name:</label>
        <input type="text" name="first_name" value="<?php echo htmlspecialchars($firstName); ?>" required>

        <label>Last Name:</label>
        <input type="text" name="last_name" value="<?php echo htmlspecialchars($lastName); ?>" required>

        <label>Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

        <label>Phone:</label>
        <input type="text" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>

        <label>Current Profile Photo:</label><br>
        <img src="<?php echo (!empty($profilePhoto) && file_exists($profilePhoto)) ? $profilePhoto : 'images/default-avatar.png'; ?>" class="profile-photo" alt="Profile Photo"><br><br>

        <label>Upload New Profile Photo:</label>
        <input type="file" name="profile_photo" accept="image/*">

        <button type="submit" name="update_profile">Update Profile</button>
    </form>
</main>

<?php include('footer.php'); ?>

</body>
</html>
