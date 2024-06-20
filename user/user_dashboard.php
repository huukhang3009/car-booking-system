<?php
session_start();

//kiểm tra xem session có tồn tại và người dùng có quyền truy cập không
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header("Location: ../auth/login.php");
    exit();
}

require '../db.php';

$username = $_SESSION['username'];

// Lấy thông tin người dùng
$sql = "SELECT id, username FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_id = $row['id'];
    $username = htmlspecialchars($row['username']);
} else {
    $username = 'Unknown User';
    $user_id = null;
}

// Lấy thông báo người dùng
$notifications = [];
if ($user_id) {
    $notifications_sql = "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($notifications_sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $notifications_result = $stmt->get_result();

    if ($notifications_result->num_rows > 0) {
        while ($notification = $notifications_result->fetch_assoc()) {
            $notifications[] = $notification;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .notification-list {
            max-height: 200px;
            overflow-y: auto;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Xin chào, <?php echo $username; ?>!</h1>
    <a href="../auth/logout.php" class="btn btn-danger mb-3">Logout</a>

    <div class="card mb-4">
        <div class="card-header">
            <h2>Thông báo</h2>
        </div>
        <div class="card-body notification-list">
            <?php if (!empty($notifications)) : ?>
                <ul class="list-group">
                    <?php foreach ($notifications as $index => $notification) : ?>
                        <?php if ($index < 3) : ?>
                            <li class="list-group-item">
                                <?php echo htmlspecialchars($notification['message']); ?>
                                <small class="text-muted d-block">Received at: <?php echo $notification['created_at']; ?></small>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
                <?php if (count($notifications) > 3) : ?>
                    <button class="btn btn-link show-all-btn" onclick="showAllNotifications()">Show All</button>
                <?php endif; ?>
            <?php else : ?>
                <p>No notifications found</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h2>Đặt xe</h2>
        </div>
        <div class="card-body">
            <form action="book_ride.php" method="post">
                <div class="mb-3">
                    <label for="date" class="form-label">Ngày đi</label>
                    <input type="date" class="form-control" id="date" name="date" required>
                </div>
                <div class="mb-3">
                    <label for="time" class="form-label">Giờ đi</label>
                    <input type="time" class="form-control" id="time" name="time" required>
                </div>
                <div class="mb-3">
                    <label for="pickup_location" class="form-label">Điểm đón</label>
                    <input type="text" class="form-control" id="pickup_location" name="pickup_location" required>
                </div>
                <div class="mb-3">
                    <label for="date_return" class="form-label">Ngày về</label>
                    <input type="date" class="form-control" id="date_return" name="date_return">
                </div>
                <div class="mb-3">
                    <label for="dropoff_location" class="form-label">Điểm đến</label>
                    <input type="text" class="form-control" id="dropoff_location" name="dropoff_location" required>
                </div>
                <div class="mb-3">
                    <label for="passengers" class="form-label">Số người đi</label>
                    <input type="number" class="form-control" id="passengers" name="passengers" required>
                </div>
                <div class="mb-3">
                    <label for="special_requests" class="form-label">Ghi chú</label>
                    <textarea class="form-control" id="special_requests" name="special_requests"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Đặt</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>Xe đã đặt</h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Ngày</th>
                            <th>Giờ</th>
                            <th>Điểm đón</th>
                            <th>Ngày về</th>
                            <th>Điểm đến</th>
                            <th>Trạng thái</th>
                            <th>Phương tiện</th>
                            <th>Tài xế</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT b.*, v.license_plate, v.model, d.name as driver_name, d.phone as driver_phone
                                FROM bookings b
                                LEFT JOIN vehicles v ON b.vehicle_id = v.id
                                LEFT JOIN drivers d ON b.driver_id = d.id
                                WHERE b.user_id = ? AND b.is_deleted = 0";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $user_id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["id"] . "</td>";
                                echo "<td>" . $row["date"] . "</td>";
                                echo "<td>" . $row["time"] . "</td>";
                                echo "<td>" . $row["pickup_location"] . "</td>";
                                echo "<td>" . $row["date_return"] . "</td>";
                                echo "<td>" . $row["dropoff_location"] . "</td>";
                                echo "<td>" . ucfirst($row["status"]) . "</td>";
                                echo "<td>" . (isset($row["model"]) ? $row["model"] . ' (' . $row["license_plate"] . ')' : 'N/A') . "</td>";
                                echo "<td>" . (isset($row["driver_name"]) ? $row["driver_name"] . ' (' . $row["driver_phone"] . ')' : 'N/A') . "</td>";
                                echo "<td>
                                        <form action='delete_booking.php' method='post' onsubmit='return confirm(\"Are you sure you want to delete this booking?\");' style='display:inline-block;'>
                                            <input type='hidden' name='booking_id' value='" . htmlspecialchars($row["id"]) . "'>
                                            <button type='submit' class='btn btn-danger'>Delete</button>
                                        </form>";
                                if ($row['status'] == 'assigned') {
                                    echo "<form action='request_complete.php' method='post' style='display:inline-block;'>
                                            <input type='hidden' name='booking_id' value='" . $row['id'] . "'>
                                            <button type='submit' class='btn btn-success'>Complete Ride</button>
                                          </form>";
                                }
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='10' class='text-center'>No bookings found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
function showAllNotifications() {
    const listGroup = document.querySelector('.notification-list');
    listGroup.style.maxHeight = 'none'; 
    listGroup.style.overflowY = 'visible';
    const showAllButton = document.querySelector('.show-all-btn');
    if (showAllButton) {
        showAllButton.innerText = 'Show Less';
        showAllButton.classList.remove('show-all-btn');
        showAllButton.classList.add('show-less-btn');
        showAllButton.setAttribute('onclick', 'showLessNotifications()');
    }
}

function showLessNotifications() {
    const listGroup = document.querySelector('.notification-list');
    listGroup.style.maxHeight = '200px';
    listGroup.style.overflowY = 'auto';
    const showLessButton = document.querySelector('.show-less-btn');
    if (showLessButton) {
        showLessButton.innerText = 'Show All';
        showLessButton.classList.remove('show-less-btn');
        showLessButton.classList.add('show-all-btn');
        showLessButton.setAttribute('onclick', 'showAllNotifications()');
    }
}
</script>
</body>
</html>
