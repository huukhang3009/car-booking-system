<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'manager') {
    header("Location: ../auth/login.php");
    exit();
}
require '../db.php';

$username = $_SESSION['username'];

// Lấy thông tin từ manager
$sql = "SELECT id, unit_id FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $manager_id = $row['id'];
    $unit_id = $row['unit_id'];
} else {
    $manager_id = null;
    $unit_id = null;
}

if (!$manager_id) {
    echo "Không tìm thấy";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Manager Dashboard</h1>
    <a href="../auth/logout.php" class="btn btn-danger mb-3">Logout</a>

    <!-- Navigation tabs or buttons for different views -->
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#pending">Yêu cầu chờ xử lý</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#approved">Yêu cầu đã chấp nhận</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#rejected">Yêu cầu đã từ chối</a>
        </li>
    </ul>

    <!-- Tab panes for different views -->
    <div class="tab-content">
        <!-- Pending requests -->
        <div class="tab-pane fade show active" id="pending">
            <div class="card mb-4">
                <div class="card-header">
                    <h2>Yêu cầu chờ xử lý</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nhân viên</th>
                                    <th>Điện thoại</th>
                                    <th>Ngày đi</th>
                                    <th>Giờ đi</th>
                                    <th>Điểm đón</th>
                                    <th>Điểm đến</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql_pending = "SELECT bookings.*, users.username, users.phone FROM bookings 
                                                JOIN users ON bookings.user_id = users.id 
                                                WHERE bookings.status='pending' AND users.unit_id = ?";
                                $stmt_pending = $conn->prepare($sql_pending);
                                $stmt_pending->bind_param("i", $unit_id);
                                $stmt_pending->execute();
                                $result_pending = $stmt_pending->get_result();

                                if ($result_pending->num_rows > 0) {
                                    while ($row_pending = $result_pending->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . $row_pending['id'] . "</td>";
                                        echo "<td>" . $row_pending['username'] . "</td>";
                                        echo "<td>" . $row_pending['phone'] . "</td>";
                                        echo "<td>" . $row_pending['date'] . "</td>";
                                        echo "<td>" . $row_pending['time'] . "</td>";
                                        echo "<td>" . $row_pending['pickup_location'] . "</td>";
                                        echo "<td>" . $row_pending['dropoff_location'] . "</td>";
                                        echo "<td>" . ucfirst($row_pending['status']) . "</td>";
                                        echo "<td>
                                                <a href='approve_booking.php?id=" . $row_pending['id'] . "' class='btn btn-success'>Chấp nhận</a>
                                                <a href='reject_booking.php?id=" . $row_pending['id'] . "' class='btn btn-danger'>Từ chối</a>
                                              </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='9' class='text-center'>Không có yêu cầu chờ xử lý</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approved requests -->
        <div class="tab-pane fade" id="approved">
            <div class="card mb-4">
                <div class="card-header">
                    <h2>Yêu cầu đã chấp nhận</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nhân viên</th>
                                    <th>Điện thoại</th>
                                    <th>Ngày đi</th>
                                    <th>Giờ đi</th>
                                    <th>Điểm đón</th>
                                    <th>Điểm đến</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql_approved = "SELECT bookings.*, users.username, users.phone FROM bookings 
                                                JOIN users ON bookings.user_id = users.id 
                                                WHERE bookings.status='approved' AND users.unit_id = ?";
                                $stmt_approved = $conn->prepare($sql_approved);
                                $stmt_approved->bind_param("i", $unit_id);
                                $stmt_approved->execute();
                                $result_approved = $stmt_approved->get_result();

                                if ($result_approved->num_rows > 0) {
                                    while ($row_approved = $result_approved->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . $row_approved['id'] . "</td>";
                                        echo "<td>" . $row_approved['username'] . "</td>";
                                        echo "<td>" . $row_approved['phone'] . "</td>";
                                        echo "<td>" . $row_approved['date'] . "</td>";
                                        echo "<td>" . $row_approved['time'] . "</td>";
                                        echo "<td>" . $row_approved['pickup_location'] . "</td>";
                                        echo "<td>" . $row_approved['dropoff_location'] . "</td>";
                                        echo "<td>" . ucfirst($row_approved['status']) . "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='8' class='text-center'>Không có yêu cầu đã chấp nhận</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rejected requests -->
        <div class="tab-pane fade" id="rejected">
            <div class="card mb-4">
                <div class="card-header">
                    <h2>Yêu cầu đã từ chối</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nhân viên</th>
                                    <th>Điện thoại</th>
                                    <th>Ngày đi</th>
                                    <th>Giờ đi</th>
                                    <th>Điểm đón</th>
                                    <th>Điểm đến</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql_rejected = "SELECT bookings.*, users.username, users.phone FROM bookings 
                                                JOIN users ON bookings.user_id = users.id 
                                                WHERE bookings.status='rejected' AND users.unit_id = ?";
                                $stmt_rejected = $conn->prepare($sql_rejected);
                                $stmt_rejected->bind_param("i", $unit_id);
                                $stmt_rejected->execute();
                                $result_rejected = $stmt_rejected->get_result();

                                if ($result_rejected->num_rows > 0) {
                                    while ($row_rejected = $result_rejected->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . $row_rejected['id'] . "</td>";
                                        echo "<td>" . $row_rejected['username'] . "</td>";
                                        echo "<td>" . $row_rejected['phone'] . "</td>";
                                        echo "<td>" . $row_rejected['date'] . "</td>";
                                        echo "<td>" . $row_rejected['time'] . "</td>";
                                        echo "<td>" . $row_rejected['pickup_location'] . "</td>";
                                        echo "<td>" . $row_rejected['dropoff_location'] . "</td>";
                                        echo "<td>" . ucfirst($row_rejected['status']) . "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='8' class='text-center'>Không có yêu cầu đã từ chối</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
