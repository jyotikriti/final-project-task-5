<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
}
?>

<h2>Welcome <?php echo $_SESSION['user']; ?></h2>
<p>Role: <?php echo $_SESSION['role']; ?></p>

<a href="index.php">Manage Posts</a> |
<a href="logout.php">Logout</a>