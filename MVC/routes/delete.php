<?php
declare(strict_types=1);

require_once DATABASES_DIR . '/events.php';

$conn    = getConnection();
$eventId = (int) ($_GET['id'] ?? 0);

if ($eventId <= 0) {
    header('Location: /event');
    exit;
}

$event = getEventById($conn, $eventId);

if (!$event || $event['user_id'] !== (int) $_SESSION['user_id']) {
    header('Location: /event');
    exit;
}

$images   = getEventImages($conn, $eventId);
$realBase = realpath(__DIR__ . '/../public/');

try {
    deleteEvent($conn, $eventId, (int) $_SESSION['user_id']);
} catch (Exception $e) {
    error_log('deleteEvent ล้มเหลว: ' . $e->getMessage());
    $_SESSION['error'] = 'ไม่สามารถลบกิจกรรมได้';
    header('Location: /event');
    exit;
}

foreach ($images as $img) {
    $realPath = realpath($realBase . '/' . $img['path']);
    if ($realPath && str_starts_with($realPath, $realBase)) {
        if (!unlink($realPath)) {
            error_log("ลบไฟล์ไม่สำเร็จ: $realPath");
        }
    }
}

$_SESSION['success'] = 'ลบกิจกรรมสำเร็จ';
header('Location: /event');
exit;