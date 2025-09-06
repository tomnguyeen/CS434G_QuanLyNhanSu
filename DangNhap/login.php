<?php
session_start();
include 'db.php';

$error = ''; // Biến lưu lỗi

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['Username'];
    $password = $_POST['Password'];

    // Lấy thông tin tài khoản từ DB
    $stmt = $conn->prepare("SELECT nv.MaNV, nv.HoTen, nv.Password, cv.QuyenHan 
                            FROM NhanVien nv
                            LEFT JOIN ChucVu cv ON nv.MaCV = cv.MaCV
                            WHERE nv.Username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // So sánh password plaintext
        if ($password === $row['Password']) {
            // Lưu session
            $_SESSION['MaNV'] = $row['MaNV'];
            $_SESSION['HoTen'] = $row['HoTen'];
            $_SESSION['QuyenHan'] = $row['QuyenHan'];

            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Sai mật khẩu";
        }
    } else {
        $error = "Tài khoản không tồn tại";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Đăng nhập</title>
    <meta charset="utf-8">
</head>
<body>
    <h2>Đăng nhập</h2>
    <?php if(!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST">
        <label>Username: </label>
        <input type="text" name="Username" required><br><br>
        <label>Password: </label>
        <input type="password" name="Password" required><br><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>
