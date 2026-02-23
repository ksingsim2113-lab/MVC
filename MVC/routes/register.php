<?php
declare(strict_types=1);

require_once DATABASES_DIR . '/users.php';

$conn   = getConnection();
$errors = [];
$old    = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old = [
        'email'      => trim($_POST['email']       ?? ''),
        'first_name' => trim($_POST['first_name']  ?? ''),
        'last_name'  => trim($_POST['last_name']   ?? ''),
        'gender'     => $_POST['gender']           ?? '',
        'birthdate'  => $_POST['birthdate']        ?? '',
        'phone'      => trim($_POST['phone']       ?? ''),
    ];
    $password        = $_POST['password']         ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // --- Validate ---
    if (empty($old['email']) || !filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'กรุณากรอกอีเมลให้ถูกต้อง';
    } elseif (isEmailExists($conn, $old['email'])) {
        $errors['email'] = 'อีเมลนี้ถูกใช้งานแล้ว';
    }

    if (strlen($password) < 8) {
        $errors['password'] = 'รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร';
    }
    if ($password !== $confirmPassword) {
        $errors['confirm'] = 'รหัสผ่านไม่ตรงกัน';
    }
    if (empty($old['first_name'])) {
        $errors['first_name'] = 'กรุณากรอกชื่อ';
    }
    if (empty($old['last_name'])) {
        $errors['last_name'] = 'กรุณากรอกนามสกุล';
    }
    if (!in_array($old['gender'], ['male', 'female', 'other'])) {
        $errors['gender'] = 'กรุณาเลือกเพศ';
    }
    if (empty($old['birthdate'])) {
        $errors['birthdate'] = 'กรุณากรอกวันเกิด';
    }

    if (empty($errors)) {
        createUser($conn, array_merge($old, ['password' => $password]));
        $_SESSION['success'] = 'สมัครสมาชิกสำเร็จ กรุณาเข้าสู่ระบบ';
        header('Location: /login');
        exit;
    }
}

renderView('users/register', ['errors' => $errors, 'old' => $old]);