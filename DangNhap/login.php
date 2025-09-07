<?php
session_start();
include __DIR__ . '/../db.php';


$error = ''; // Biến lưu lỗi hiển thị lên form

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['Username'];
    $password = $_POST['Password'];

    $stmt = $conn->prepare("SELECT nv.MaNV, nv.HoTen, nv.Password, cv.QuyenHan 
                            FROM NhanVien nv
                            LEFT JOIN ChucVu cv ON nv.MaCV = cv.MaCV
                            WHERE nv.Username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if ($password === $row['Password']) { // plaintext password
            // Lưu session
            $_SESSION['MaNV'] = $row['MaNV'];
            $_SESSION['HoTen'] = $row['HoTen'];
            $_SESSION['QuyenHan'] = $row['QuyenHan'];

            


            // Redirect sang dashboard
            header("Location: ../DangNhap/dashboard.php");

            //log thao tác
            
$maNV = $row['MaNV'];
$ip = $_SERVER['REMOTE_ADDR'];

$log = $conn->prepare("INSERT INTO NhatKyHoatDong (MaNV, HanhDong, DiaChiIP, MoTa) VALUES (?, ?, ?, ?)");
$hanhDong = "Đăng nhập hệ thống";
$moTa = "Người dùng ".$row['Username']." đăng nhập thành công.";
$log->bind_param("isss", $maNV, $hanhDong, $ip, $moTa);
$log->execute();
            exit;
        } else {
            $error = 'Sai mật khẩu';
        }
    } else {
        $error = 'Tài khoản không tồn tại';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Đăng nhập</title>
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
