<?php
session_start();
include "config.php";

// ✅ Check if ID exists
if (!isset($_GET['id']) || $_GET['id'] == "") {
    die("Invalid Request!");
}

$id = $_GET['id'];

// Fetch data
$stmt = $conn->prepare("SELECT * FROM posts WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

// ✅ Check if data found
if (!$data) {
    die("Post not found!");
}

// Update
if (isset($_POST['update'])) {

    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if ($title == "" || $content == "") {
        echo "All fields required!";
    } else {
        $stmt = $conn->prepare("UPDATE posts SET title=?, content=? WHERE id=?");
        $stmt->bind_param("ssi", $title, $content, $id);
        $stmt->execute();

        header("Location: index.php");
        exit();
    }
}
?>

<form method="POST">
<input type="text" name="title" value="<?php echo $data['title']; ?>"><br>
<textarea name="content"><?php echo $data['content']; ?></textarea><br>
<button name="update">Update</button>
</form>