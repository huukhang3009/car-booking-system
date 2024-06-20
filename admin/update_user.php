<?php
require '../db.php';

$id = $_POST['id'];
$username = $_POST['username'];
$role = $_POST['role'];
$unit_id = $_POST['unit_id'];
$phone = $_POST['phone'];

$sql = "UPDATE users SET username='$username', role='$role', unit_id='$unit_id', phone='$phone' WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    header("Location: dashboard.php"); // Redirect to the admin dashboard or another page
} else {
    echo "Error updating record: " . $conn->error;
}

$conn->close();
?>
