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

$stmt = $conn->prepare("
    SELECT id, status, otp_code, otp_expires_at , is_checked_in
    FROM registrations 
    WHERE event_id = ? AND user_id = ? 
    LIMIT 1
");
$stmt->bind_param('ii', $eventId, $userId);
$stmt->execute();
$registration = $stmt->get_result()->fetch_assoc();


if (!$registration || $registration['status'] !== 'approved') {
    die("คุณไม่มีสิทธิ์เข้าถึง หรือยังไม่ได้รับการอนุมัติ");
}

$otpCode = $registration['otp_code'];
$expiresAt = $registration['otp_expires_at'];


if (!$otpCode || !$expiresAt || strtotime($expiresAt) < time()) {
    
    
    $otpCode = (string) rand(100000, 999999);
    $newExpiresAt = date('Y-m-d H:i:s', strtotime('+30 minutes'));
    
   
    $updateStmt = $conn->prepare("
        UPDATE registrations 
        SET otp_code = ?, otp_expires_at = ? 
        WHERE id = ?
    ");
    $updateStmt->bind_param('ssi', $otpCode, $newExpiresAt, $registration['id']);
    $updateStmt->execute();
}
if ((bool)$registration['is_checked_in']) {
    $_SESSION['error'] = "คุณได้เช็คอินเข้าร่วมงานนี้ไปแล้ว";
    header('Location: /event');
    exit;
}


renderView('event/otp', [
    'otpCode' => $otpCode,
    'eventId' => $eventId
]);