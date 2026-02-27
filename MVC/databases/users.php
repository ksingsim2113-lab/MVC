<?php

declare(strict_types=1);


function getUserByEmail(mysqli $conn, string $email): array|false
{
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}


function isEmailExists(mysqli $conn, string $email): bool
{
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    return $stmt->num_rows > 0;
}


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
