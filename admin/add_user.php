<?php
session_start();
if (isset($_SESSION['username']) && $_SESSION['role'] !== 'admin') {
    header('Location: dashboard.php');
    exit();
}
require '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload_csv'])) {
    if (is_uploaded_file($_FILES['csv_file']['tmp_name'])) {
        $file = fopen($_FILES['csv_file']['tmp_name'], 'r');
        
        // Skip the first line (header)
        fgetcsv($file);
        
        $errors = [];
        while (($row = fgetcsv($file)) !== FALSE) {
            $username = $row[0];
            $password = md5($row[1]);
            $phone = $row[2];
            $role = $row[3];
            $unit_id = $row[4];

            $sql = "INSERT INTO users (username, password, phone, role, unit_id) VALUES ('$username', '$password', '$phone', '$role', '$unit_id')";
            if (!$conn->query($sql)) {
                $errors[] = "Error adding user $username: " . $conn->error;
            }
        }

        fclose($file);

        if (empty($errors)) {
            header("Location: dashboard.php");
            exit();
        } else {
            foreach ($errors as $error) {
                echo $error . "<br>";
            }
        }
    } 
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['upload_csv'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $phone = $_POST['phone'];
    $role = $_POST['role'];
    $unit_id = $_POST['unit_id'];

    $sql = "INSERT INTO users (username, password, phone, role, unit_id) VALUES ('$username', '$password', '$phone', '$role', '$unit_id')";
    if ($conn->query($sql) === TRUE) {
        header("Location: dashboard.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$units = $conn->query("SELECT * FROM units");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Thêm người dùng</h1>
    <div class="d-flex justify-content-between mb-3">
        <a href="dashboard.php" class="btn btn-primary">Back</a>
    </div>
    <form action="" method="post">
        <div class="mb-3">
            <label for="username" class="form-label">Tên đăng nhập</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mật khẩu</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Số điện thoại</label>
            <input type="text" class="form-control" id="phone" name="phone" required>
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">Vị trí làm việc</label>
            <select class="form-control" id="role" name="role" required>
                <option value="user">Nhân viên</option>
                <option value="manager">Quản lý</option>
                <option value="dispatcher">Điều phối</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="unit_id" class="form-label">Phòng ban</label>
            <select class="form-control" id="unit_id" name="unit_id" require>
                <?php while ($row = $units->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Thêm</button>
    </form>
    <hr>
    <h2 class="mb-4">Tải lên hồ sơ</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="csv_file" class="form-label">CSV file</label>
            <input type="file" class="form-control" id="csv_file" name="csv_file" accept=".csv">
        </div>
        <button type="submit" class="btn btn-primary" name="upload_csv">Upload CSV</button>
    </form>
</div>
</body>
</html>
