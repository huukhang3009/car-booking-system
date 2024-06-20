<?php
session_start();
if (!isset($_SESSION['dispatcher_id']) && $_SESSION['role'] !== 'dispatcher') {
    header("Location: ../auth/login.php");
    exit();
}
require '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message = $_POST['message'];

    // Lấy tất cả người dùng
    $sql = "SELECT id FROM users WHERE role='user'";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $user_id = $row['id'];
        // Gửi thông báo cho người dùng
        $conn->query("INSERT INTO notifications (user_id, message) VALUES ($user_id, '$message')");
    }

    header("Location: dispatcher_dashboard.php?success=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notify Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Thông báo</h1>
    <a href="dispatcher_dashboard.php" class="btn btn-primary mb-3">Back</a>
    <form action="notify_users.php" method="post">
        <div class="mb-3">
            <label for="message" class="form-label">Message</label>
            <textarea class="form-control" id="message" name="message" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Gửi</button>
    </form>
</div>
</body>
</html>
