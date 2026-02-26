<?php
declare(strict_types=1);

require_once DATABASES_DIR . '/events.php';
require_once DATABASES_DIR . '/registrations.php'; // ดึงไฟล์ใหม่ที่เราสร้าง

$conn   = getConnection();
$userId = (int) $_SESSION['user_id'];

// 1. จัดการการกดปุ่ม "ลงทะเบียน" (ถ้ามีการ POST มา)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id'])) {
    $eventId = (int) $_POST['event_id'];
    
    // เช็คก่อนว่าเคยสมัครไปหรือยังเพื่อป้องกันข้อมูลซ้ำ
    if (!hasUserRegistered($conn, $eventId, $userId)) {
        createRegistration($conn, $eventId, $userId);
        $_SESSION['success'] = 'ส่งคำขอเข้าร่วมกิจกรรมเรียบร้อยแล้ว!';
    } else {
        $_SESSION['error'] = 'คุณได้ลงทะเบียนกิจกรรมนี้ไปแล้ว';
    }
    
    header('Location: /join'); // ป้องกันการกด Refresh แล้วส่งข้อมูลซ้ำ
    exit;
}

// 2. ดึงกิจกรรมทั้งหมดพร้อมสถานะของ User คนนี้มาแสดง
$events = getAllEventsWithStatus($conn, $userId);

$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? ''; // เพิ่มการจัดการข้อความแจ้งเตือนข้อผิดพลาด
unset($_SESSION['success'], $_SESSION['error']);

// ดึงรูปภาพประกอบ (ใช้ฟังก์ชันที่คุณส่งมา)
foreach ($events as &$event) {
    $imgs = getEventImages($conn, (int) $event['id']);
    $event['cover'] = $imgs[0]['path'] ?? null;
}

// ส่งค่าไปที่ join_view.php
renderView('event/join', [
    'events' => $events, 
    'success' => $success,
    'error' => $error
]);