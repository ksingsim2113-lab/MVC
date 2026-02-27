<?php


require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../databases/events.php';
require_once __DIR__ . '/../databases/registrations.php';
require_once __DIR__ . '/../databases/users.php';

$conn = getConnection();   // 🔥 ต้องมีบรรทัดนี้
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
$event = getEventById($conn, $eventId);

if (!$event) {
    die('ไม่พบกิจกรรม');
}

/* ===============================
   3) จัดการ POST (อนุมัติ / ปฏิเสธ)
================================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $registrationId = (int) ($_POST['registration_id'] ?? 0);
    $action         = $_POST['action'] ?? '';

    if ($registrationId > 0 && in_array($action, ['approved', 'rejected'], true)) {

        if ($action === 'approved') {
            $approvedCount = countApprovedByEvent($conn, $eventId);

            if ($approvedCount >= (int) $event['max_participants']) {
                $_SESSION['error'] = 'ไม่สามารถอนุมัติได้ เนื่องจากถึงจำนวนสูงสุดแล้ว';
                header('Location: /registrations?event_id=' . $eventId);
                exit;
            }
        }

        updateRegistrationStatus($conn, $registrationId, $action);

        $_SESSION['success'] =
            $action === 'approved'
            ? 'อนุมัติเรียบร้อยแล้ว'
            : 'ปฏิเสธเรียบร้อยแล้ว';
    }

    header('Location: /registrations?event_id=' . $eventId);
    exit;
}

/* ===============================
   4) ดึงรายชื่อผู้สมัคร
================================= */
$rawParticipants = getParticipantsByEventId($conn, $eventId);
$approvedCount   = countApprovedByEvent($conn, $eventId);

/* ===============================
   5) Flash Message
================================= */
$success = $_SESSION['success'] ?? '';
$error   = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);

/* ===============================
   6) เตรียมข้อมูล event สำหรับ view
================================= */
$eventView = [
    'id'               => $event['id'],
    'title'            => htmlspecialchars($event['title']),
    'location'         => htmlspecialchars($event['location']),
    'start_date'       => date('d/m/Y H:i', strtotime($event['start_date'])),
    'max_participants' => $event['max_participants'],
];

/* ===============================
   7) เตรียมข้อมูล participants
================================= */
$genderLabel = [
    'male'   => 'ชาย',
    'female' => 'หญิง',
    'other'  => 'อื่นๆ'
];

$statusBadge = [
    'pending'  => '<span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-yellow-100 text-yellow-700">รออนุมัติ</span>',
    'approved' => '<span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-green-100 text-green-700">อนุมัติแล้ว</span>',
    'rejected' => '<span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-red-100 text-red-700">ปฏิเสธ</span>',
];

$participants = [];

foreach ($rawParticipants as $p) {

    $status = $p['status'];

    $participants[] = [
        'id'             => $p['id'],
        'full_name'      => htmlspecialchars($p['first_name'] . ' ' . $p['last_name']),
        'email'          => htmlspecialchars($p['email']),
        'gender'         => $genderLabel[$p['gender']] ?? '-',
        'phone'          => htmlspecialchars($p['phone'] ?? '-'),
        'status'         => $status,
        'status_badge'   => $statusBadge[$status] ?? $status,
        'is_checked_in'  => (bool) $p['is_checked_in'],
        'can_check_in'   => $status === 'approved' && !(bool)$p['is_checked_in'],
        'can_approve'    => in_array($status, ['pending', 'rejected']),
        'can_reject'     => in_array($status, ['pending', 'approved']),
    ];
}

/* ===============================
   8) Filter
================================= */
$activeFilter = $_GET['filter'] ?? 'all';
if (!in_array($activeFilter, ['all', 'pending', 'approved', 'rejected'])) {
    $activeFilter = 'all';
}

$totalCount = count($participants);

if ($activeFilter !== 'all') {
    $participants = array_values(
        array_filter($participants, fn($p) => $p['status'] === $activeFilter)
    );
}

$filterTabs = [
    ['filter' => 'all',      'label' => 'ทั้งหมด (' . $totalCount . ')', 'active' => $activeFilter === 'all'],
    ['filter' => 'pending',  'label' => 'รออนุมัติ',                      'active' => $activeFilter === 'pending'],
    ['filter' => 'approved', 'label' => 'อนุมัติแล้ว',                    'active' => $activeFilter === 'approved'],
    ['filter' => 'rejected', 'label' => 'ถูกปฏิเสธ',                      'active' => $activeFilter === 'rejected'],
];

/* ===============================
   9) Render View
================================= */
renderView('event/registrations', [
    'event'         => $eventView,
    'participants'  => $participants,
    'approvedCount' => $approvedCount,
    'filterTabs'    => $filterTabs,
    'success'       => htmlspecialchars($success),
    'error'         => htmlspecialchars($error),
]);