<?php
require '../db.php';

$id = $_GET['id'];
$sql = "SELECT * FROM users WHERE id=$id";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Chỉnh sửa</h1>
    <div class="d-flex justify-content-between mb-3">
        <a href="dashboard.php" class="btn btn-primary">Back</a>
    </div>
    <form action="update_user.php" method="post">
        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
        <div class="mb-3">
            <label for="username" class="form-label">Tên đăng nhập</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo $user['username']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">Vai trò</label>
            <select class="form-control" id="role" name="role" required>
                <option value="user" <?php if ($user['role'] == 'user') echo 'selected'; ?>>Nhân viên</option>
                <option value="manager" <?php if ($user['role'] == 'manager') echo 'selected'; ?>>Quản lý</option>
                <option value="dispatcher" <?php if ($user['role'] == 'dispatcher') echo 'selected'; ?>>Điều phối</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="unit" class="form-label">Phòng ban</label>
            <select class="form-control" id="unit" name="unit_id" required>
                <?php
                $sql = "SELECT * FROM units";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    $selected = $row['id'] == $user['unit_id'] ? 'selected' : '';
                    echo "<option value='{$row['id']}' $selected>{$row['name']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Số điện thoại</label>
            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $user['phone']; ?>" >
        </div>
        <button type="submit" class="btn btn-success">Cập nhật</button>
    </form>
</div>
</body>
</html>
