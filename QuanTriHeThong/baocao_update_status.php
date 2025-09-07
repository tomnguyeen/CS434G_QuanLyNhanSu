<?php
session_start();
include __DIR__ . '/../db.php';
include __DIR__ . '/../functions.php';

if (!isset($_SESSION['MaNV'])) {
    die("Vui lòng đăng nhập!");
}
if (!isset($_SESSION['QuyenHan']) || $_SESSION['QuyenHan'] !== 'Admin') {
    die("Bạn không có quyền thực hiện thao tác này!");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $MaBC      = $_POST['MaBC'];
    $TrangThai = $_POST['TrangThai'];
    $GhiChu    = trim($_POST['GhiChu']);
    $NguoiXuLy = $_SESSION['MaNV'];

    $stmt = $conn->prepare("UPDATE BaoCaoHeThong 
                            SET TrangThai=?, NguoiXuLy=?, GhiChu=? 
                            WHERE MaBC=?");
    $stmt->bind_param("sssi", $TrangThai, $NguoiXuLy, $GhiChu, $MaBC);

    if ($stmt->execute()) {
        // Ghi log
        ghiLog($conn, $_SESSION['MaNV'], "Cập nhật trạng thái báo cáo", "Đã cập nhật trạng thái báo cáo MaBC: $MaBC thành $TrangThai");

        header("Location: baocao_list.php");
        exit;
    } else {
        echo "Lỗi: " . $conn->error;
        // Ghi log lỗi
        ghiLog($conn, $_SESSION['MaNV'], "Lỗi cập nhật trạng thái báo cáo", "Không cập nhật được trạng thái báo cáo MaBC: $MaBC. Lỗi: ".$conn->error);
    }
} else {
    echo "Phải dùng POST";
}
