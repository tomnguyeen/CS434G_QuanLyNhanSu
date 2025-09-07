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
    <meta charset="utf-8">
    <title>Dashboard</title>
</head>
<body>
    <h2>Xin chào, <?php echo $_SESSION['HoTen']; ?>!</h2>
    <p>Quyền: <?php echo $_SESSION['QuyenHan']; ?></p>

    <h3>Quản lý tài khoản</h3>
    <ul>
        <li><a href="../QuanLyTaiKhoan/tai_khoan_list.php">Danh sách tài khoản</a></li>
        <!-- <li><a href="../QuanLyTaiKhoan/tai_khoan_add.php">Thêm tài khoản</a></li> -->
        <!-- <li><a href="../QuanLyTaiKhoan/tai_khoan_update.php">Cập nhật tài khoản</a></li>         -->
</li>
    </ul>

    <a href="logout.php">Đăng xuất</a>

    <h4>Quản trị hệ thống</h4>
<ul>
  <li><a href="../QuanTriHeThong/baocao_add.php">Gửi báo cáo (Bảo trì/Cập nhật)</a></li>
  <li><a href="../QuanTriHeThong/baocao_list.php">Danh sách báo cáo</a></li>
  <li><a href="../QuanTriHeThong/nhat_ky_list.php">Nhật ký hoạt động</a></li>
</ul>

</body>
</html>
