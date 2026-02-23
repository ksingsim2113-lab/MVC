<?php
declare(strict_types=1);

// ดึง user จาก email
function getUserByEmail(mysqli $conn, string $email): array|false
{
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// ดึง user จาก id
function getUserById(mysqli $conn, int $id): array|false
{
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// เช็ค email ซ้ำ
function isEmailExists(mysqli $conn, string $email): bool
{
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    return $stmt->num_rows > 0;
}

// สร้าง user ใหม่
function createUser(mysqli $conn, array $data): int
{
    $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
    $stmt = $conn->prepare(
        "INSERT INTO users (email, password, first_name, last_name, gender, birthdate, phone)
         VALUES (?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param(
        'sssssss',
        $data['email'],
        $hashedPassword,
        $data['first_name'],
        $data['last_name'],
        $data['gender'],
        $data['birthdate'],
        $data['phone']
    );
    $stmt->execute();
    return (int) $conn->insert_id;
}

// อัปเดตโปรไฟล์
function updateUser(mysqli $conn, int $id, array $data): void
{
    $stmt = $conn->prepare(
        "UPDATE users SET first_name=?, last_name=?, gender=?, birthdate=?, phone=?, profile_img=?
         WHERE id=?"
    );
    $stmt->bind_param(
        'ssssssi',
        $data['first_name'],
        $data['last_name'],
        $data['gender'],
        $data['birthdate'],
        $data['phone'],
        $data['profile_img'],
        $id
    );
    $stmt->execute();
}