<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}
require '../db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Requests</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Admin Dashboard - Requests</h1>
    <a href="dashboard.php" class="btn btn-primary mb-3">Back</a>

    <div class="card mb-4">
        <div class="card-header">
            <h2>Tất cả chuyến đi</h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="requestsTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nhân viên</th>
                            <th>Quản lý</th>
                            <th>Ngày </th>
                            <th>Giờ</th>
                            <th>Điểm đón</th>
                            <th>Điểm đến</th>
                            <th>Trạng thái</th>
                            <th>Tài xế</th>
                            <th>Phương tiện</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT 
                                    bookings.id, 
                                    users.username AS user, 
                                    managers.username AS manager, 
                                    bookings.date, 
                                    bookings.time, 
                                    bookings.pickup_location, 
                                    bookings.dropoff_location, 
                                    bookings.status, 
                                    drivers.name AS driver, 
                                    vehicles.model AS vehicle 
                                FROM bookings
                                LEFT JOIN users ON bookings.user_id = users.id
                                LEFT JOIN users AS managers ON users.unit_id = managers.unit_id AND managers.role = 'manager'
                                LEFT JOIN drivers ON bookings.driver_id = drivers.id
                                LEFT JOIN vehicles ON bookings.vehicle_id = vehicles.id";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td>" . $row['user'] . "</td>";
                                echo "<td>" . $row['manager'] . "</td>";
                                echo "<td>" . $row['date'] . "</td>";
                                echo "<td>" . $row['time'] . "</td>";
                                echo "<td>" . $row['pickup_location'] . "</td>";
                                echo "<td>" . $row['dropoff_location'] . "</td>";
                                echo "<td>" . ucfirst($row['status']) . "</td>";
                                echo "<td>" . $row['driver'] . "</td>";
                                echo "<td>" . $row['vehicle'] . "</td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#requestsTable').DataTable();
    });
</script>
</body>
</html>
