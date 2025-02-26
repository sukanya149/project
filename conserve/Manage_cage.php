<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="logo.png">
    <title>จัดการข้อมูลกรงเลี้ยง</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .wrapper {
            display: flex;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            background: #2e7d32;
            color: white;
            padding: 15px;
            position: fixed;
        }
        .sidebar h4 {
            text-align: center;
            padding-bottom: 10px;
            border-bottom: 1px solid white;
        }
        .sidebar a {
            display: block;
            color: white;
            padding: 10px;
            text-decoration: none;
            margin-bottom: 5px;
            border-radius: 5px;
        }
        .sidebar a:hover {
            background: #1b5e20;
        }
        .content {
            margin-left: 260px;
            padding: 20px;
            width: calc(100% - 260px);
        }
    </style>
</head>
<body>

<div class="wrapper">
     <!-- เมนูด้านซ้าย -->
     <div class="sidebar">
     <h4><i class="fas fa-cogs"></i> เมนูหลัก</h4>
        <a href="conserve.php"><i class="fas fa-home"></i> หน้าหลัก</a>
        <a href="Manage_animal.php"><i class="fas fa-baby"></i> จัดการข้อมูลสัตว์เข้าใหม่</a>
        <a href="Manage_newanimal.php"><i class="fas fa-baby-carriage"></i> จัดการข้อมูลสัตว์เกิดใหม่</a>
        <a href="Manage_cage.php"><i class="fas fa-box"></i> จัดการกรงเลี้ยง</a>
        <a href="Manage_type.php"><i class="fas fa-paw"></i> จัดการข้อมูลประเภทสัตว์</a>
        <a href="Manage_breeding.php"><i class="fas fa-heart"></i> จัดการข้อมูลผสมพันธุ์ ตั้งครรภ์ และคลอด</a>
        <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> ออกจากระบบ</a>
     </div>

    <!-- เนื้อหาหลัก -->
    <div class="content">
        <h2 class="text-success">จัดการกรงเลี้ยง</h2>

        <!-- ปุ่มเพิ่มข้อมูล + ช่องค้นหา -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="add_cage.php" class="btn btn-success">+ เพิ่มข้อมูล</a>
            <input type="text" id="searchInput" class="form-control w-25" placeholder="🔍 ค้นหา...">
        </div>

        <table class="table table-bordered table-striped bg-white">
            <thead class="table-success">
                <tr>
                    <th>รหัสกรง</th>
                    <th>ขนาดกรง</th>
                    <th>ประเภทกรง</th>
                    <th>รายละเอียดกรง</th>
                    <th>ตำแหน่งกรง</th>
                    <th>การจัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require_once '../connect.php';
                $sql = "SELECT * FROM cage";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($result) > 0) {
                    foreach ($result as $row) {
                        // แสดงผลในตาราง
                        echo "<tr>";
                        echo "<td>{$row['cage_id']}</td>";
                        echo "<td>{$row['size']}</td>";
                        echo "<td>{$row['type']}</td>";
                        echo "<td>{$row['details_cage']}</td>";
                        echo "<td>{$row['location_cage']}</td>";
                        echo "<td>
                                <div class='btn-group'>
                                    <a href='edit_cage.php?cage_id={$row['cage_id']}' class='btn btn-warning btn-sm'>แก้ไข</a>
                                    <button class='btn btn-danger btn-sm' onclick='confirmDelete({$row['cage_id']})'>ลบ</button>
                                </div>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>ไม่มีข้อมูลกรงเลี้ยง</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    // ค้นหาข้อมูลแบบเรียลไทม์
    $("#searchInput").on("keyup", function () {
        let value = $(this).val().toLowerCase();
        $("tbody tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

   // ลบข้อมูลแบบ AJAX
function confirmDelete(cageId) {
    if (confirm("คุณแน่ใจหรือไม่ที่จะลบข้อมูลกรงเลี้ยงนี้?")) {
        $.post("delete_cage.php", { delete_id: cageId }, function(response) {
            alert(response); // แสดงข้อความที่ส่งกลับจาก PHP (เช่น "ลบข้อมูลสำเร็จ")
            location.reload(); // รีเฟรชหน้าเพื่อตรวจสอบข้อมูลที่ถูกลบ
        });
    }
}

</script>

</body>
</html>
