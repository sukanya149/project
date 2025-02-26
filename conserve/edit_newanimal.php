<?php
session_start();
require_once '../connect.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// รับค่า newanimal_id จาก URL
$newanimal_id = filter_input(INPUT_GET, 'newanimal_id', FILTER_VALIDATE_INT);
if (!$newanimal_id) {
    die("Error: ไม่พบค่า newanimal_id");
}

// ดึงข้อมูลสัตว์แรกเกิดและข้อมูลสัตว์จากฐานข้อมูล
$sql = "SELECT na.*, a.color, a.animal_name
        FROM newborn_animals na
        LEFT JOIN animal a ON na.animal_id = a.animal_id
        WHERE na.newanimal_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$newanimal_id]);
$animal = $stmt->fetch();

// ถ้าไม่พบข้อมูล
if (!$animal) {
    die("Error: ไม่พบข้อมูลสัตว์ที่ต้องการแก้ไข");
}

// ดึงข้อมูลประเภทสัตว์
$sql = "SELECT * FROM animal_type";
$stmt = $pdo->query($sql);
$animal_types = $stmt->fetchAll();

// เมื่อกดปุ่มบันทึกการแก้ไข
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $animal_name = $_POST['animal_name'];
    $details_animal = $_POST['details_animal'] ?? null;
    $birthday_animal = $_POST['birthday_animal'] ?? null;
    $gender = $_POST['gender'] ?? null;
    $weight = $_POST['weight'] ?? null;
    $animalsiz = $_POST['animalsiz'] ?? null;
    $breeding_id = $_POST['breeding_id'] ?? null;
    $cage_id = $_POST['cage_id'] ?? null;
    $color = $_POST['color'] ?? null;  // เพิ่มการรับค่าของสี
    $chip = $_POST['chip'] ?? null;
    $animal_type_id = $_POST['animal_type_id'] ?? null;

    // ตรวจสอบว่าประเภทสัตว์ถูกเลือก
    if (!$animal_type_id) {
        die("Error: กรุณาเลือกประเภทสัตว์");
    }

    // เริ่ม Transaction
    $pdo->beginTransaction();

    try {
        // อัปเดตข้อมูลใน newborn_animals
        $sql = "UPDATE newborn_animals 
                SET details_animal = ?, birthday_animal = ?, gender = ?  ,weight = ?, animalsiz = ?, 
                    breeding_id = ?, cage_id = ?, animal_type_id = ?, chip = ? 
                WHERE newanimal_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $details_animal, $birthday_animal,$gender,  $weight, $animalsiz,
            $breeding_id, $cage_id, $animal_type_id, $chip, $newanimal_id
        ]);

        // อัปเดตข้อมูลใน animals (เช่น สีและชื่อ)
       $sql = "UPDATE animal 
                SET color = :color, animal_name = :animal_name 
                WHERE animal_id = :animal_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':color', $color);
        $stmt->bindParam(':animal_name', $animal_name);
        $stmt->bindParam(':animal_id', $animal['animal_id'], PDO::PARAM_INT);
        $stmt->execute();

        // บันทึก Transaction
        $pdo->commit();

        echo "<script>alert('แก้ไขข้อมูลสำเร็จ'); window.location='Manage_newanimal.php';</script>";
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        die("Error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="logo.png">
    <title>แก้ไขข้อมูลสัตว์แรกเกิด</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; }
        .wrapper { display: flex; }
        .content { margin-left: 260px; padding: 30px; width: calc(100% - 260px); display: flex; justify-content: center; }
        .form-container { background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); max-width: 600px; width: 100%; }
        .form-container h2 { margin-bottom: 20px; color: #2e7d32; }
        .form-control { margin-bottom: 15px; }
        .btn-custom { background-color: #2e7d32; color: white; border: none; padding: 12px 20px; border-radius: 5px; cursor: pointer; width: 100%; font-size: 16px; }
        .btn-custom:hover { background-color: #43a047; }
    </style>
</head>
<body>

<div class="wrapper">
    <div class="content">
        <div class="form-container">
            <h2>แก้ไขข้อมูลสัตว์แรกเกิด</h2>
            <form method="POST">
                <div class="form-control">
                    <label for="animal_name">ชื่อสัตว์</label>
                    <input type="text" class="form-control" id="animal_name" name="animal_name" value="<?= htmlspecialchars($animal['animal_name']) ?>" required>
                </div>


                <div class="form-control">
    <label for="gender">เพศ</label>
    <select class="form-control" id="gender" name="gender" required>
        <option value="เพศผู้" <?= ($animal['gender'] == 'เพศผู้') ? 'selected' : '' ?>>เพศผู้</option>
        <option value="เพศเมีย" <?= ($animal['gender'] == 'เพศเมีย') ? 'selected' : '' ?>>เพศเมีย</option>
    </select>
</div>

                <div class="form-control">
                    <label for="details_animal">รายละเอียดสัตว์</label>
                    <textarea class="form-control" id="details_animal" name="details_animal" rows="4" required><?= htmlspecialchars($animal['details_animal']) ?></textarea>
                </div>
                <div class="form-control">
                    <label for="birthday_animal">วันที่เกิด</label>
                    <input type="date" class="form-control" id="birthday_animal" name="birthday_animal" value="<?= htmlspecialchars($animal['birthday_animal']) ?>" required>
                </div>
                <div class="form-control">
                    <label for="weight">น้ำหนัก</label>
                    <input type="text" class="form-control" id="weight" name="weight" value="<?= htmlspecialchars($animal['weight']) ?>" required>
                </div>
                <div class="form-control">
                    <label for="animalsiz">ขนาดสัตว์</label>
                    <input type="text" class="form-control" id="animalsiz" name="animalsiz" value="<?= htmlspecialchars($animal['animalsiz']) ?>" required>
                </div>
                <div class="form-control">
                    <label for="breeding_id">หมายเลขการผสมพันธุ์</label>
                    <input type="text" class="form-control" id="breeding_id" name="breeding_id" value="<?= htmlspecialchars($animal['breeding_id']) ?>" required>
                </div>
                <div class="form-control">
                    <label for="cage_id">หมายเลขกรง</label>
                    <input type="text" class="form-control" id="cage_id" name="cage_id" value="<?= htmlspecialchars($animal['cage_id']) ?>" required>
                </div>
                <div class="form-control">
                    <label for="animal_type_id">ประเภทสัตว์</label>
                    <select class="form-control" id="animal_type_id" name="animal_type_id" required>
                        <?php foreach ($animal_types as $type): ?>
                            <option value="<?= $type['animal_type_id']; ?>" <?= ($animal['animal_type_id'] == $type['animal_type_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($type['type_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-control">
                    <label for="color">สี</label>
                    <input type="text" class="form-control" id="color" name="color" value="<?= htmlspecialchars($animal['color']) ?>" required>
                </div>
                <button type="submit" class="btn-custom">บันทึกการแก้ไข</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
