<?php
session_start();
if (!isset($_SESSION['manager_id']) && $_SESSION['role'] !== 'manager') {
    header("Location: ../auth/login.php");
    exit();
}
require '../db.php';

$booking_id = $_GET['id'];

$sql = "UPDATE bookings SET status='rejected' WHERE id=$booking_id";

if ($conn->query($sql) === TRUE) {
    header("Location: manager_dashboard.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
?>
