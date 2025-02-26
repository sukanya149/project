<?php
session_start();
require_once '../connect.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// ดึงข้อมูลรหัสกรงจากฐานข้อมูล
$sql_cages = "SELECT cage_id, size FROM cage";
$stmt_cages = $pdo->prepare($sql_cages);
$stmt_cages->execute();
$cages = $stmt_cages->fetchAll(PDO::FETCH_ASSOC);

// เมื่อผู้ใช้ส่งฟอร์มมา
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับข้อมูลจากฟอร์ม (กำหนดค่าให้เป็นค่าว่างหากไม่ได้กรอก)
    $care_date = $_POST['care_date'] ?? null;
    $care_details = $_POST['care_details'] ?? null;
    $care_time = $_POST['care_time'] ?? null;
    $cage_id = $_POST['cage_id'] ?? null;

    // เพิ่มข้อมูลการดูแลสัตว์ในตาราง care โดยไม่ต้องส่ง care_id เนื่องจากมันจะเพิ่มอัตโนมัติ
    $sql = "INSERT INTO care (care_date, care_details, care_time, cage_id) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$care_date, $care_details, $care_time, $cage_id]);

    // แจ้งเตือนการสำเร็จและย้อนกลับ
    echo "<script>alert('เพิ่มข้อมูลการดูแลสำเร็จ'); window.location='Manage care.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="logo.png">
    <title>เพิ่มข้อมูลการดูแลสัตว์</title>
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
            <h2>เพิ่มข้อมูลการดูแลสัตว์</h2>
            <form method="POST">
                <div class="form-control">
                    <label for="care_date">วันที่</label>
                    <input type="date" class="form-control" id="care_date" name="care_date" required>
                </div>
                <div class="form-control">
                    <label for="care_details">รายละเอียดการดูแล</label>
                    <textarea class="form-control" id="care_details" name="care_details" rows="4" required></textarea>
                </div>
                <div class="form-control">
                    <label for="care_time">เวลา</label>
                    <input type="time" class="form-control" id="care_time" name="care_time" required>
                </div>
                <div class="form-control">
                    <label for="cage_id">รหัสกรง</label>
                    <select class="form-control" id="cage_id" name="cage_id" required>
                        <option value="">เลือกกรง</option>
                        <?php foreach ($cages as $cage) : ?>
                            <option value="<?= $cage['cage_id']; ?>"><?= $cage['cage_id'] . " - " . $cage['size']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn-custom">เพิ่มข้อมูลการดูแลสัตว์</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
