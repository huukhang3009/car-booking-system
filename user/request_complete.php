<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header("Location: ../auth/login.php");
    exit();
}
require '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_SESSION['username'];
    $booking_id = $_POST['booking_id'];

    // Lấy user_id từ bảng users
    $sql_get_user_id = "SELECT id FROM users WHERE username = ?";
    $stmt_get_user_id = $conn->prepare($sql_get_user_id);
    $stmt_get_user_id->bind_param("s", $username);
    $stmt_get_user_id->execute();
    $result_get_user_id = $stmt_get_user_id->get_result();

    if ($result_get_user_id->num_rows > 0) {
        $row = $result_get_user_id->fetch_assoc();
        $user_id = $row['id'];

        // Kiểm tra xem đặt xe có thuộc về người dùng hiện tại không
        $sql_check_booking = "SELECT id FROM bookings WHERE id = ? AND user_id = ?";
        $stmt_check_booking = $conn->prepare($sql_check_booking);
        $stmt_check_booking->bind_param("ii", $booking_id, $user_id);
        $stmt_check_booking->execute();
        $result_check_booking = $stmt_check_booking->get_result();

        if ($result_check_booking->num_rows > 0) {
            // Cập nhật trạng thái của chuyến đi thành "completed"
            $sql_update_booking = "UPDATE bookings SET status = 'completed' WHERE id = ?";
            $stmt_update_booking = $conn->prepare($sql_update_booking);
            $stmt_update_booking->bind_param("i", $booking_id);

            if ($stmt_update_booking->execute()) {
                header("Location: user_dashboard.php");
                exit();
            } else {
                echo "Error updating booking: " . $stmt_update_booking->error;
            }
        } else {
            echo "Error: Unauthorized action."; // This message should not be shown in production
        }
    } else {
        echo "Error: User not found."; // This message should not be shown in production
    }
}
?>
