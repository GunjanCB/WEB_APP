<?php
session_start();
include('db.php');

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch the user's profile photo before deleting the account
$query = "SELECT profile_photo FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($profilePhoto);
$stmt->fetch();
$stmt->close();

// Delete profile photo if exists
if (!empty($profilePhoto) && file_exists($profilePhoto)) {
    unlink($profilePhoto);
}

// Delete user from the database
$deleteQuery = "DELETE FROM users WHERE id = ?";
$stmt = $conn->prepare($deleteQuery);
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    // Destroy session and log the user out
    session_destroy();

    // Redirect to register page after deletion
    echo "<script>
            alert('Your account has been deleted successfully.');
            window.location.href = 'register.php';
          </script>";
    exit();
} else {
    echo "<script>
            alert('Error deleting account. Please try again.');
            window.location.href = 'profile.php';
          </script>";
}

$stmt->close();
?>
