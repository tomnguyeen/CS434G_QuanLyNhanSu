<?php
include 'db.php';
header('Content-Type: application/json');

$sql = "SELECT nv.MaNV, nv.HoTen, nv.Username, cv.TenCV AS ChucVu, cv.QuyenHan
        FROM NhanVien nv
        LEFT JOIN ChucVu cv ON nv.MaCV = cv.MaCV";

$result = $conn->query($sql);
$data = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);
?>
