<?php
session_start();
if (!isset($_SESSION['dispatcher_id']) && $_SESSION['role'] !== 'dispatcher') {
    header("Location: ../auth/login.php");
    exit();
}
require '../db.php';

$vehicle_id = $_GET['id'];

$sql = "SELECT * FROM vehicles WHERE id=$vehicle_id";
$result = $conn->query($sql);
$vehicle = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Vehicle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Thông tin xe</h1>
    <div class="d-flex justify-content-between mb-3">
        <a href="view_status.php" class="btn btn-primary">Back</a>
    </div>
    <form action="" method="post">
        <input type="hidden" name="vehicle_id" value="<?php echo $vehicle['id']; ?>">
        <div class="mb-3">
            <label for="license_plate" class="form-label">Tên</label>
            <input type="text" class="form-control" id="license_plate" name="license_plate" value="<?php echo $vehicle['license_plate']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="model" class="form-label">Biển số</label>
            <input type="text" class="form-control" id="model" name="model" value="<?php echo $vehicle['model']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="capacity" class="form-label">Số ghế</label>
            <input type="text" class="form-control" id="capacity" name="capacity" value="<?php echo $vehicle['license_plate']; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
</div>
</body>
</html>
