<?php
session_start();
include('../db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $booking_id = $_POST['booking_id'];
    $vehicle_id = $_POST['vehicle_id'];
    $driver_id = $_POST['driver_id'];
    
    // Cập nhật thông tin đặt xe
    $query = "UPDATE bookings SET vehicle_id = $vehicle_id, driver_id = $driver_id, status = 'assigned' WHERE id = $booking_id";
    if (mysqli_query($conn, $query)) {
        // Cập nhật trạng thái xe
        $update_vehicle_status = "UPDATE vehicles SET status = 'busy' WHERE id = $vehicle_id";
        mysqli_query($conn, $update_vehicle_status);

        // Cập nhật trạng thái tài xế
        $update_driver_status = "UPDATE drivers SET status = 'busy' WHERE id = $driver_id";
        mysqli_query($conn, $update_driver_status);

        // Lấy thông tin người dùng để gửi thông báo
        $booking_query = "SELECT user_id FROM bookings WHERE id = $booking_id";
        $booking_result = mysqli_query($conn, $booking_query);
        $booking_row = mysqli_fetch_assoc($booking_result);
        $user_id = $booking_row['user_id'];

        // Gửi thông báo cho người dùng (có thể là email hoặc thông báo hệ thống)
        // Ví dụ đơn giản: Lưu thông báo vào bảng notifications
        $notification_query = "INSERT INTO notifications (user_id, message) VALUES ($user_id, 'Your booking has been assigned a vehicle and driver.')";
        mysqli_query($conn, $notification_query);

        header("Location: dispatcher_dashboard.php?success=1");
    } else {
        header("Location: dispatcher_dashboard.php?error=1");
    }
}
?>
