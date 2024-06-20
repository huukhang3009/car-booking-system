<?php

require '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $vehicle_id = $_POST['vehicle_id'];
    $driver_id = $_POST['driver_id'];
    $vehicle_status = $_POST['vehicle_status'];
    $driver_status = $_POST['driver_status'];

    // Cập nhật trạng thái xe và tài xế
    if ($vehicle_id) {
        $conn->query("UPDATE vehicles SET status='$vehicle_status' WHERE id=$vehicle_id");
    }
    if ($driver_id) {
        $conn->query("UPDATE drivers SET status='$driver_status' WHERE id=$driver_id");
    }
    header("Location: dispatcher_dashboard.php?success=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Status</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Update Status</h1>
    <a href="dispatcher_dashboard.php" class="btn btn-primary mb-3">Back</a>
    <form action="update_status.php" method="post">
        <div class="mb-3">
            <label for="vehicle_id" class="form-label">ID phương tiện</label>
            <input type="number" class="form-control" id="vehicle_id" name="vehicle_id">
        </div>
        <div class="mb-3">
            <label for="vehicle_status" class="form-label">Trạng thái</label>
            <select class="form-control" id="vehicle_status" name="vehicle_status">
                <option value="available">Có sẵn</option>
                <option value="busy">Bận</option>
                <option value="maintenance">Bảo trì</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="driver_id" class="form-label">ID tài xế</label>
            <input type="number" class="form-control" id="driver_id" name="driver_id">
        </div>
        <div class="mb-3">
            <label for="driver_status" class="form-label">Trạng thái</label>
            <select class="form-control" id="driver_status" name="driver_status">
                <option value="available">Có sẵn</option>
                <option value="busy">Bận</option>
                <option value="off-duty">Nghỉ việc</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
</div>
</body>
</html>
