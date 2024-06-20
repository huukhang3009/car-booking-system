<?php
session_start();
if (!isset($_SESSION['username']) && $_SESSION['role'] !== 'dispatcher') {
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
    <title>View Status</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Vehicles and Drivers Status</h1>
    <div class="d-flex justify-content-between mb-3">
        <a href="dispatcher_dashboard.php" class="btn btn-primary">Back</a>
    </div>
       


    <div class="card-mb-4">
        <div class="card-header">
            <h2>Phương tiện</h2>
        </div>
        <div class="card-body">
            <div class="table-reponsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Biển số</th>
                        <th>Tên</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
        $sql = "SELECT * FROM vehicles";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['model'] . "</td>";
                echo "<td>" . $row['license_plate'] . "</td>";
                echo "<td>" . ucfirst($row['status']) . "</td>";
                echo "<td>
                <a href='edit_vehicle.php?id=" . $row["id"] . "' class='btn btn-warning'>Edit</a>
                <a href='delete_vehicle.php?id=" . $row['id'] . "' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this vehicle?\";'>Delete</a>
                </td>";
                echo "</tr>";
            }
        }
        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div class="card-mb-4">
        <div class="card-header">
            <h2>Tài xế</h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên</t>
                        <th>Điện thoại</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                    </thead>
                <tbody>
                <?php
        $sql = "SELECT * FROM drivers";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>". $row["phone"] . "</td>";
                echo "<td>" . ucfirst($row['status']) . "</td>";
                echo "<td>
                        <a href='edit_driver.php?id=" . $row["id"] . "' class='btn btn-warning'>Edit</a>
                        <a href='delete_driver.php?id=" . $row['id'] . "' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this driver?\");'>Delete</a>
                        </td>";
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
</body>
</html>
