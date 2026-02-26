<?php

declare(strict_types=1);

/**
 * 1. สร้างการลงทะเบียนใหม่ (ข้อ 3.2)
 * สำหรับผู้ใช้งานกดสมัครเข้าร่วมกิจกรรม
 */


/**
 * 2. ดึงรายการกิจกรรมที่ผู้ใช้คนนั้นขอเข้าร่วม (ข้อ 3.3)
 * ใช้สำหรับแสดงในหน้า "กิจกรรมของฉัน" เพื่อดูสถานะ Approved/Rejected
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
 * 3. ดึงรายชื่อคนคนขอเข้าร่วมกิจกรรม (ข้อ 2.2)
 * สำหรับผู้สร้างกิจกรรม (Host) เข้ามาดูว่าใครสมัครมาบ้าง
 */
function getParticipantsByEventId(mysqli $conn, int $eventId): array
{
    $sql = "SELECT r.*, u.first_name, u.last_name, u.email, u.gender, u.phone 
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
 * 4. อนุมัติหรือปฏิเสธการเข้าร่วม (ข้อ 2.3)
 * สำหรับผู้สร้างกิจกรรมกดจัดการสถานะ
 */
function updateRegistrationStatus(mysqli $conn, int $registrationId, string $status): bool
{
    // $status ต้องเป็น 'approved' หรือ 'rejected' เท่านั้น
    $stmt = $conn->prepare("UPDATE registrations SET status = ? WHERE id = ?");
    $stmt->bind_param('si', $status, $registrationId);
    return $stmt->execute();
}

/**
 * 5. เช็คชื่อเข้างาน (Check-in) (ข้อ 2.7)
 * เมื่อตรวจสอบ OTP ถูกต้องแล้ว ให้เปลี่ยนสถานะการเข้างาน
 */
function markAsCheckedIn(mysqli $conn, int $registrationId): bool
{
    $stmt = $conn->prepare("UPDATE registrations SET is_checked_in = 1 WHERE id = ?");
    $stmt->bind_param('i', $registrationId);
    return $stmt->execute();
}

/**
 * สร้างการลงทะเบียนใหม่ (ข้อ 3.2)
 * ใช้ในจังหวะที่มีการ POST ค่า event_id มาจากหน้า join_view
 */
function createRegistration(mysqli $conn, int $eventId, int $userId): int
{
    // กำหนดสถานะเริ่มต้นเป็น 'pending'
    $status = 'pending';
    $stmt = $conn->prepare(
        "INSERT INTO registrations (event_id, user_id, status) VALUES (?, ?, ?)"
    );
    $stmt->bind_param('iis', $eventId, $userId, $status);
    $stmt->execute();
    return (int) $conn->insert_id;
}

/**
 * ดึงกิจกรรมทั้งหมด พร้อมสถานะการสมัครของ User คนนั้น (ข้อ 3.3)
 * ฟังก์ชันนี้สำคัญมาก เพราะจะทำให้หน้า join_view รู้ว่าปุ่มไหนควรเป็น "ลงทะเบียน" หรือ "รออนุมัติ"
 */
function getAllEventsWithStatus(mysqli $conn, int $currentUserId): array
{
    // ใช้ LEFT JOIN เพื่อดึงทุกกิจกรรม และเช็คว่า user คนนี้สมัครหรือยัง
    $sql = "SELECT e.*, r.status AS reg_status, r.id AS reg_id 
            FROM events e 
            LEFT JOIN registrations r ON e.id = r.event_id AND r.user_id = ?
            ORDER BY e.created_at DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $currentUserId);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

/**
 * ตรวจสอบว่า User คนนี้ลงทะเบียนกิจกรรมนี้ไปหรือยัง (ป้องกันการลงทะเบียนซ้ำ)
 */
function hasUserRegistered(mysqli $conn, int $eventId, int $userId): bool
{
    $stmt = $conn->prepare("SELECT id FROM registrations WHERE event_id = ? AND user_id = ? LIMIT 1");
    $stmt->bind_param('ii', $eventId, $userId);
    $stmt->execute();
    $stmt->store_result();
    return $stmt->num_rows > 0;
}