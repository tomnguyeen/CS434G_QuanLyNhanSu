<?php
// C:\xampp\htdocs\QuanLyNhanSu\DangKy\register.php
session_start();
$conn = new mysqli('localhost', 'root', '', 'quanlynhansu');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['Username'];
    $password = $_POST['Password'];
  // $email = $_POST['Email'];

    // Chuẩn bị câu lệnh INSERT
    $stmt = $conn->prepare("INSERT INTO nhanvien (Username, Password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);

    if ($stmt->execute()) {
        echo "Đăng ký thành công. <a href='../DangNhap/login.php'>Đăng nhập</a>";
    } else {
        echo "Lỗi: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Đăng ký</title>
</head>
<body>
    <h2>Đăng ký tài khoản</h2>
    <form method="POST">
        Username: <input type="text" name="Username" required><br>
        <!-- Email: <input type="email" name="email" required><br> -->
        Password: <input type="password" name="Password" required><br>
        <button type="submit">Đăng ký</button>
    </form>
</body>
</html>
