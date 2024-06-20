<?php
session_start();
if (!isset($_SESSION['dispatcher_id']) && $_SESSION['role'] !== 'dispatcher') {
    header("Location: ../auth/login.php");
    exit();
}
require '../db.php';

$vehicle_id = $_GET['id'];

$sql = "DELETE FROM vehicles WHERE id=$vehicle_id";

if ($conn->query($sql) === TRUE) {
    header("Location: dispatcher_dashboard.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
?>
