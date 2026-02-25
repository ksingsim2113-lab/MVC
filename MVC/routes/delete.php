<?php
declare(strict_types=1);

require_once DATABASES_DIR . '/events.php';

$conn    = getConnection();
$eventId = (int) ($_GET['id'] ?? 0);
$event   = getEventById($conn, $eventId);

// เช็คว่าเป็นเจ้าของ
if (!$event || $event['user_id'] !== (int) $_SESSION['user_id']) {
    header('Location: /event');
    exit;
}

// ลบรูปภาพออกจาก server ก่อน
$images = getEventImages($conn, $eventId);
foreach ($images as $img) {
    $path = __DIR__ . '/../../public/' . $img['path'];
    if (file_exists($path)) unlink($path);
}

deleteEvent($conn, $eventId, (int) $_SESSION['user_id']);
$_SESSION['success'] = 'ลบกิจกรรมสำเร็จ';
header('Location: /events');
exit;