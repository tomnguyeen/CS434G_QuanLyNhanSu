<?php
function ghiLog($conn, $maNV, $hanhDong, $moTa) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $log = $conn->prepare("INSERT INTO NhatKyHoatDong (MaNV, HanhDong, DiaChiIP, MoTa) VALUES (?, ?, ?, ?)");
    $log->bind_param("isss", $maNV, $hanhDong, $ip, $moTa);
    $log->execute();
}
?>
