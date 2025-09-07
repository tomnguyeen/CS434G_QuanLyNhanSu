<?php
$servername = "localhost";
$username = "root";    // mặc định XAMPP user là root
$password = "";        // mặc định XAMPP không có password
$dbname = "quanlynhansu";

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>
