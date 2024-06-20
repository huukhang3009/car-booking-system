<?php
session_start();
if (!isset($_SESSION['dispatcher_id']) && $_SESSION['role'] !== 'dispatcher') {
    header("Location: ../auth/login.php");
    exit();
}
require '../db.php';

$dispatcher_id = $_SESSION['dispatcher_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dispatcher Notifications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Dispatcher Notifications</h1>
    <a href="dispatcher_dashboard.php" class="btn btn-primary mb-3">Back</a>
    <a href="../auth/logout.php" class="btn btn-danger mb-3">Logout</a>

    <div class="card mb-4">
        <div class="card-header">
            <h2>Ride Completion Notifications</h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Message</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM notifications WHERE user_id=? AND is_read=0";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param('i', $dispatcher_id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td>" . $row['message'] . "</td>";
                                echo "<td>
                                        <form action='confirm_completion.php' method='post' style='display:inline;'>
                                            <input type='hidden' name='notification_id' value='" . $row['id'] . "'>
                                            <button type='submit' class='btn btn-success'>Xác nhận</button>
                                        </form>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3'>No new notifications</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
