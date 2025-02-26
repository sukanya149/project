<?php
require_once('../tcpdf/tcpdf.php');
require_once '../connect.php';

// สมมติว่าคุณมีการใช้ session สำหรับการล็อกอิน
session_start(); // เริ่มต้น session
$user_name = isset($_SESSION['username']) ? $_SESSION['username'] : 'Unknown User'; // ดึงชื่อผู้ใช้งานจาก session


$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($user_name);
$pdf->SetTitle('รายงานข้อมูลพนักงาน');
$pdf->SetHeaderData('', 0, 'รายงานข้อมูลพนักงาน', 'สร้างโดย: ' . $user_name);
$pdf->setFooterData(array(0, 64, 128), array(0, 64, 128));
$pdf->setMargins(15, 27, 15);
$pdf->setHeaderFont(Array('freeserif', '', 10));
$pdf->setFooterFont(Array('freeserif', '', 8));
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->AddPage();
$pdf->SetFont('freeserif', '', 12);
$sql = "SELECT * FROM personnel";
$stmt = $pdo->prepare($sql);
$stmt->execute();
if ($stmt->rowCount() > 0) {
    $html = '
    <p style="text-align: center; font-size: 14px; font-weight: bold;">ข้อมูลพนักงาน</p>
    <table border="1" cellpadding="5">
        <tr>
            <th width="15%">รหัสพนักงาน</th>
            <th width="23%">ชื่อ-นามสกุล</th>
            <th width="25%">ตำแหน่ง</th>
            <th width="15%">เบอร์โทร</th>
            <th width="28%">อีเมล</th>
        </tr>';
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $html .= '<tr>
                    <td>' . $row['staff_id'] . '</td>
                    <td>' . $row['full_name'] . '</td>
                    <td>' . $row['position'] . '</td>
                    <td>' . $row['phone_number'] . '</td>
                    <td>' . $row['email'] . '</td>
                  </tr>';
    }
    $html .= '</table>';
    $pdf->writeHTML($html, true, false, true, false, '');
} else {
    $pdf->Write(0, 'ไม่พบข้อมูลพนักงาน');
}
$pdo = null;
$pdf->Output('employee_report.pdf', 'I');
?>
