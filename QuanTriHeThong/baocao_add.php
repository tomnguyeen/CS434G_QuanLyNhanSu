<?php
session_start();
include __DIR__ . '/../db.php';
include __DIR__ . '/../functions.php';

if (!isset($_SESSION['MaNV'])) {
    die("Vui lòng đăng nhập!");
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $TieuDe  = trim($_POST['TieuDe']);
    $Loai    = $_POST['Loai'];
    $MucDo   = $_POST['MucDo'];
    $MoTa    = trim($_POST['MoTa']);
    $NguoiTao = $_SESSION['MaNV'];

    if ($TieuDe === '') {
        $error = "Tiêu đề không được để trống.";
    } else {
        $stmt = $conn->prepare("INSERT INTO BaoCaoHeThong (TieuDe, Loai, MucDo, MoTa, NguoiTao) VALUES (?,?,?,?,?)");
        $stmt->bind_param("sssss", $TieuDe, $Loai, $MucDo, $MoTa, $NguoiTao);
        if ($stmt->execute()) {
            $success = "✅ Gửi báo cáo thành công!";
            // Ghi log
            ghiLog($conn, $_SESSION['MaNV'], "Gửi báo cáo hệ thống", "Đã gửi báo cáo: $TieuDe");
        } else {
            $error = "❌ Lỗi: " . $conn->error;
            // Ghi log lỗi
            ghiLog($conn, $_SESSION['MaNV'], "Lỗi gửi báo cáo hệ thống", "Không gửi được báo cáo: $TieuDe. Lỗi: ".$conn->error);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Gửi báo cáo hệ thống</title>
</head>
<body>
  <h2>Gửi báo cáo hệ thống</h2>
  <?php if($error) echo "<p style='color:red;'>$error</p>"; ?>
  <?php if($success) echo "<p style='color:green;'>$success</p>"; ?>

  <form method="POST">
    <label>Tiêu đề:</label><br>
    <input type="text" name="TieuDe" required style="width:400px"><br><br>

    <label>Loại:</label>
    <select name="Loai" required>
      <option value="Bug">Bug</option>
      <option value="YeuCau">Yêu cầu</option>
      <option value="CapNhat">Cập nhật</option>
      <option value="BaoTri">Bảo trì</option>
    </select>
    &nbsp;&nbsp;

    <label>Mức độ:</label>
    <select name="MucDo" required>
      <option value="Thap">Thấp</option>
      <option value="TrungBinh">Trung bình</option>
      <option value="Cao">Cao</option>
      <option value="KhanCap">Khẩn cấp</option>
    </select><br><br>

    <label>Mô tả:</label><br>
    <textarea name="MoTa" rows="5" cols="80"></textarea><br><br>

    <button type="submit">Gửi báo cáo</button>
  </form>

  <br>
  <a href="baocao_list.php">Xem danh sách báo cáo</a> |
  <a href="../DangNhap/dashboard.php">Về Dashboard</a>
</body>
</html>
