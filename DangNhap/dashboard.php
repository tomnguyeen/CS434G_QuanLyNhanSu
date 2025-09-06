<?php
session_start();
if (!isset($_SESSION['MaNV'])) {
    header("Location: login.php");
    exit;
}

// Kiểm tra quyền truy cập
if ($_SESSION['QuyenHan'] != 'Admin') {
    echo "Bạn không có quyền truy cập vào trang này.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <meta charset="utf-8">
</head>
<body>
    <h2>Chào <?php echo $_SESSION['HoTen']; ?> (<?php echo $_SESSION['QuyenHan']; ?>)</h2>
    <p><a href="tai_khoan_list.php">Quản lý tài khoản</a></p>
    <p><a href="logout.php">Đăng xuất</a></p>
</body>
</html>
