<?php
declare(strict_types=1);

require_once DATABASES_DIR . '/events.php';
require_once DATABASES_DIR . '/registrations.php';

$conn   = getConnection();
$userId = (int) $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id'])) {
    $eventId = (int) $_POST['event_id'];

    // เจ้าของกิจกรรมไม่สามารถลงทะเบียนตัวเองได้
    $event = getEventById($conn, $eventId);
    if ($event && (int) $event['user_id'] === $userId) {
        $_SESSION['error'] = 'คุณเป็นเจ้าของกิจกรรมนี้';
        header('Location: /join');
        exit;
    }

    if (!hasUserRegistered($conn, $eventId, $userId)) {
        createRegistration($conn, $eventId, $userId);
        $_SESSION['success'] = 'ส่งคำขอเข้าร่วมกิจกรรมเรียบร้อยแล้ว!';
    } else {
        $_SESSION['error'] = 'คุณได้ลงทะเบียนกิจกรรมนี้ไปแล้ว';
    }

    header('Location: /join');
    exit;
}

$search    = trim($_GET['search']     ?? '');
$dateStart = trim($_GET['date_start'] ?? '');
$dateEnd   = trim($_GET['date_end']   ?? '');

$events = searchEventsWithStatus($conn, $userId, $search, $dateStart, $dateEnd);

foreach ($events as &$event) {
    $imgs = getEventImages($conn, (int) $event['id']);
    $event['cover']    = $imgs[0]['path'] ?? null;
    $event['images']   = array_column($imgs, 'path');
    $event['is_owner'] = (int) $event['user_id'] === $userId;
}
unset($event);

$success = $_SESSION['success'] ?? '';
$error   = $_SESSION['error']   ?? '';
unset($_SESSION['success'], $_SESSION['error']);

renderView('event/join', [
    'events'     => $events,
    'success'    => htmlspecialchars($success),
    'error'      => htmlspecialchars($error),
    'search'     => htmlspecialchars($search),
    'date_start' => htmlspecialchars($dateStart),
    'date_end'   => htmlspecialchars($dateEnd),
]);