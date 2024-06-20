<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header("Location: user_login.php");
    exit();
}
require '../db.php';

// Xử lý dữ liệu từ biểu mẫu POST
$date = $_POST['date'];
$time = $_POST['time'];
$pickup_location = $_POST['pickup_location'];
$date_return = $_POST['date_return'];
$dropoff_location = $_POST['dropoff_location'];
$passengers = $_POST['passengers'];
$special_requests = $_POST['special_requests'];

// Sử dụng username để lấy user_id từ bảng users
$username = $_SESSION['username'];
$sql_get_user_id = "SELECT id FROM users WHERE username = ?";
$stmt_get_user_id = $conn->prepare($sql_get_user_id);
$stmt_get_user_id->bind_param("s", $username);
$stmt_get_user_id->execute();
$result_get_user_id = $stmt_get_user_id->get_result();

if ($result_get_user_id->num_rows > 0) {
    $row = $result_get_user_id->fetch_assoc();
    $user_id = $row['id'];

    // Thực hiện truy vấn INSERT vào bảng bookings
    $sql = "INSERT INTO bookings (user_id, date, time, pickup_location, date_return, dropoff_location, passengers, special_requests) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssis", $user_id, $date, $time, $pickup_location, $date_return, $dropoff_location, $passengers, $special_requests);

    if ($stmt->execute()) {
        header("Location: user_dashboard.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    echo "Error: User not found."; // This message should not be shown in production
}
?>
