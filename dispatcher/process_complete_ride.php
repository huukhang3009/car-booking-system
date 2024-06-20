<?php
session_start();
if (!isset($_SESSION['dispatcher_id']) && $_SESSION['role'] !== 'dispatcher') {
    header("Location: ../auth/login.php");
    exit();
}
require '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $notification_id = $_POST['notification_id'];
    $booking_id = $_POST['booking_id'];

    // Cập nhật trạng thái chuyến đi là "completed"
    $sql = "UPDATE bookings SET status='completed' WHERE id=$booking_id";
    if ($conn->query($sql) === TRUE) {
        // Đánh dấu thông báo là đã đọc
        $sql = "UPDATE notifications SET is_read=1 WHERE id=$notification_id";
        $conn->query($sql);

        header("Location: dispatcher_dashboard.php?success=1");
    } else {
        header("Location: dispatcher_dashboard.php?error=1");
    }
    exit();
}
?>
