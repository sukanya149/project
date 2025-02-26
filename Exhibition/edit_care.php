<?php
session_start();
require_once '../connect.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// ตรวจสอบว่ามีการส่งข้อมูลฟอร์มมา
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับข้อมูลจากฟอร์ม
    $care_date = $_POST['care_date']; // วันที่การดูแล
    $care_details = $_POST['care_details']; // รายละเอียดการดูแล
    $care_time = $_POST['care_time']; // เวลา
    $cage_id = $_POST['cage_id']; // รหัสกรง

    // ตรวจสอบการอัปเดตข้อมูล
    if (empty($_POST['care_id'])) {
        // เพิ่มข้อมูลใหม่
        $sql = "INSERT INTO care (care_date, care_details, care_time, cage_id) 
                VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$care_date, $care_details, $care_time, $cage_id]);

        echo "<script>alert('เพิ่มข้อมูลการดูแลสำเร็จ'); window.location='Manage care.php';</script>";
        exit;
    } else {
        // อัปเดตข้อมูลในฐานข้อมูล
        $care_id = $_POST['care_id'];
        $sql = "UPDATE care SET care_date = ?, care_details = ?, care_time = ?, cage_id = ? WHERE care_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$care_date, $care_details, $care_time, $cage_id, $care_id]);

        echo "<script>alert('อัปเดตข้อมูลการดูแลสำเร็จ'); window.location='Manage care.php';</script>";
        exit;
    }
}

// ดึงข้อมูลการดูแลที่ต้องการแก้ไขจากฐานข้อมูล
if (isset($_GET['care_id'])) {
    $care_id = $_GET['care_id'];
    $sql = "SELECT * FROM care WHERE care_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$care_id]);
    $care = $stmt->fetch();

    if ($care) {
        $care_date = $care['care_date'];
        $care_details = $care['care_details'];
        $care_time = $care['care_time'];
        $cage_id = $care['cage_id'];
    } else {
        echo "ไม่พบข้อมูลการดูแล";
        exit;
    }
}
?>

<!-- ฟอร์ม HTML -->
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="logo.png">
    <title>แก้ไขข้อมูลการดูแลสัตว์</title>
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
        .sidebar .active {
            background: #1b5e20;
        }
        .content {
            margin-left: 260px;
            padding: 30px;
            width: calc(100% - 260px);
            display: flex;
            justify-content: center;
        }
        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
        }
        .form-container h2 {
            margin-bottom: 20px;
            color: #2e7d32;
        }
        .form-control {
            margin-bottom: 15px;
        }
        .form-control label {
            font-weight: bold;
        }
        .btn-custom {
            background-color: #2e7d32;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }
        .btn-custom:hover {
            background-color: #43a047;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #6c757d;
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
        <div class="form-container">
            <h2>แก้ไขข้อมูลการดูแลสัตว์</h2>
            <form method="POST">
                <input type="hidden" name="care_id" value="<?= isset($care_id) ? $care_id : '' ?>">
                
                <div class="form-control">
                    <label for="care_date">วันที่การดูแล</label>
                    <input type="date" class="form-control" id="care_date" name="care_date" value="<?= isset($care_date) ? $care_date : '' ?>" required>
                </div>

                <div class="form-control">
                    <label for="care_details">รายละเอียดการดูแล</label>
                    <textarea class="form-control" id="care_details" name="care_details" rows="4" required><?= isset($care_details) ? $care_details : '' ?></textarea>
                </div>

                <div class="form-control">
                    <label for="care_time">เวลา</label>
                    <input type="time" class="form-control" id="care_time" name="care_time" value="<?= isset($care_time) ? $care_time : '' ?>" required>
                </div>

                <div class="form-control">
                    <label for="cage_id">รหัสกรง</label>
                    <select class="form-control" id="cage_id" name="cage_id" required>
                        <option value="">เลือกกรง</option>
                        <!-- ตัวอย่างข้อมูลกรงที่สามารถนำมาจากฐานข้อมูล -->
                        <?php
                        $sql_cages = "SELECT cage_id, size FROM cage";
                        $stmt_cages = $pdo->prepare($sql_cages);
                        $stmt_cages->execute();
                        $cages = $stmt_cages->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($cages as $cage) {
                            echo "<option value='{$cage['cage_id']}' " . ($cage['cage_id'] == $cage_id ? 'selected' : '') . ">{$cage['cage_id']} - {$cage['size']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <button type="submit" class="btn-custom">บันทึกการแก้ไข</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
