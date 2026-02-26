<?php
declare(strict_types=1);
date_default_timezone_set('Asia/Bangkok');
require_once DATABASES_DIR . '/registrations.php';

$conn = getConnection();
$userId = (int) $_SESSION['user_id'];
$eventId = (int) ($_GET['event_id'] ?? 0);

if (!$eventId) {
    header('Location: /event');
    exit;
}

// 1. ดึงข้อมูลการลงทะเบียน พร้อม OTP เดิมที่มีอยู่ (ถ้ามี)
$stmt = $conn->prepare("
    SELECT id, status, otp_code, otp_expires_at 
    FROM registrations 
    WHERE event_id = ? AND user_id = ? 
    LIMIT 1
");
$stmt->bind_param('ii', $eventId, $userId);
$stmt->execute();
$registration = $stmt->get_result()->fetch_assoc();

// ตรวจสอบสิทธิ์
if (!$registration || $registration['status'] !== 'approved') {
    die("คุณไม่มีสิทธิ์เข้าถึง หรือยังไม่ได้รับการอนุมัติ");
}

$otpCode = $registration['otp_code'];
$expiresAt = $registration['otp_expires_at'];

// 2. เช็คว่า OTP เดิม "ว่าง" หรือ "หมดอายุ" หรือไม่ (หมดอายุใน 30 นาที)
if (!$otpCode || !$expiresAt || strtotime($expiresAt) < time()) {
    
    // 3. สร้าง OTP ใหม่ 6 หลัก
    $otpCode = (string) rand(100000, 999999);
    $newExpiresAt = date('Y-m-d H:i:s', strtotime('+30 minutes'));
    
    // 4. Update กลับลงไปที่ตาราง registrations ในแถวเดิมของ User คนนี้
    $updateStmt = $conn->prepare("
        UPDATE registrations 
        SET otp_code = ?, otp_expires_at = ? 
        WHERE id = ?
    ");
    $updateStmt->bind_param('ssi', $otpCode, $newExpiresAt, $registration['id']);
    $updateStmt->execute();
}

// 5. ส่งค่าไปที่หน้า View
renderView('event/otp', [
    'otpCode' => $otpCode,
    'eventId' => $eventId
]);