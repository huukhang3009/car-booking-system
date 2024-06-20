<?php
session_start();
include('../db.php'); // Đường dẫn tới tệp kết nối cơ sở dữ liệu

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Kiểm tra xem các khóa 'username' và 'password' có tồn tại trong mảng $_POST không
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = md5($_POST['password']); // Sử dụng MD5 để mã hóa mật khẩu

        // Kiểm tra đăng nhập
        $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            // Lưu thông tin người dùng vào session
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['user_id'] = $user['user_id'];
            
            // Chuyển hướng người dùng dựa trên vai trò của họ
            switch ($user['role']) {
                case 'admin':
                    header("Location: ../admin/dashboard.php");
                    break;
                case 'dispatcher':
                    header("Location: ../dispatcher/dispatcher_dashboard.php");
                    break;
                case 'user':
                    header("Location: ../user/user_dashboard.php");
                    break;
                case 'manager':
                    header("Location: ../manager/manager_dashboard.php");
                    break;
                default:
                    $error = "Vai trò không hợp lệ!";
            }
            exit();
        } else {
            $error = "Sai thông tin đăng nhập!";
        }
    } else {
        $error = "Vui lòng nhập tên đăng nhập và mật khẩu!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-4">
            <h3 class="text-center">Đăng nhập</h3>
            <div class="d-flex justify-content-between mb-3">
                <a href="../index.php" class="btn btn-primary">Quay lại</a>
            </div>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            <form action="login.php" method="post">
                <div class="mb-3">
                    <label for="username" class="form-label">Tên đăng nhập</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mật khẩu</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary">Đăng nhập</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
