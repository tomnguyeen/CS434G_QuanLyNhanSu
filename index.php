<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản Lý Nhân Sự</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Danh sách nhân viên</h1>
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Họ Tên</th>
            <th>Chức Vụ</th>
            <th>Lương</th>
        </tr>
        <?php
        $sql = "SELECT * FROM nhanvien";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>".$row["id"]."</td>
                        <td>".$row["hoTen"]."</td>
                        <td>".$row["chucVu"]."</td>
                        <td>".$row["luong"]."</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Không có dữ liệu</td></tr>";
        }
        $conn->close();
        ?>
    </table>
</body>
</html>
