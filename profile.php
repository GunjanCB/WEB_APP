<?php
include('db.php');
include('header.php');

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$query = "SELECT username, first_name, last_name, email, phone, profile_photo FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $firstName, $lastName, $email, $phone, $profilePhoto);
$stmt->fetch();
$stmt->close();
?>
    <link rel="stylesheet" href="style.css">
    <main>
    <h2>User Profile</h2>
    <div class="profile-container">
        <img src="<?php echo $profilePhoto ? $profilePhoto : 'images/default-avatar.png'; ?>" alt="Profile Photo" class="profile-photo">
        <p><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
        <p><strong>First Name:</strong> <?php echo htmlspecialchars($firstName); ?></p>
        <p><strong>Last Name:</strong> <?php echo htmlspecialchars($lastName); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($phone); ?></p>

        <!-- Buttons Side by Side -->
        <div class="button-container">
            <a href="update_profile.php" class="btn update-btn">Update Profile</a>
            <a href="delete_account.php" class="btn delete-btn" onclick="return confirm('Are you sure you want to delete your account? This action is permanent!');">
                Delete Account
            </a>
        </div>
    </div>
</main>


<?php include('footer.php'); ?>
