<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="logo.png">
    <title>จัดการข้อมูลการดูแล</title>
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
        <a href="Exhibition.php"><i class="fas fa-home"></i> หน้าหลัก</a>
    <a href="Manage care.php"><i class="fas fa-user-cog"></i> จัดการข้อมูลการดูแล</a>
    <a href="breeding_report.php"><i class="fas fa-file-alt"></i> ดูรายงานประวัติการผสมพันธุ์ ตั้งครรภ์และคลอด</a>
    <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> ออกจากระบบ</a>
    </div>

    <!-- เนื้อหาหลัก -->
    <div class="content">
        <h2 class="text-success">จัดการข้อมูลการดูแล</h2>

        <!-- ปุ่มเพิ่มข้อมูล + ช่องค้นหา -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="add_care.php" class="btn btn-success">+ เพิ่มข้อมูลการดูแล</a>
            <input type="text" id="searchInput" class="form-control w-25" placeholder="🔍 ค้นหา...">
        </div>

        <table class="table table-bordered table-striped bg-white">
            <thead class="table-success">
                <tr>
                    <th>รหัสการดูแล</th>
                    <th>วันที่</th>
                    <th>รายละเอียดการดูแล</th>
                    <th>เวลา</th>
                    <th>รหัสกรง</th>
                    <th>การจัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require_once '../connect.php';
                $sql = "SELECT care_id, care_date, care_details, care_time, cage_id FROM care";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                $care_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($care_data) > 0) {
                    foreach ($care_data as $care) {
                        // แสดงข้อมูลการดูแล
                        echo "<tr>";
                        echo "<td>{$care['care_id']}</td>";
                        echo "<td>{$care['care_date']}</td>";
                        echo "<td>{$care['care_details']}</td>";
                        echo "<td>{$care['care_time']}</td>";
                        echo "<td>{$care['cage_id']}</td>";
                        echo "<td>
                                <div class='btn-group'>
                                    <a href='edit_care.php?care_id={$care['care_id']}' class='btn btn-warning btn-sm'>แก้ไข</a>
                                    <button class='btn btn-danger btn-sm' onclick='confirmDelete({$care['care_id']})'>ลบ</button>
                                </div>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>ไม่มีข้อมูลการดูแล</td></tr>";
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
function confirmDelete(careId) {
    if (confirm("คุณแน่ใจหรือไม่ที่จะลบข้อมูลการดูแลนี้?")) {
        $.post("delete_care.php", { delete_id: careId }, function(response) {
            alert(response); // แสดงข้อความที่ส่งกลับจาก PHP
            location.reload(); // รีเฟรชหน้าเพื่อแสดงผลข้อมูลที่ถูกลบ
        });
    }
}

</script>

</body>
</html>
