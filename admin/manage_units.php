<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}
require '../db.php';

// Thêm phòng ban mới
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_unit'])) {
    $name = $_POST['unit_name'];
    $sql = "INSERT INTO units (name) VALUES ('$name')";
    $conn->query($sql);
}

// Xóa phòng ban
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM units WHERE id=$id";
    $conn->query($sql);
}

$units = $conn->query("SELECT * FROM units");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Units</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Quản lý phòng ban</h1>
    <a href="dashboard.php" class="btn btn-primary mb-3">Back</a>

    <form method="post" action="">
        <div class="mb-3">
            <h2 for="unit_name" class="form-label">Tên phòng ban</h2>
            <input type="text" class="form-control" id="unit_name" name="unit_name" required>
        </div>
        <button type="submit" name="add_unit" class="btn btn-primary">Thêm</button>
    </form>


    <div class="card mb-4">
        <div class="card-header">
            <h2 class="mt-5 mb-4">Phòng ban hiện tại</h2>
        </div>
        <div class="card-body">
            <table class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Hành động</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = $units->fetch_assoc()): ?>
                <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td>
                    <a href="manage_units.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this unit?');">Delete</a>
                </td>
                </tr>
        <?php endwhile; ?>
                    </tbody>
                </table>
            </table>
        </div>
    </div>
</div>
</body>
</html>
