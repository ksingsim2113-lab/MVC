<?php
declare(strict_types=1);

require_once DATABASES_DIR . '/users.php';

// ถ้า login แล้ว redirect ไป profile
if (isset($_SESSION['user_id'])) {
    header('Location: /event');
    exit;
}

$error   = '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['success']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']    ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'กรุณากรอกอีเมลและรหัสผ่าน';
    } else {
        $conn = getConnection();
        $user = getUserByEmail($conn, $email);

        if (!$user || !password_verify($password, $user['password'])) {
            $error = 'อีเมลหรือรหัสผ่านไม่ถูกต้อง';
        } else {
            $_SESSION['user_id']    = $user['id'];
            $_SESSION['user_name']  = $user['first_name'] . ' ' . $user['last_name'];
            $_SESSION['user_email'] = $user['email'];
            header('Location: /event');
            exit;
        }
    }
}

renderView('users/login', ['error' => $error, 'success' => $success]);