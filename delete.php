<?php
session_start();
include "config.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
}

if ($_SESSION['role'] != 'admin') {
    die("Access Denied!");
}

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM posts WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: index.php");
?>