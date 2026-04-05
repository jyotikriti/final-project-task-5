<?php
session_start();
include "config.php";

// 🔐 Session check
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Pagination
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$start = ($page - 1) * $limit;

// Search
$search = isset($_GET['search']) ? trim($_GET['search']) : "";

// 🔐 Prepared statement (secure)
if ($search != "") {
    $stmt = $conn->prepare("SELECT * FROM posts WHERE title LIKE ? OR content LIKE ? LIMIT ?, ?");
    $search_param = "%$search%";
    $stmt->bind_param("ssii", $search_param, $search_param, $start, $limit);
} else {
    $stmt = $conn->prepare("SELECT * FROM posts LIMIT ?, ?");
    $stmt->bind_param("ii", $start, $limit);
}

$stmt->execute();
$result = $stmt->get_result();

// Total count
if ($search != "") {
    $stmt2 = $conn->prepare("SELECT COUNT(*) as total FROM posts WHERE title LIKE ? OR content LIKE ?");
    $stmt2->bind_param("ss", $search_param, $search_param);
} else {
    $stmt2 = $conn->prepare("SELECT COUNT(*) as total FROM posts");
}

$stmt2->execute();
$total_result = $stmt2->get_result();
$total_row = $total_result->fetch_assoc();
$total_posts = $total_row['total'];

$total_pages = ceil($total_posts / $limit);
?>

<!DOCTYPE html>
<html>
<head>
<title>Blog System</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(to right, #74ebd5, #ACB6E5);
}
.card {
    border-radius: 15px;
}
</style>
</head>

<body>

<div class="container mt-5">
<div class="card p-4 shadow">

<h2 class="text-center mb-4">📘 Blog Dashboard</h2>

<p><b>User:</b> <?php echo $_SESSION['user']; ?> | 
<b>Role:</b> <?php echo $_SESSION['role']; ?></p>

<!-- Search -->
<form method="GET" class="d-flex mb-3">
<input type="text" name="search" class="form-control me-2"
placeholder="Search posts..." value="<?php echo htmlspecialchars($search); ?>">
<button class="btn btn-primary">Search</button>
</form>

<!-- Buttons -->
<div class="mb-3">
<a href="create.php" class="btn btn-success">+ Add Post</a>
<a href="logout.php" class="btn btn-danger">Logout</a>
</div>

<!-- Table -->
<table class="table table-bordered table-hover bg-white text-center">
<thead class="table-dark">
<tr>
<th>ID</th>
<th>Title</th>
<th>Created</th>
<th>Action</th>
</tr>
</thead>

<tbody>
<?php if ($result->num_rows > 0) {
while ($row = $result->fetch_assoc()) { ?>
<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo htmlspecialchars($row['title']); ?></td>
<td><?php echo $row['created_at']; ?></td>
<td>

<a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>

<?php if ($_SESSION['role'] == 'admin') { ?>
<a href="delete.php?id=<?php echo $row['id']; ?>" 
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this post?');">
Delete
</a>
<?php } ?>

</td>
</tr>
<?php } } else { ?>
<tr>
<td colspan="4">No data found</td>
</tr>
<?php } ?>
</tbody>
</table>

<!-- Pagination -->
<nav>
<ul class="pagination justify-content-center">

<?php if ($page > 1) { ?>
<li class="page-item">
<a class="page-link" href="?page=<?php echo $page-1; ?>&search=<?php echo $search; ?>">Prev</a>
</li>
<?php } ?>

<?php for ($i = 1; $i <= $total_pages; $i++) { ?>
<li class="page-item <?php if ($i == $page) echo 'active'; ?>">
<a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo $search; ?>">
<?php echo $i; ?>
</a>
</li>
<?php } ?>

<?php if ($page < $total_pages) { ?>
<li class="page-item">
<a class="page-link" href="?page=<?php echo $page+1; ?>&search=<?php echo $search; ?>">Next</a>
</li>
<?php } ?>

</ul>
</nav>

</div>
</div>

</body>
</html>