<?php
// รวมไฟล์การเชื่อมต่อฐานข้อมูล
require_once '../connect.php';
require_once('../tcpdf/tcpdf.php');

// ดึงข้อมูลกรงเลี้ยงจากฐานข้อมูลด้วย PDO
$sql = "SELECT * FROM cage";
$stmt = $pdo->prepare($sql);
$stmt->execute();


session_start(); // เริ่มต้น session
$user_name = isset($_SESSION['username']) ? $_SESSION['username'] : 'Unknown User'; // ดึงชื่อผู้ใช้งานจาก session

// สร้าง PDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('รายงานข้อมูลกรงเลี้ยง');
$pdf->SetHeaderData('', 0, 'รายงานข้อมูลกรงเลี้ยง', 'สร้างโดย: ' . $user_name);
$pdf->setFooterData(array(0, 64, 128), array(0, 64, 128));
$pdf->setMargins(15, 27, 15);
$pdf->setHeaderFont(Array('freeserif', '', 10));
$pdf->setFooterFont(Array('freeserif', '', 8));
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->AddPage();
$pdf->SetFont('freeserif', '', 12);
 
// เขียน HTML
$html = '
<p style="text-align: center; font-size: 14px; font-weight: bold;">ข้อมูลกรงเลี้ยง</p>
<table border="1" cellpadding="5">
    <tr>
        <th width="20%">รหัสกรง</th>
        <th width="30%">ขนาดกรง</th>
        <th width="25%">ประเภทกรง</th>
        <th width="25%">รายละเอียดกรง</th>
    </tr>';

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $html .= '<tr>
                <td>' . $row['cage_id'] . '</td>
                <td>' . $row['size'] . '</td>
                <td>' . $row['type'] . '</td>
                <td>' . $row['details_cage'] . '</td>
              </tr>';
}

$html .= '</table>';
$pdf->writeHTML($html, true, false, true, false, '');

// ปิดการเชื่อมต่อฐานข้อมูล
$pdo = null;

// ส่ง PDF ไปยังเบราว์เซอร์
$pdf->Output('cage_report.pdf', 'I');
?>
