<?php
session_start();
include 'db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['QuyenHan']) || $_SESSION['QuyenHan'] !== 'Admin') {
    echo json_encode(['status'=>'error','message'=>'Không có quyền']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $HoTen = $_POST['HoTen'];
    $Username = $_POST['Username'];
    $Password = $_POST['Password']; // plaintext
    $MaCV = $_POST['MaCV'];

    $stmt = $conn->prepare("SELECT * FROM NhanVien WHERE Username=?");
    $stmt->bind_param("s", $Username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['status'=>'error','message'=>'Username đã tồn tại']);
    } else {
        $stmt = $conn->prepare("INSERT INTO NhanVien (HoTen, Username, Password, MaCV) VALUES (?,?,?,?)");
        $stmt->bind_param("sssi", $HoTen, $Username, $Password, $MaCV);
        if ($stmt->execute()) {
            echo json_encode(['status'=>'success','message'=>'Tài khoản thêm thành công']);
        } else {
            echo json_encode(['status'=>'error','message'=>$stmt->error]);
        }
    }
} else {
    echo json_encode(['status'=>'error','message'=>'Phải dùng POST']);
}
?>
