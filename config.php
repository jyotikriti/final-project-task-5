<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "root", "", "blog");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?><?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "root", "", "blog");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>