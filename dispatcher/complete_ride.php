<?php
session_start();
if (!isset($_SESSION['dispatcher_id']) && $_SESSION['role'] !== 'dispatcher') {
    header("Location: ../auth/login.php");
    exit();
}

require '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['notification_id']) && isset($_POST['booking_id'])) {
    $notification_id = $_POST['notification_id'];
    $booking_id = $_POST['booking_id'];

    // Lấy thông tin xe và tài xế từ bảng bookings
    $sql = "SELECT vehicle_id, driver_id FROM bookings WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $booking = $result->fetch_assoc();

    if ($booking) {
        $vehicle_id = $booking['vehicle_id'];
        $driver_id = $booking['driver_id'];

        // Cập nhật trạng thái của xe và tài xế
        $sql_update_vehicle = "UPDATE vehicles SET status = 'available' WHERE id = ?";
        $stmt_vehicle = $conn->prepare($sql_update_vehicle);
        $stmt_vehicle->bind_param("i", $vehicle_id);
        $stmt_vehicle->execute();

        $sql_update_driver = "UPDATE drivers SET status = 'available' WHERE id = ?";
        $stmt_driver = $conn->prepare($sql_update_driver);
        $stmt_driver->bind_param("i", $driver_id);
        $stmt_driver->execute();

        // Cập nhật trạng thái của booking
        $sql_update_booking = "UPDATE bookings SET status = 'completed' WHERE id = ?";
        $stmt_booking = $conn->prepare($sql_update_booking);
        $stmt_booking->bind_param("i", $booking_id);
        $stmt_booking->execute();

        // Đánh dấu thông báo là đã đọc
        $sql_update_notification = "UPDATE notifications SET is_read = 1 WHERE id = ?";
        $stmt_notification = $conn->prepare($sql_update_notification);
        $stmt_notification->bind_param("i", $notification_id);
        $stmt_notification->execute();

        header("Location: dispatcher_dashboard.php?success=1");
    } else {
        header("Location: dispatcher_dashboard.php?error=1");
    }
} else {
    header("Location: dispatcher_dashboard.php?error=1");
}
?>
