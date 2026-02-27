<?php

require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../databases/events.php';
require_once __DIR__ . '/../databases/registrations.php';

$conn = getConnection();

/* ===============================
   1) รับ event_id
================================= */
$eventId = $_GET['event_id'] ?? null;

if (!$eventId) {
    die('ไม่พบกิจกรรม');
}

/* ===============================
   2) ดึงข้อมูลกิจกรรม
================================= */
$event = getEventById($conn, (int)$eventId);

if (!$event) {
    die('ไม่พบกิจกรรม');
}

/* ===============================
   3) ดึงเฉพาะผู้ที่ approved
================================= */
$allParticipants = getParticipantsByEventId($conn, (int)$eventId);

$approvedParticipants = array_values(
    array_filter($allParticipants, fn($p) => $p['status'] === 'approved')
);

/* ===============================
   4) เตรียมข้อมูลสำหรับ view
================================= */
$participants = [];

foreach ($approvedParticipants as $p) {
    $participants[] = [
        'full_name' => htmlspecialchars($p['first_name'] . ' ' . $p['last_name']),
        'email'     => htmlspecialchars($p['email']),
        'phone'     => htmlspecialchars($p['phone'] ?? '-'),
    ];
}

$eventView = [
    'id'    => $event['id'],
    'title' => htmlspecialchars($event['title']),
];

renderView('event/participants', [
    'event'        => $eventView,
    'participants' => $participants,
]);