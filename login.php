<?php
session_start();
include "config.php";

if (isset($_POST['login'])) {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if ($username == "" || $password == "") {
        echo "All fields required!";
    } else {

        $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {

            session_regenerate_id(true);

            $_SESSION['user'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            header("Location: dashboard.php");
        } else {
            echo "Invalid Login!";
        }
    }
}
?>

<form method="POST">
<input type="text" name="username" placeholder="Username"><br>
<input type="password" name="password" placeholder="Password"><br>
<button name="login">Login</button>
</form>