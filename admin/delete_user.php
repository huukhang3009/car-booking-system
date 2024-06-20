<?php
require '../db.php';

$id = $_GET['id'];
$sql = "DELETE FROM users WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    header("Location: dashboard.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
?>
