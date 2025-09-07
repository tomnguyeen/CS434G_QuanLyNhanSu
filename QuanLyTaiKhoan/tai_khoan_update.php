<?php
session_start();
include __DIR__ . '/../db.php';
include __DIR__ . '/../functions.php';

$error = '';
$success = '';

// Lấy id (MaNV) từ URL
if (!isset($_GET['MaNV'])) {
    die("Thiếu mã nhân viên!");
}
$maNV = $_GET['MaNV'];

// Lấy thông tin nhân viên hiện tại
$stmt = $conn->prepare("SELECT * FROM NhanVien WHERE MaNV = ?");
$stmt->bind_param("s", $maNV);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    die("Không tìm thấy nhân viên!");
}
$nhanvien = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hoTen = $_POST["HoTen"];
    $maCV = $_POST["MaCV"];
    $username = $_POST["Username"];
    $password = $_POST["Password"];

    // Kiểm tra trùng username (trừ chính nhân viên này)
    $check = $conn->prepare("SELECT * FROM NhanVien WHERE Username=? AND MaNV <> ?");
    $check->bind_param("ss", $username, $maNV);
    $check->execute();
    $resCheck = $check->get_result();

    if ($resCheck->num_rows > 0) {
        $error = "Tên đăng nhập đã tồn tại!";
    } else {
        $stmt = $conn->prepare("UPDATE NhanVien SET HoTen=?, MaCV=?, Username=?, Password=? WHERE MaNV=?");
        $stmt->bind_param("sssss", $hoTen, $maCV, $username, $password, $maNV);

        if ($stmt->execute()) {
            $success = "Cập nhật tài khoản thành công!";
            // refresh lại dữ liệu
            $nhanvien['HoTen'] = $hoTen;
            $nhanvien['MaCV'] = $maCV;
            $nhanvien['Username'] = $username;
            $nhanvien['Password'] = $password;
            // Ghi log
            ghiLog($conn, $_SESSION['MaNV'], "Cập nhật nhân viên", "Đã cập nhật nhân viên $hoTen (MaNV: $maNV)");
        } else {
            $error = "Lỗi khi cập nhật: " . $conn->error;
            // Ghi log lỗi
            ghiLog($conn, $_SESSION['MaNV'], "Lỗi cập nhật nhân viên", "Không cập nhật được nhân viên $hoTen (MaNV: $maNV). Lỗi: ".$conn->error);
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cập nhật tài khoản</title>
</head>
<body>
    <h2>Cập nhật tài khoản</h2>
    <?php if($error) echo "<p style='color:red;'>$error</p>"; ?>
    <?php if($success) echo "<p style='color:green;'>$success</p>"; ?>

    <form method="POST">
        <label>MaNV: </label>
        <input type="text" name="MaNV" value="<?php echo htmlspecialchars($nhanvien['MaNV']); ?>" readonly><br><br>

        <label>Họ Tên: </label>
        <input type="text" name="HoTen" value="<?php echo htmlspecialchars($nhanvien['HoTen']); ?>" required><br><br>

        <label>Chức vụ: </label>
        <select name="MaCV" required>
        <?php
        // Lấy danh sách chức vụ
        $result = $conn->query("SELECT MaCV, TenCV FROM ChucVu");
        while ($row = $result->fetch_assoc()) {
            $selected = ($row['MaCV'] == $nhanvien['MaCV']) ? "selected" : "";
            echo "<option value='".$row['MaCV']."' $selected>".$row['TenCV']."</option>";
        }
        ?>
        </select><br><br>

        <label>Username: </label>
        <input type="text" name="Username" value="<?php echo htmlspecialchars($nhanvien['Username']); ?>" required><br><br>

        <label>Mật khẩu: </label>
        <input type="text" name="Password" value="<?php echo htmlspecialchars($nhanvien['Password']); ?>" required><br><br>

        <button type="submit">Cập nhật</button>
    </form>

    <br>
    <a href="tai_khoan_list.php">Quay lại danh sách</a>
</body>
</html>
