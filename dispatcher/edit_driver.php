<?php
session_start();
if (!isset($_SESSION['dispatcher_id']) && $_SESSION['role'] !== 'dispatcher') {
    header("Location: ../auth/login.php");
    exit();
}
require '../db.php';

$driver_id = $_GET['id'];

$sql = "SELECT * FROM drivers WHERE id=$driver_id";
$result = $conn->query($sql);
$driver = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Driver</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Thông tin tài xế</h1>
    <div class="d-flex justify-content-between mb-3">
        <a href="view_status.php" class="btn btn-primary">Back</a>
    </div>
    <form action="" method="post">
        <input type="hidden" name="driver_id" value="<?php echo $driver['id']; ?>">
        <div class="mb-3">
            <label for="name" class="form-label">Tên</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo $driver['name']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Số điện thoại</label>
            <input type="phone" class="form-control" id="phone" name="phone" value="<?php echo $driver['phone']; ?>">
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
</div>
</body>
</html>
