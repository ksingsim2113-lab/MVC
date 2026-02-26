<?php
declare(strict_types=1);

// 1. ดึงไฟล์ Database ที่จำเป็นมาให้ครบ
require_once DATABASES_DIR . '/events.php';
require_once DATABASES_DIR . '/registrations.php'; // ต้องมีไฟล์นี้เพื่อใช้ getRegistrationsByUserId

$conn   = getConnection();
$userId = (int) $_SESSION['user_id'];

// 2. จัดการเรื่อง Flash Message (Success)
$success = $_SESSION['success'] ?? '';
unset($_SESSION['success']);

// 3. ดึงข้อมูลชุดที่ 1: กิจกรรมที่ "เราไปขอเข้าร่วม" (Registrations)
$myRegistrations = getRegistrationsByUserId($conn, $userId); 
foreach ($myRegistrations as &$r) {
    // ต้องระวัง: ใน registrations table ไม่มี id กิจกรรมตรงๆ ต้องอ้างอิง event_id
    $imgs = getEventImages($conn, (int) $r['event_id']);
    $r['cover'] = $imgs[0]['path'] ?? null;
}

// 4. ดึงข้อมูลชุดที่ 2: กิจกรรมที่ "เราเป็นคนสร้าง" (My Created Events)
$myCreatedEvents = getEventsByUserId($conn, $userId);
foreach ($myCreatedEvents as &$e) {
    $imgs = getEventImages($conn, (int) $e['id']);
    $e['cover'] = $imgs[0]['path'] ?? null;
}

// 5. ส่งค่าไปที่ View (ใช้ชื่อไฟล์ View ที่คุณตั้งไว้ เช่น event/event หรือ event/my_events)
renderView('event/event', [
    'registrations' => $myRegistrations, 
    'events'        => $myCreatedEvents,
    'success'       => $success
]);