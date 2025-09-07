<?php
session_start();
include __DIR__ . '/../db.php';

// Kiểm tra quyền admin
if (!isset($_SESSION['QuyenHan']) || $_SESSION['QuyenHan'] !== 'Admin') {
    echo "Bạn không có quyền truy cập trang này!";
    exit;
}

// Lấy danh sách tài khoản
$sql = "SELECT nv.MaNV, nv.HoTen, nv.Username, nv.MaCV, cv.TenCV
        FROM NhanVien nv
        LEFT JOIN ChucVu cv ON nv.MaCV = cv.MaCV";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách tài khoản</title>
    <style>
        table { border-collapse: collapse; width: 80%; margin: 20px auto; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
        th { background: #f2f2f2; }
        a { text-decoration: none; color: blue; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Danh sách tài khoản</h2>
    <table>
        <tr>
            <th>Mã NV</th>
            <th>Họ Tên</th>
            <th>Tên đăng nhập</th>
            <th>Mã CV</th>
            <th>Chức vụ</th>
            <th>Hành động</th>
        </tr>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['MaNV']); ?></td>
                    <td><?php echo htmlspecialchars($row['HoTen']); ?></td>
                    <td><?php echo htmlspecialchars($row['Username']); ?></td>
                    <td><?php echo htmlspecialchars($row['MaCV']); ?></td>
                    <td><?php echo htmlspecialchars($row['TenCV']); ?></td>
                    <td>
                        <a href="tai_khoan_update.php?MaNV=<?php echo urlencode($row['MaNV']); ?>">Sửa</a> | 
                        <a href="tai_khoan_delete.php?MaNV=<?php echo urlencode($row['MaNV']); ?>" onclick="return confirm('Bạn có chắc muốn xóa?');">Xóa</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6">Chưa có tài khoản nào</td></tr>
        <?php endif; ?>
    </table>
    <div style="text-align:center;">
        <a href="tai_khoan_add.php">➕ Thêm tài khoản mới</a>
        <a href="../DangNhap/dashboard.php">Quay lại</a>
    </div>
</body>
</html>
