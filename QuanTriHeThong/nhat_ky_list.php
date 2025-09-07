<?php
session_start();
include __DIR__ . '/../db.php';

// Kiểm tra quyền admin
if (!isset($_SESSION['QuyenHan']) || $_SESSION['QuyenHan'] !== 'Admin') {
    die("Không có quyền truy cập!");
}

$result = $conn->query("SELECT nk.MaNK, nv.HoTen, nk.HanhDong, nk.ThoiGian, nk.DiaChiIP, nk.MoTa
                        FROM NhatKyHoatDong nk
                        JOIN NhanVien nv ON nk.MaNV = nv.MaNV
                        ORDER BY nk.ThoiGian DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nhật ký hoạt động</title>
</head>
<body>
    <h2>Danh sách nhật ký hoạt động</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>Mã NK</th>
            <th>Nhân viên</th>
            <th>Hành động</th>
            <th>Thời gian</th>
            <th>IP</th>
            <th>Mô tả</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['MaNK'] ?></td>
            <td><?= $row['HoTen'] ?></td>
            <td><?= $row['HanhDong'] ?></td>
            <td><?= $row['ThoiGian'] ?></td>
            <td><?= $row['DiaChiIP'] ?></td>
            <td><?= $row['MoTa'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
