<?php
declare(strict_types=1);

require_once DATABASES_DIR . '/events.php';
require_once DATABASES_DIR . '/registrations.php';

$conn   = getConnection();
$userId = (int) $_SESSION['user_id'];

// 1. จัดการการกดปุ่ม "ลงทะเบียน"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id'])) {
    $eventId = (int) $_POST['event_id'];

    if (!hasUserRegistered($conn, $eventId, $userId)) {
        createRegistration($conn, $eventId, $userId);
        $_SESSION['success'] = 'ส่งคำขอเข้าร่วมกิจกรรมเรียบร้อยแล้ว!';
    } else {
        $_SESSION['error'] = 'คุณได้ลงทะเบียนกิจกรรมนี้ไปแล้ว';
    }

    header('Location: /join');
    exit;
}

// 2. รับค่า search จาก GET
$search    = trim($_GET['search']     ?? '');
$dateStart = trim($_GET['date_start'] ?? '');
$dateEnd   = trim($_GET['date_end']   ?? '');

// 3. ดึงกิจกรรมพร้อม filter search
$events = searchEventsWithStatus($conn, $userId, $search, $dateStart, $dateEnd);

// 4. ดึงรูปภาพ
foreach ($events as &$event) {
    $imgs = getEventImages($conn, (int) $event['id']);
    $event['cover'] = $imgs[0]['path'] ?? null;
}
unset($event);

// 5. Flash messages
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