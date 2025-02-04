<?php
session_start();
include('header.php');
?>

<main class="hero-section">
    <div class="hero-content">
        <h1>Welcome to Our User Management System</h1>
        <p>Manage your profile, update your details, and maintain your account effortlessly.</p>

        <?php if (isset($_SESSION['user_id'])): ?>
    <div class="welcome-container">
        <p>Hello, <strong><?php echo htmlspecialchars($_SESSION['email']); ?></strong>! You are logged in.</p>
    </div>
    <div class="button-container">
        <a href="profile.php" class="btn profile-btn">Go to Profile</a>
        <a href="logout.php" class="btn logout-btn">Logout</a>
    </div>
<?php endif; ?>


        <!-- Task Description Section -->
        <section class="task-section">
            <h2>Project Overview</h2>
            <p>This website allows users to manage their profiles efficiently. Features include:</p>
            <ul>
                <li>Secure user authentication (Login & Registration)</li>
                <li>Profile update with profile photo upload</li>
                <li>CRUD operations for user data management</li>
                <li>SQL database integration</li>
            </ul>
        </section>
    </div>
</main>

<?php include('footer.php'); ?>
