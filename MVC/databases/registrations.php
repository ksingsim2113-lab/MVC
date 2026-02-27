<?php

declare(strict_types=1);

/**
 * 1. ดึงรายการกิจกรรมที่ผู้ใช้คนนั้นขอเข้าร่วม
 */
function getRegistrationsByUserId(mysqli $conn, int $userId): array
{
    $sql = "SELECT r.*, e.title, e.start_date, e.location 
            FROM registrations r 
            JOIN events e ON r.event_id = e.id 
            WHERE r.user_id = ? 
            ORDER BY r.created_at DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $userId);
    $stmt->execute();

    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

/**
 * 2. ดึงรายชื่อคนขอเข้าร่วมกิจกรรม (สำหรับ Host)
 */
function getParticipantsByEventId(mysqli $conn, int $eventId): array
{
    $sql = "SELECT r.*, u.first_name, u.last_name, u.email, u.gender, u.phone, u.birthdate
            FROM registrations r
            JOIN users u ON r.user_id = u.id
            WHERE r.event_id = ?
            ORDER BY r.created_at ASC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $eventId);
    $stmt->execute();

    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

/**
 * 3. อนุมัติหรือปฏิเสธ
 */
function updateRegistrationStatus(mysqli $conn, int $registrationId, string $status): bool
{
    $allowed = ['approved', 'rejected'];

    if (!in_array($status, $allowed, true)) {
        return false;
    }

    $stmt = $conn->prepare("UPDATE registrations SET status = ? WHERE id = ?");
    $stmt->bind_param('si', $status, $registrationId);

    return $stmt->execute();
}

/**
 * 4. นับจำนวนที่อนุมัติแล้ว
 */
function countApprovedByEvent(mysqli $conn, int $eventId): int
{
    $stmt = $conn->prepare(
        "SELECT COUNT(*) as total 
         FROM registrations 
         WHERE event_id = ? AND status = 'approved'"
    );

    $stmt->bind_param('i', $eventId);
    $stmt->execute();

    $result = $stmt->get_result()->fetch_assoc();

    return (int) $result['total'];
}

/**
 * 5. เช็คอิน (ต้อง approved เท่านั้น)
 */
function markAsCheckedIn(mysqli $conn, int $registrationId): bool
{
    $stmt = $conn->prepare(
        "UPDATE registrations 
         SET is_checked_in = 1 
         WHERE id = ? AND status = 'approved'"
    );

    $stmt->bind_param('i', $registrationId);

    return $stmt->execute();
}

/**
 * 6. สมัครเข้าร่วมกิจกรรม
 */
function createRegistration(mysqli $conn, int $eventId, int $userId): int
{
    if (hasUserRegistered($conn, $eventId, $userId)) {
        return 0;
    }

    $status = 'pending';

    $stmt = $conn->prepare(
        "INSERT INTO registrations (event_id, user_id, status) VALUES (?, ?, ?)"
    );

    $stmt->bind_param('iis', $eventId, $userId, $status);
    $stmt->execute();

    return (int) $conn->insert_id;
}

/**
 * 7. ดึงกิจกรรมทั้งหมด พร้อมสถานะของ user
 */
function getAllEventsWithStatus(mysqli $conn, int $currentUserId): array
{
    $sql = "SELECT e.*, r.status AS reg_status, r.id AS reg_id 
            FROM events e
            LEFT JOIN registrations r
            ON e.id = r.event_id AND r.user_id = ?
            ORDER BY e.created_at DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $currentUserId);
    $stmt->execute();

    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

/**
 * 8. เช็คว่าสมัครแล้วหรือยัง
 */
function hasUserRegistered(mysqli $conn, int $eventId, int $userId): bool
{
    $stmt = $conn->prepare(
        "SELECT id FROM registrations WHERE event_id = ? AND user_id = ? LIMIT 1"
    );

    $stmt->bind_param('ii', $eventId, $userId);
    $stmt->execute();
    $stmt->store_result();

    return $stmt->num_rows > 0;
}

/**
 * 9. ตรวจสอบ OTP
 */
function validateOTP(mysqli $conn, string $inputOtp, int $eventId): array
{
    $sql = "SELECT id, otp_expires_at, is_checked_in
            FROM registrations
            WHERE otp_code = ? AND event_id = ?
            LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $inputOtp, $eventId);
    $stmt->execute();

    $result = $stmt->get_result()->fetch_assoc();

    if (!$result) {
        return ['success' => false, 'message' => 'ไม่พบรหัส OTP นี้ในระบบ'];
    }

    if (strtotime($result['otp_expires_at']) < time()) {
        return ['success' => false, 'message' => 'รหัส OTP หมดอายุแล้ว'];
    }

    if ($result['is_checked_in'] == 1) {
        return ['success' => false, 'message' => 'รหัสนี้ถูกใช้เช็คอินไปแล้ว'];
    }

    return [
        'success' => true,
        'message' => 'ตรวจสอบสำเร็จ',
        'reg_id'  => (int) $result['id']
    ];
}
