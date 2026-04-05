<?php
include "config.php";

if (isset($_POST['register'])) {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if ($username == "" || $password == "") {
        echo "All fields required!";
    } elseif (strlen($password) < 5) {
        echo "Password must be at least 5 characters!";
    } else {

        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashed);
        $stmt->execute();

        echo "Registered Successfully!";
    }
}
?>

<form method="POST">
<input type="text" name="username" placeholder="Username"><br>
<input type="password" name="password" placeholder="Password"><br>
<button name="register">Register</button>
</form>