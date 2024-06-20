<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}
require '../db.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <style>
        .table-responsive { margin-top: 20px; }
        .btn { margin-right: 5px; margin-bottom: 5px; }
        .card-header, .card-body { padding: 15px; }
        .card-header h2 { margin-bottom: 0; }
    </style>
</head>
<body>
<div class="container-fluid mt-3">
    <h1 class="mb-4 text-center">Admin Dashboard</h1>
    <div class="d-flex justify-content-end">
        <a href="../auth/logout.php" class="btn btn-danger mb-3">Đăng xuất</a>
    </div>
    <div class="card mb-4">
        <div class="card-header">
            <h2>Quản lý người dùng</h2>
        </div>
        <div class="card-body">
            <div class="d-flex flex-wrap">
                <a href="add_user.php" class="btn btn-success mb-3">Thêm nhân viên</a>
                <a href="manage_units.php" class="btn btn-success mb-3">Quản lý phòng ban</a>
                <a href="requests.php" class="btn btn-success mb-3">Các yêu cầu</a>
            </div>
            <div class="table-responsive">
                <table id="userTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên đăng nhập</th>
                            <th>Vai trò</th>
                            <th>Phòng ban</th>
                            <th>Số điện thoại</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT users.*, units.name as unit_name FROM users LEFT JOIN units ON users.unit_id = units.id";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td>" . $row['username'] . "</td>";
                                echo "<td>" . $row['role'] . "</td>";
                                echo "<td>" . $row['unit_name'] ."</td>";
                                echo "<td>" . $row['phone'] ."</td>";
                                echo "<td>
                                        <a href='edit_user.php?id=" . $row['id'] . "' class='btn btn-warning'>Sửa</a>
                                        <a href='delete_user.php?id=" . $row['id'] . "' class='btn btn-danger' onclick='return confirm(\"Bạn có chắc chắn muốn xóa người dùng này không?\");'>Xóa</a>
                                      </td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#userTable').DataTable();
    });
</script>
</body>
</html>
