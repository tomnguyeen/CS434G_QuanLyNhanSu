<?php
session_start();
include __DIR__ . '/../db.php';
include __DIR__ . '/../functions.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $maNV = $_POST["MaNV"];
    $hoTen = $_POST["HoTen"];
    $maCV = $_POST["MaCV"];
    $username = $_POST["Username"];
    $password = $_POST["Password"];

    // Kiểm tra trùng username
    $check = $conn->prepare("SELECT * FROM NhanVien WHERE Username=?");
    $check->bind_param("s", $username);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $error = "Tên đăng nhập đã tồn tại!";
    } else {
        $stmt = $conn->prepare("INSERT INTO nhanvien (MaNV, HoTen, MaCV, Username, Password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $maNV, $hoTen, $maCV, $username, $password);

        if ($stmt->execute()) {
            $success = "Thêm tài khoản thành công!";
            ghiLog($conn, $_SESSION['MaNV'], "Thêm nhân viên", "Đã thêm nhân viên $hoTen (MaNV: $maNV)");
}     
         else {
            $error = "Lỗi khi thêm tài khoản: " . $conn->error;
            ghiLog($conn, $_SESSION['MaNV'], "Lỗi thêm nhân viên", "Không thêm được nhân viên $hoTen (MaNV: $maNV). Lỗi: ".$conn->error);
        }
    }
}
    
    
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Thêm tài khoản</title>
</head>
<body>
    <h2>Thêm tài khoản</h2>
    <?php if($error) echo "<p style='color:red;'>$error</p>"; ?>
    <?php if($success) echo "<p style='color:green;'>$success</p>"; ?>

    <form method="POST">
        <label>MaNV: </label>
        <input type="text" name="MaNV" required><br><br>

        <label>Họ Tên: </label>
        <input type="text" name="HoTen" required><br><br>

        <label>Chức vụ: </label>
        <select name="MaCV" required>
        <?php
        // lấy danh sách chức vụ từ bảng ChucVu
        $result = $conn->query("SELECT MaCV, TenCV FROM ChucVu");
        while ($row = $result->fetch_assoc()) {
            echo "<option value='".$row['MaCV']."'>".$row['TenCV']."</option>";
        }
        ?>
    </select><br><br>

        <label>Username: </label>
        <input type="text" name="Username" required><br><br>

        <label>Mật khẩu: </label>
        <input type="password" name="Password" required><br><br>

        <button type="submit">Thêm</button>
    </form>

    <br>
    <a href="tai_khoan_list.php">Quay lại danh sách</a>
    
</body>
</html>
