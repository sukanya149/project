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
    $animal_id = $_POST['animal_id']; // รหัสสัตว์
    $animal_name = $_POST['animal_name']; // ชื่อสัตว์
    $gender = $_POST['gender']; // เพศสัตว์
    $entry_date = $_POST['entry_date']; // วันที่เข้า
    $color = $_POST['color']; // สี
    $nature = $_POST['nature']; // ธรรมชาติ
    $chip = $_POST['chip']; // รหัสชิป
    $details_animals = $_POST['details_animals']; // รายละเอียดเพิ่มเติม
    $source = $_POST['source']; // แหล่งที่มา
    $cage_id = $_POST['cage_id']; // รหัสกรง
    $animal_type_id = $_POST['animal_type_id']; // รหัสประเภทสัตว์

    // อัปเดตข้อมูลในฐานข้อมูล
    $sql = "UPDATE animal SET animal_name = ?, gender = ?, entry_date = ?, color = ?, nature = ?, chip = ?, details_animals = ?, source = ?, cage_id = ?, animal_type_id = ? WHERE animal_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$animal_name, $gender, $entry_date, $color, $nature, $chip, $details_animals, $source, $cage_id, $animal_type_id, $animal_id]);

    echo "<script>alert('อัปเดตข้อมูลสัตว์สำเร็จ'); window.location='Manage_animal.php';</script>";
    exit;
}

// ดึงข้อมูลสัตว์ที่ต้องการแก้ไขจากฐานข้อมูล
if (isset($_GET['animal_id'])) {
    $animal_id = $_GET['animal_id'];
    $sql = "SELECT * FROM animal WHERE animal_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$animal_id]);
    $animal = $stmt->fetch();

    if ($animal) {
        $animal_name = $animal['animal_name'];
        $gender = $animal['gender'];
        $entry_date = $animal['entry_date'];
        $color = $animal['color'];
        $nature = $animal['nature'];
        $chip = $animal['chip'];
        $details_animals = $animal['details_animals'];
        $source = $animal['source'];
        $cage_id = $animal['cage_id'];
        $animal_type_id = $animal['animal_type_id'];
    } else {
        echo "ไม่พบข้อมูลสัตว์";
        exit;
    }
} else {
    echo "ไม่พบรหัสสัตว์";
    exit;
}
?>

<!-- ฟอร์ม HTML สำหรับแก้ไขข้อมูลสัตว์ -->
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="logo.png">
    <title>แก้ไขข้อมูลสัตว์เข้าใหม่</title>
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
            <h2>แก้ไขข้อมูลสัตว์</h2>
            <form method="POST">
                <input type="hidden" name="animal_id" value="<?= isset($animal_id) ? $animal_id : '' ?>">

                <div class="form-control">
                    <label for="animal_name">ชื่อสัตว์</label>
                    <input type="text" class="form-control" id="animal_name" name="animal_name" value="<?= isset($animal_name) ? $animal_name : '' ?>" required>
                </div>

                <div class="form-control">
                    <label for="gender">เพศ</label>
                    <select class="form-control" id="gender" name="gender" required>
                        <option value="Male" <?= ($gender == 'Male') ? 'selected' : '' ?>>เพศผู้</option>
                        <option value="Female" <?= ($gender == 'Female') ? 'selected' : '' ?>>เพศเมีย</option>
                    </select>
                </div> 

                <div class="form-control">
                    <label for="entry_date">วันที่เข้า</label>
                    <input type="date" class="form-control" id="entry_date" name="entry_date" value="<?= isset($entry_date) ? $entry_date : '' ?>" required>
                </div>

                <div class="form-control">
                    <label for="color">สี</label>
                    <input type="text" class="form-control" id="color" name="color" value="<?= isset($color) ? $color : '' ?>" required>
                </div>

                <div class="form-control">
                    <label for="nature">พฤติกรรม</label>
                    <textarea class="form-control" id="nature" name="nature" required><?= isset($nature) ? $nature : '' ?></textarea>
                </div>

                <div class="form-control">
                    <label for="chip">รหัสชิป</label>
                    <input type="text" class="form-control" id="chip" name="chip" value="<?= isset($chip) ? $chip : '' ?>">
                </div>

                <div class="form-control">
                    <label for="details_animals">รายละเอียดเพิ่มเติม</label>
                    <textarea class="form-control" id="details_animals" name="details_animals"><?= isset($details_animals) ? $details_animals : '' ?></textarea>
                </div>

                <div class="form-control">
                    <label for="source">แหล่งที่มา</label>
                    <input type="text" class="form-control" id="source" name="source" value="<?= isset($source) ? $source : '' ?>" required>
                </div>

                <div class="form-control">
                    <label for="cage_id">รหัสกรง</label>
                    <input type="number" class="form-control" id="cage_id" name="cage_id" value="<?= isset($cage_id) ? $cage_id : '' ?>" required>
                </div>

                <div class="form-control">
                    <label for="animal_type_id">รหัสประเภทสัตว์</label>
                    <input type="number" class="form-control" id="animal_type_id" name="animal_type_id" value="<?= isset($animal_type_id) ? $animal_type_id : '' ?>" required>
                </div>

                <button type="submit" class="btn-custom">บันทึกการแก้ไข</button>
            </form>
        </div>
    </div>

</div>

</body>
</html>
