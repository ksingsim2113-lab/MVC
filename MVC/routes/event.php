<?php
declare(strict_types=1);


require_once DATABASES_DIR . '/events.php';

$conn    = getConnection();
$events  = getEventsByUserId($conn, (int) $_SESSION['user_id']);
$success = $_SESSION['success'] ?? '';
unset($_SESSION['success']);

// ดึงรูปแรกของแต่ละกิจกรรม
foreach ($events as &$event) {
    $imgs = getEventImages($conn, (int) $event['id']);
    $event['cover'] = $imgs[0]['path'] ?? null;
}

renderView('event/event', ['events' => $events, 'success' => $success]);