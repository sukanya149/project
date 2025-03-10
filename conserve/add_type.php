<?php
session_start();
require_once '../connect.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// เมื่อผู้ใช้ส่งฟอร์มมา
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับข้อมูลจากฟอร์ม (กำหนดค่าให้เป็นค่าว่างหากไม่ได้กรอก)
    $type_name = $_POST['type_name'] ?? null;
    $animal_count = $_POST['animal_count'] ?? null;
    $source = $_POST['source'] ?? null;

    // เพิ่มข้อมูลประเภทสัตว์ใหม่ในตาราง animal_types
    $sql = "INSERT INTO animal_type (type_name, animal_count, source) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$type_name, $animal_count, $source]);

    // แจ้งเตือนการสำเร็จและย้อนกลับ
    echo "<script>alert('เพิ่มข้อมูลประเภทสัตว์สำเร็จ'); window.location='Manage_type.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="logo.png">
    <title>เพิ่มข้อมูลประเภทสัตว์</title>
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
        <div class="form-container">
            <h2>เพิ่มข้อมูลประเภทสัตว์</h2>
            <form method="POST">
                <div class="form-control">
                    <label for="type_name">ชื่อประเภทสัตว์</label>
                    <input type="text" class="form-control" id="type_name" name="type_name" required>
                </div>
                <div class="form-control">
                    <label for="animal_count">จำนวนสัตว์</label>
                    <input type="number" class="form-control" id="animal_count" name="animal_count" required>
                </div>
                <div class="form-control">
                    <label for="source">แหล่งที่มา</label>
                    <input type="text" class="form-control" id="source" name="source" required>
                </div>
                <button type="submit" class="btn-custom">เพิ่มข้อมูลประเภทสัตว์</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
