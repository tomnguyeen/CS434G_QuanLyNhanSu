<?php
include 'db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $manv = $_POST['MaNV'];

    $stmt = $conn->prepare("DELETE FROM NhanVien WHERE MaNV=?");
    $stmt->bind_param("i", $manv);

    if ($stmt->execute()) {
        echo json_encode(["status"=>"success","message"=>"Xóa tài khoản thành công"]);
    } else {
        echo json_encode(["status"=>"error","message"=>$stmt->error]);
    }
}
?>
