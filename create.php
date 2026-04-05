<?php
session_start();
include "config.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
}

if (isset($_POST['submit'])) {

    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if ($title == "" || $content == "") {
        echo "All fields required!";
    } else {

        $stmt = $conn->prepare("INSERT INTO posts (title, content) VALUES (?, ?)");
        $stmt->bind_param("ss", $title, $content);
        $stmt->execute();

        header("Location: index.php");
    }
}
?>

<form method="POST">
<input type="text" name="title" placeholder="Title"><br>
<textarea name="content" placeholder="Content"></textarea><br>
<button name="submit">Add</button>
</form>