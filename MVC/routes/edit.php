<?php
declare(strict_types=1);

require_once DATABASES_DIR . '/events.php';

$conn    = getConnection();
$errors  = [];
$eventId = (int) ($_GET['id'] ?? 0);

$event = getEventById($conn, $eventId);



if (!$event || $event['user_id'] !== (int) $_SESSION['user_id']) {
    header('Location: /event');
    exit;
}

$images = getEventImages($conn, $eventId);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'user_id'          => (int) $_SESSION['user_id'],
        'title'            => trim($_POST['title']             ?? ''),
        'description'      => trim($_POST['description']       ?? ''),
        'location'         => trim($_POST['location']          ?? ''),
        'max_participants' => (int) ($_POST['max_participants'] ?? 0),
        'start_date'       => $_POST['start_date']             ?? '',
        'end_date'         => $_POST['end_date']               ?? '',
    ];

    // Validate
    if (empty($data['title']))         $errors['title']       = 'กรุณากรอกชื่อกิจกรรม';
    if (empty($data['description']))   $errors['description'] = 'กรุณากรอกรายละเอียด';
    if (empty($data['location']))      $errors['location']    = 'กรุณากรอกสถานที่';
    if ($data['max_participants'] < 1) $errors['max_participants'] = 'จำนวนคนต้องมากกว่า 0';
    if (empty($data['start_date']))    $errors['start_date']  = 'กรุณาเลือกวันเริ่มต้น';
    if (empty($data['end_date']))      $errors['end_date']    = 'กรุณาเลือกวันสิ้นสุด';

    if (empty($errors)) {
        updateEvent($conn, $eventId, $data);

       
        if (!empty($_FILES['images']['name'][0])) {
            deleteEventImages($conn, $eventId);
            $paths = uploadEventImages($_FILES['images'], $eventId);
            foreach ($paths as $path) {
                addEventImage($conn, $eventId, $path);
            }
        }

        $_SESSION['success'] = 'แก้ไขกิจกรรมสำเร็จ';
        header('Location: /event');
        exit;
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($errors)) {
    $event = array_merge($event, $data);
}
renderView('event/edit', ['event' => $event, 'images' => $images, 'errors' => $errors]);