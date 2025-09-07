<?php
session_start();
include __DIR__ . '/../db.php';
include __DIR__ . '/../functions.php';

// Kiểm tra quyền admin
if (!isset($_SESSION['QuyenHan']) || $_SESSION['QuyenHan'] !== 'Admin') {
    echo "Bạn không có quyền thực hiện thao tác này!";
    exit;
}

// Lấy MaNV từ query string
if (isset($_GET['MaNV'])) {
    $maNV = $_GET['MaNV'];

    // Xóa tài khoản
    $stmt = $conn->prepare("DELETE FROM NhanVien WHERE MaNV = ?");
    $stmt->bind_param("s", $maNV);

    if ($stmt->execute()) {
        // Ghi log
        ghiLog($conn, $_SESSION['MaNV'], "Xóa nhân viên", "Đã xóa nhân viên có MaNV: $maNV");
        // Quay lại danh sách
        header("Location: tai_khoan_list.php?msg=deleted");
        exit;
    } else {
        echo "Lỗi khi xóa: " . $conn->error;
        // Ghi log lỗi
        ghiLog($conn, $_SESSION['MaNV'], "Lỗi xóa nhân viên", "Không xóa được nhân viên có MaNV: $maNV. Lỗi: ".$conn->error);
    }
} else {
    echo "Thiếu tham số MaNV!";
}
?>
