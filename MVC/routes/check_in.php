<?php
declare(strict_types=1);
date_default_timezone_set('Asia/Bangkok');

require_once DATABASES_DIR . '/registrations.php';
$conn = getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $regId = (int) $_POST['registration_id'];
    $eventId = (int) $_POST['event_id'];
    $otpInput = $_POST['otp_code'] ?? '';

    
    $result = validateOTP($conn, $otpInput, $eventId);

    if ($result['success'] && $result['reg_id'] === $regId) {
        
        markAsCheckedIn($conn, $regId);
        $_SESSION['success'] = 'เช็คอินสำเร็จ! ยินดีต้อนรับเข้าสู่งาน';
    } else {
        $_SESSION['error'] = 'รหัส OTP ไม่ถูกต้องหรือหมดอายุ';
    }

    header("Location: /registrations?event_id=" . $eventId);
    exit;
}