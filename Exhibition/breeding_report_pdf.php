<?php
// รวมไฟล์การเชื่อมต่อฐานข้อมูล
require_once '../connect.php';
require_once('../tcpdf/tcpdf.php');

// ดึงข้อมูลประวัติการผสมพันธุ์จากฐานข้อมูลด้วย PDO
$sql = "SELECT * FROM breeding";
$stmt = $pdo->prepare($sql);
$stmt->execute();

session_start(); // เริ่มต้น session
$user_name = isset($_SESSION['username']) ? $_SESSION['username'] : 'Unknown User'; // ดึงชื่อผู้ใช้งานจาก session


// สร้าง PDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('รายงานประวัติการผสมพันธุ์ ตั้งครรภ์และคลอด');
$pdf->SetHeaderData('', 0, 'รายงานประวัติการผสมพันธุ์ ตั้งครรภ์และคลอด', 'สร้างโดย: ' . $user_name);
$pdf->setFooterData(array(0, 64, 128), array(0, 64, 128));
$pdf->setMargins(15, 27, 15);
$pdf->setHeaderFont(Array('freeserif', '', 10));
$pdf->setFooterFont(Array('freeserif', '', 8));
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->AddPage();
$pdf->SetFont('freeserif', '', 12);

// เขียน HTML
$html = '
<p style="text-align: center; font-size: 14px; font-weight: bold;">ประวัติการผสมพันธุ์ ตั้งครรภ์และคลอด</p>
<table border="1" cellpadding="5">
    <tr>
        <th width="15%">รหัสผสมพันธุ์</th>
        <th width="20%">สถานะการผสมพันธุ์</th>
        <th width="20%">สถานะการตั้งครรภ์</th>
        <th width="15%">จำนวนลูก</th>
        <th width="15%">รหัสพ่อพันธุ์</th>
        <th width="15%">รหัสแม่พันธุ์</th>
    </tr>';

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $html .= '<tr>
                <td>' . $row['breeding_id'] . '</td>
                <td>' . $row['breeding_status'] . '</td>
                <td>' . $row['pregnancy_status'] . '</td>
                <td>' . $row['offspring_count'] . '</td>
                <td>' . $row['Animal_father_id'] . '</td>
                <td>' . $row['Animal_mother_id'] . '</td>
              </tr>';
}

$html .= '</table>';
$pdf->writeHTML($html, true, false, true, false, '');

// ปิดการเชื่อมต่อฐานข้อมูล
$pdo = null;

// ส่ง PDF ไปยังเบราว์เซอร์
$pdf->Output('breeding_report.pdf', 'I');
?>
