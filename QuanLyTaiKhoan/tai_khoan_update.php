<?php
include 'db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $manv = $_POST['MaNV'];
    $hoten = $_POST['HoTen'] ?? NULL;
    $username = $_POST['Username'] ?? NULL;
    $password = $_POST['Password'] ?? NULL;
    $macv = $_POST['MaCV'] ?? NULL;

    $fields = [];
    $params = [];
    $types = "";

    if ($hoten) { $fields[] = "HoTen=?"; $params[] = $hoten; $types.="s"; }
    if ($username) { $fields[] = "Username=?"; $params[] = $username; $types.="s"; }
    if ($password) { 
        $fields[] = "Password=?"; 
        $params[] = password_hash($password, PASSWORD_BCRYPT); 
        $types.="s"; 
    }
    if ($macv) { $fields[] = "MaCV=?"; $params[] = $macv; $types.="i"; }

    if (count($fields) > 0) {
        $sql = "UPDATE NhanVien SET ".implode(",", $fields)." WHERE MaNV=?";
        $stmt = $conn->prepare($sql);
        $types .= "i";
        $params[] = $manv;

        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            echo json_encode(["status"=>"success","message"=>"Cập nhật thành công"]);
        } else {
            echo json_encode(["status"=>"error","message"=>$stmt->error]);
        }
    } else {
        echo json_encode(["status"=>"error","message"=>"Không có trường nào để cập nhật"]);
    }
}
?>
