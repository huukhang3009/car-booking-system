<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'dispatcher') {
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
    <title>Dispatcher Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Dispatcher Dashboard</h1>
    <a href="../auth/logout.php" class="btn btn-danger mb-3">Logout</a>
    <a href="add_driver.php" class="btn btn-success mb-3">Thêm tài xế</a>
    <a href="add_vehicle.php" class="btn btn-success mb-3">Thêm phương tiện</a>
    <a href="view_status.php" class="btn btn-info mb-3">Trạng thái</a>
    <a href="update_status.php" class="btn btn-warning mb-3">Cập nhật</a>
    <a href="notify_users.php" class="btn btn-primary mb-3">Thông báo</a>

    <div class="card mb-4">
        <div class="card-header">
            <h2>Yêu cầu đã được duyệt</h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nhân viên</th>
                            <th>Ngày</th>
                            <th>Giờ</th>
                            <th>Điểm đón</th>
                            <th>Điểm đến</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT bookings.*, users.username FROM bookings JOIN users ON bookings.user_id = users.id WHERE status='approved'";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td>" . $row['username'] . "</td>";
                                echo "<td>" . $row['date'] . "</td>";
                                echo "<td>" . $row['time'] . "</td>";
                                echo "<td>" . $row['pickup_location'] . "</td>";
                                echo "<td>" . $row['dropoff_location'] . "</td>";
                                echo "<td>" . ucfirst($row['status']) . "</td>";
                                echo "<td>
                                        <a href='assign_vehicle.php?id=" . $row['id'] . "' class='btn btn-primary'>Assign Vehicle</a>
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

    <div class="card mb-4">
        <div class="card-header">
            <h2>Yêu cầu hoàn thành chuyến đi</h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nhân viên</th>
                            <th>Tin nhắn</th>
                            <th>Hành đồng</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT n.*, u.username FROM notifications n JOIN users u ON n.user_id = u.id WHERE n.message LIKE '%requested to complete the ride%' AND n.is_read = 0";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td>" . $row['username'] . "</td>";
                                echo "<td>" . $row['message'] . "</td>";
                                echo "<td>
                                        <form action='complete_ride.php' method='post' style='display:inline-block;'>
                                            <input type='hidden' name='notification_id' value='" . $row['id'] . "'>
                                            <input type='hidden' name='booking_id' value='" . explode(': ', $row['message'])[1] . "'>
                                            <button type='submit' class='btn btn-success'>Complete Ride</button>
                                        </form>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' class='text-center'>No complete ride requests</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php
    if (isset($_GET['success'])) {
        echo '<div class="alert alert-success" role="alert">Operation completed successfully!</div>';
    }
    if (isset($_GET['error'])) {
        echo '<div class="alert alert-danger" role="alert">Operation failed.</div>';
    }
    ?>
</div>
</body>
</html>
