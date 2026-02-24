<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>เข้าสู่ระบบ</title>
</head>

<body class="bg-gray-50 min-h-screen flex items-center justify-center">

    <div class="bg-white w-full max-w-md rounded-2xl shadow-md p-8">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">เข้าสู่ระบบ</h2>

        <?php if (!empty($success)): ?>
            <div class="bg-green-50 border border-green-300 text-green-700 rounded-lg px-4 py-3 mb-4 text-sm">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="bg-red-50 border border-red-300 text-red-700 rounded-lg px-4 py-3 mb-4 text-sm">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/login">
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">อีเมล</label>
                <input type="email" name="email" placeholder="example@email.com" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>
            <div class="mb-6">
                <label class="block text-sm text-gray-600 mb-1">รหัสผ่าน</label>
                <input type="password" name="password" placeholder="รหัสผ่าน" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>
            <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-lg transition">
                เข้าสู่ระบบ
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-4">
            ยังไม่มีบัญชี?
            <a href="/register" class="text-indigo-600 hover:underline font-medium">สมัครสมาชิก</a>
        </p>
    </div>

</body>

</html>