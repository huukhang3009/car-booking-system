<?php
session_start();
if (!isset($_SESSION['dispatcher_id']) && $_SESSION['role'] !== 'dispatcher') {
    header("Location: ../auth/login.php");
    exit();
}
require '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $notification_id = $_POST['notification_id'];

    // Mark the notification as read
    $sql = "UPDATE notifications SET is_read=1 WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $notification_id);
    $stmt->execute();

    // Extract booking_id from the message (assuming message format: "User has completed the ride. Booking ID: X")
    $sql = "SELECT message FROM notifications WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $notification_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $notification = $result->fetch_assoc();
    preg_match('/Booking ID: (\d+)/', $notification['message'], $matches);
    $booking_id = $matches[1];

    // Update the booking status to completed
    $sql = "UPDATE bookings SET status='completed' WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $booking_id);

    if ($stmt->execute()) {
        // Fetch the vehicle_id and driver_id from the booking
        $sql = "SELECT vehicle_id, driver_id FROM bookings WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $booking_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $booking = $result->fetch_assoc();

        // Update the vehicle and driver status to available
        $vehicle_id = $booking['vehicle_id'];
        $driver_id = $booking['driver_id'];

        $sql = "UPDATE vehicles SET status='available' WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $vehicle_id);
        $stmt->execute();

        $sql = "UPDATE drivers SET status='available' WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $driver_id);
        $stmt->execute();

        header("Location: dispatcher_notifications.php?success=ride_confirmed");
    } else {
        header("Location: dispatcher_notifications.php?error=ride_confirmation_failed");
    }
    exit();
} else {
    header("Location: dispatcher_notifications.php");
    exit();
}
?>
