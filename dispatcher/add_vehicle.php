<?php
session_start();
if (!isset($_SESSION['dispatcher_id']) && $_SESSION['role'] !== 'dispatcher') {
    header("Location: ../auth/login.php");
    exit();
}
require '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
$license_plate = $_POST['license_plate'];
$model = $_POST['model'];
$capacity = $_POST['capacity'];

$sql = "INSERT INTO vehicles (license_plate, model, capacity) VALUES ('$license_plate', '$model', '$capacity')";

if ($conn->query($sql) === TRUE) {
    header("Location: dispatcher_dashboard.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Vehicle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Thêm phương tiện</h1>
    <div class="d-flex justify-content-between mb-3">
        <a href="dispatcher_dashboard.php" class="btn btn-primary">Back</a>
    </div>

    <form action="" method="post">
        <div class="mb-3">
            <label for="license_plate" class="form-label">Tên xe</label>
            <input type="text" class="form-control" id="license_plate" name="license_plate" required>
        </div>
        <div class="mb-3">
            <label for="model" class="form-label">Biển số</label>
            <input type="text" class="form-control" id="model" name="model" required>
        </div>
        <div class="mb-3">
            <label for="capacity" class="form-label">Số ghế</label>
            <input type="text" class="form-control" id="capacity" name="capacity" required>
        </div>
        <button type="submit" class="btn btn-primary">Thêm</button>
    </form>
</div>
</body>
</html>
