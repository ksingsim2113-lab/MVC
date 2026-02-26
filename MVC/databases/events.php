<?php
declare(strict_types=1);

// ดึงกิจกรรมทั้งหมดของ user คนนั้น
function getEventsByUserId(mysqli $conn, int $userId): array
{
    $stmt = $conn->prepare("SELECT * FROM events WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// ดึงกิจกรรมจาก id (พร้อมเช็คว่าเป็นเจ้าของ)
function getEventById(mysqli $conn, int $id): array|false
{
    $stmt = $conn->prepare("SELECT * FROM events WHERE id = ? LIMIT 1");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// ดึงรูปภาพของกิจกรรม
function getEventImages(mysqli $conn, int $eventId): array
{
    $stmt = $conn->prepare("SELECT * FROM event_images WHERE event_id = ?");
    $stmt->bind_param('i', $eventId);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// สร้างกิจกรรม
function createEvent(mysqli $conn, array $data): int
{
    $stmt = $conn->prepare(
        "INSERT INTO events (user_id, title, description, location, max_participants, start_date, end_date)
         VALUES (?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param(
        'isssiis',
        $data['user_id'],
        $data['title'],
        $data['description'],
        $data['location'],
        $data['max_participants'],
        $data['start_date'],
        $data['end_date']
    );
    $stmt->execute();
    return (int) $conn->insert_id;
}

// เพิ่มรูปภาพ
function addEventImage(mysqli $conn, int $eventId, string $path): void
{
    $stmt = $conn->prepare("INSERT INTO event_images (event_id, path) VALUES (?, ?)");
    $stmt->bind_param('is', $eventId, $path);
    $stmt->execute();
}

// ลบรูปภาพของกิจกรรม
function deleteEventImages(mysqli $conn, int $eventId): void
{
    $stmt = $conn->prepare("DELETE FROM event_images WHERE event_id = ?");
    $stmt->bind_param('i', $eventId);
    $stmt->execute();
}

// แก้ไขกิจกรรม
function updateEvent(mysqli $conn, int $id, array $data): void
{
    $stmt = $conn->prepare(
        "UPDATE events SET title=?, description=?, location=?, max_participants=?, start_date=?, end_date=?
         WHERE id=? AND user_id=?"
    );
    $stmt->bind_param(
        'sssisiii',
        $data['title'],
        $data['description'],
        $data['location'],
        $data['max_participants'],
        $data['start_date'],
        $data['end_date'],
        $id,
        $data['user_id']
    );
    $stmt->execute();
}

// ลบกิจกรรม
function deleteEvent(mysqli $conn, int $id, int $userId): void
{
    $stmt = $conn->prepare("DELETE FROM events WHERE id = ? AND user_id = ?");
    $stmt->bind_param('ii', $id, $userId);
    $stmt->execute();
}

// อัปโหลดรูปภาพหลายรูป
function uploadEventImages(array $files, int $eventId): array
{
    $uploaded  = [];
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $maxSize      = 5 * 1024 * 1024; // 5MB

    $uploadDir = __DIR__ . '/../public/uploads/events/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

    foreach ($files['tmp_name'] as $i => $tmp) {
        if ($files['error'][$i] !== UPLOAD_ERR_OK) continue;
        if (!in_array($files['type'][$i], $allowedTypes)) continue;
        if ($files['size'][$i] > $maxSize) continue;

        $ext      = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
        $filename = uniqid('event_', true) . '.' . $ext;
        if (move_uploaded_file($tmp, $uploadDir . $filename)) {
            $uploaded[] = 'uploads/events/' . $filename;
        }
    }
    return $uploaded;
}

// ค้นหากิจกรรมทั้งหมด (ไม่กรอง user)
function searchEvents(mysqli $conn, string $keyword = ''): array
{
    if ($keyword !== '') {
        $like = '%' . $keyword . '%';
        $stmt = $conn->prepare("SELECT * FROM events WHERE title LIKE ? ORDER BY start_date ASC");
        $stmt->bind_param('s', $like);
    } else {
        $stmt = $conn->prepare("SELECT * FROM events ORDER BY start_date ASC");
    }
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}