<?php
session_start();
if (!isset($_SESSION['dispatcher_id']) && $_SESSION['role'] !== 'dispatcher') {
    header("Location: ../auth/login.php");
    exit();
}
require '../db.php';

$booking_id = $_GET['id'];

$sql = "SELECT * FROM bookings WHERE id=$booking_id";
$result = $conn->query($sql);
$booking = $result->fetch_assoc();

$sql_vehicles = "SELECT * FROM vehicles WHERE status='available'";
$result_vehicles = $conn->query($sql_vehicles);

$sql_drivers = "SELECT * FROM drivers WHERE status='available' AND id NOT IN (SELECT driver_id FROM bookings WHERE status='assigned')";
$result_drivers = $conn->query($sql_drivers);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Vehicle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    
<div class="card mb-4">
    <div class="card-header">
        <h1>Điều phối xe và tài xế</h1>
        <div class="d-flex justify-content-between mb-3">
        <a href="dispatcher_dashboard.php" class="btn btn-primary">Back</a>
    </div>
    </div>
    <div class="card-body">
        <form action="assign_vehicle_process.php" method="post">
        <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
        <div class="mb-3">
            <label for="vehicle" class="form-label">Chọn xe</label>
            <select class="form-select" id="vehicle" name="vehicle_id" required>
                <?php
                if ($result_vehicles->num_rows > 0) {
                    while ($vehicle = $result_vehicles->fetch_assoc()) {
                        echo "<option value='" . $vehicle['id'] . "'>" . $vehicle['license+plate'] . " " . $vehicle['model'] . "</option>";
                    }
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="driver" class="form-label">Chọn tài xế</label>
            <select class="form-select" id="driver" name="driver_id" required>
                <?php
                if ($result_drivers->num_rows > 0) {
                    while ($name = $result_drivers->fetch_assoc()) {
                        echo "<option value='" . $name['id'] . "'>" . $name['name'] . "</option>";
                    }
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Xác nhận</button>
    </form>
    </div>
    </div>
</div>
</body>
</html>
