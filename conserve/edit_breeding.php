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
    $breeding_id = $_POST['breeding_id']; // รหัสการผสมพันธุ์
    $breeding_date = $_POST['breeding_date']; // วันที่ผสมพันธุ์
    $breeding_status = $_POST['breeding_status']; // สถานะผสมพันธุ์
    $pregnancy_status = $_POST['pregnancy_status']; // สถานะการตั้งครรภ์
    $offspring_count = $_POST['offspring_count']; // จำนวนลูก
    $birth_date = $_POST['birth_date']; // วันที่คลอด
    $animal_father_id = $_POST['animal_father_id']; // รหัสพ่อสัตว์
    $animal_mother_id = $_POST['animal_mother_id']; // รหัสแม่สัตว์

    // อัปเดตข้อมูลในฐานข้อมูล
    $sql = "UPDATE breeding SET breeding_date = ?, breeding_status = ?, pregnancy_status = ?, offspring_count = ?, birth_date = ?, Animal_father_id = ?, Animal_mother_id = ? WHERE breeding_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$breeding_date, $breeding_status, $pregnancy_status, $offspring_count, $birth_date, $animal_father_id, $animal_mother_id, $breeding_id]);

    echo "<script>alert('อัปเดตข้อมูลการผสมพันธุ์สำเร็จ'); window.location='Manage_breeding.php';</script>";
    exit;
}

// ดึงข้อมูลการผสมพันธุ์ที่ต้องการแก้ไขจากฐานข้อมูล
if (isset($_GET['breeding_id'])) {
    $breeding_id = $_GET['breeding_id'];
    $sql = "SELECT * FROM breeding WHERE breeding_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$breeding_id]);
    $breeding = $stmt->fetch();

    if ($breeding) {
        $breeding_date = $breeding['breeding_date'];
        $breeding_status = $breeding['breeding_status'];
        $pregnancy_status = $breeding['pregnancy_status'];
        $offspring_count = $breeding['offspring_count'];
        $birth_date = $breeding['birth_date'];
        $animal_father_id = $breeding['Animal_father_id'];
        $animal_mother_id = $breeding['Animal_mother_id'];
    } else {
        echo "ไม่พบข้อมูลการผสมพันธุ์";
        exit;
    }
} else {
    echo "ไม่พบรหัสการผสมพันธุ์";
    exit;
}
?>

<!-- ฟอร์ม HTML สำหรับแก้ไขข้อมูลการผสมพันธุ์ -->
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="logo.png">
    <title>แก้ไขข้อมูลการผสมพันธุ์</title>
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
            <h2>แก้ไขข้อมูลการผสมพันธุ์</h2>
            <form method="POST">
                <input type="hidden" name="breeding_id" value="<?= isset($breeding_id) ? $breeding_id : '' ?>">

                <div class="form-control">
                    <label for="breeding_date">วันที่ผสมพันธุ์</label>
                    <input type="date" class="form-control" id="breeding_date" name="breeding_date" value="<?= isset($breeding_date) ? $breeding_date : '' ?>" required>
                </div>

                <div class="form-control">
                    <label for="breeding_status">สถานะผสมพันธุ์</label>
                    <select class="form-control" id="breeding_status" name="breeding_status" required>
                        <option value="ผ่าน" <?= ($breeding_status == 'ผ่าน') ? 'selected' : '' ?>>ผสมพันธุ์ผ่าน</option>
                        <option value="ไม่ผ่าน" <?= ($breeding_status == 'ไม่ผ่าน') ? 'selected' : '' ?>>ผสมพันธุ์ไม่ผ่าน</option>
                    </select>
                </div>

                <div class="form-control">
                    <label for="pregnancy_status">สถานะการตั้งครรภ์</label>
                    <select class="form-control" id="pregnancy_status" name="pregnancy_status" required>
                        <option value="ตั้งครรภ์แล้ว" <?= ($pregnancy_status == 'ตั้งครรภ์แล้ว') ? 'selected' : '' ?>>ตั้งครรภ์แล้ว</option>
                        <option value="ยังไม่ต้องครรภ์" <?= ($pregnancy_status == 'ยังไม่ต้องครรภ์') ? 'selected' : '' ?>>ยังไม่ต้องครรภ์</option>
                    </select>
                </div>

                <div class="form-control">
                    <label for="offspring_count">จำนวนลูก</label>
                    <input type="number" class="form-control" id="offspring_count" name="offspring_count" value="<?= isset($offspring_count) ? $offspring_count : '' ?>" required>
                </div>

                <div class="form-control">
                    <label for="birth_date">วันที่คลอด</label>
                    <input type="date" class="form-control" id="birth_date" name="birth_date" value="<?= isset($birth_date) ? $birth_date : '' ?>">
                </div>

                <div class="form-control">
                    <label for="animal_father_id">รหัสพ่อสัตว์</label>
                    <input type="number" class="form-control" id="animal_father_id" name="animal_father_id" value="<?= isset($animal_father_id) ? $animal_father_id : '' ?>" required>
                </div>

                <div class="form-control">
                    <label for="animal_mother_id">รหัสแม่สัตว์</label>
                    <input type="number" class="form-control" id="animal_mother_id" name="animal_mother_id" value="<?= isset($animal_mother_id) ? $animal_mother_id : '' ?>" required>
                </div>

                <button type="submit" class="btn-custom">บันทึกการแก้ไข</button>
            </form>
        </div>
    </div>

</div>

</body>
</html>
