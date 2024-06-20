<?php
session_start();
if (!isset($_SESSION['user_id']) && $_SESSION['role'] !== 'user') {
    header("Location: ../auth/login.php");
    exit();
}
require '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $booking_id = $_POST['booking_id'];
    $user_id = $_SESSION['user_id'];

    //cập nhật trạng thái chuyến đi là completed
    $sql = "UPDATE bookings SET status='completed' WHERE id=$booking_id";
    if ($conn->query($sql) === TRUE) {
        //lấy thông tin xe và tài xế từ booking
        $sql = "SELECT vehicle_id, driver_id FROM bookings WHERE id=$booking_id";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $vehicle_id = $row['vehicle_id'];
            $driver_id = $row['driver_id'];

            //cập nhật trạng thái xe và tài xế
            $conn->query("UPDATE vehicles SET status='available' WHERE id=$vehicle_id");
            $conn->query("UPDATE drivers SET status='available' WHERE id=$driver_id");
        }
        header("Location: user_dashboard.php?success=1");
    } else {
        header("Location: user_dashboard.php?error=1");
    }

    // Tạo thông báo yêu cầu hoàn thành chuyến đi
    $message = "User ID: $user_id requested to complete the ride. Booking ID: $booking_id";
    $sql = "INSERT INTO notifications (user_id, message) VALUES ($user_id, '$message')";

    if ($conn->query($sql) === TRUE) {
        header("Location: user_dashboard.php?success=1");
    } else {
        header("Location: user_dashboard.php?error=1");
    }
    exit();
}
?>
