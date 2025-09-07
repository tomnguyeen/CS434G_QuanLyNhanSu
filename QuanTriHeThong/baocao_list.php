<?php
session_start();
include __DIR__ . '/../db.php';

if (!isset($_SESSION['MaNV'])) {
    die("Vui lòng đăng nhập!");
}

$isAdmin = (isset($_SESSION['QuyenHan']) && $_SESSION['QuyenHan'] === 'Admin');

$TrangThai = isset($_GET['TrangThai']) ? $_GET['TrangThai'] : '';
$Loai      = isset($_GET['Loai']) ? $_GET['Loai'] : '';

$where = [];
if ($TrangThai !== '') $where[] = "b.TrangThai = '".$conn->real_escape_string($TrangThai)."'";
if ($Loai !== '')      $where[] = "b.Loai = '".$conn->real_escape_string($Loai)."'";
if (!$isAdmin)         $where[] = "b.NguoiTao = '".$conn->real_escape_string($_SESSION['MaNV'])."'";

$sql = "SELECT b.*, 
               nv1.HoTen AS TenNguoiTao,
               nv2.HoTen AS TenNguoiXuLy
        FROM BaoCaoHeThong b
        LEFT JOIN NhanVien nv1 ON nv1.MaNV = b.NguoiTao
        LEFT JOIN NhanVien nv2 ON nv2.MaNV = b.NguoiXuLy";
if ($where) $sql .= " WHERE " . implode(" AND ", $where);
$sql .= " ORDER BY b.NgayTao DESC";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Danh sách báo cáo hệ thống</title>
  <style>
    table { border-collapse: collapse; width: 100%; }
    th, td { border:1px solid #ccc; padding:8px; }
    th { background:#f2f2f2; }
    .filter { margin: 10px 0; }
  </style>
</head>
<body>
  <h2>Danh sách báo cáo hệ thống</h2>

  <div class="filter">
    <form method="GET">
      <label>Trạng thái: </label>
      <select name="TrangThai">
        <option value="">-- Tất cả --</option>
        <?php
          $arrTT = ['Moi'=>'Mới','DangXuLy'=>'Đang xử lý','DaXuLy'=>'Đã xử lý','TuChoi'=>'Từ chối'];
          foreach ($arrTT as $val=>$txt) {
              $sel = ($TrangThai===$val)?'selected':'';
              echo "<option value='$val' $sel>$txt</option>";
          }
        ?>
      </select>
      &nbsp;&nbsp;
      <label>Loại: </label>
      <select name="Loai">
        <option value="">-- Tất cả --</option>
        <?php
          $arrLoai = ['Bug'=>'Bug','YeuCau'=>'Yêu cầu','CapNhat'=>'Cập nhật','BaoTri'=>'Bảo trì'];
          foreach ($arrLoai as $val=>$txt) {
              $sel = ($Loai===$val)?'selected':'';
              echo "<option value='$val' $sel>$txt</option>";
          }
        ?>
      </select>
      &nbsp;&nbsp;
      <button type="submit">Lọc</button>
      &nbsp;&nbsp;
      <a href="baocao_add.php">➕ Gửi báo cáo mới</a>
      &nbsp;&nbsp;
      <a href="../DangNhap/dashboard.php">Về Dashboard</a>
    </form>
  </div>

  <table>
    <tr>
      <th>Mã BC</th>
      <th>Tiêu đề</th>
      <th>Loại</th>
      <th>Mức độ</th>
      <th>Trạng thái</th>
      <th>Người tạo</th>
      <th>Người xử lý</th>
      <th>Ngày tạo</th>
      <th>Ngày cập nhật</th>
      <th>Ghi chú</th>
      <?php if ($isAdmin): ?><th>Hành động</th><?php endif; ?>
    </tr>
    <?php if ($result && $result->num_rows>0): ?>
      <?php while($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?php echo $row['MaBC']; ?></td>
          <td><?php echo htmlspecialchars($row['TieuDe']); ?></td>
          <td><?php echo $row['Loai']; ?></td>
          <td><?php echo $row['MucDo']; ?></td>
          <td><?php echo $row['TrangThai']; ?></td>
          <td><?php echo htmlspecialchars($row['TenNguoiTao'] ?: $row['NguoiTao']); ?></td>
          <td><?php echo htmlspecialchars($row['TenNguoiXuLy'] ?: ''); ?></td>
          <td><?php echo $row['NgayTao']; ?></td>
          <td><?php echo $row['NgayCapNhat']; ?></td>
          <td style="max-width:220px;"><?php echo nl2br(htmlspecialchars($row['GhiChu'])); ?></td>

          <?php if ($isAdmin): ?>
          <td>
            <form method="POST" action="baocao_update_status.php" style="display:inline-block;">
              <input type="hidden" name="MaBC" value="<?php echo $row['MaBC']; ?>">
              <select name="TrangThai" required>
                <?php
                  foreach ($arrTT as $val=>$txt) {
                      $sel = ($row['TrangThai']===$val)?'selected':'';
                      echo "<option value='$val' $sel>$txt</option>";
                  }
                ?>
              </select><br>
              <textarea name="GhiChu" rows="2" cols="24" placeholder="Ghi chú (tuỳ chọn)"></textarea><br>
              <button type="submit">Cập nhật</button>
            </form>
          </td>
          <?php endif; ?>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="<?php echo $isAdmin? '11':'10'; ?>">Không có báo cáo nào.</td></tr>
    <?php endif; ?>
  </table>
</body>
</html>
