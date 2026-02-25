<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>สมัครสมาชิก</title>
</head>

<body class="bg-gray-50 min-h-screen flex items-center justify-center py-10">

    <div class="bg-white w-full max-w-lg rounded-2xl shadow-md p-8">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">สมัครสมาชิก</h2>

        <form method="POST" action="/register">

            <!-- ชื่อ - นามสกุล -->
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">ชื่อ</label>
                    <input type="text" name="first_name"
                        value="<?= htmlspecialchars($old['first_name'] ?? '') ?>"
                        placeholder="ชื่อ"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400
                    <?= !empty($errors['first_name']) ? 'border-red-400' : '' ?>">
                    <?php if (!empty($errors['first_name'])): ?>
                        <p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($errors['first_name']) ?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">นามสกุล</label>
                    <input type="text" name="last_name"
                        value="<?= htmlspecialchars($old['last_name'] ?? '') ?>"
                        placeholder="นามสกุล"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400
                    <?= !empty($errors['last_name']) ? 'border-red-400' : '' ?>">
                    <?php if (!empty($errors['last_name'])): ?>
                        <p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($errors['last_name']) ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">อีเมล</label>
                <input type="email" name="email"
                    value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                    placeholder="example@email.com"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400
                <?= !empty($errors['email']) ? 'border-red-400' : '' ?>">
                <?php if (!empty($errors['email'])): ?>
                    <p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($errors['email']) ?></p>
                <?php endif; ?>
            </div>

            <!-- Password -->
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">รหัสผ่าน</label>
                    <input type="password" name="password" placeholder="อย่างน้อย 8 ตัว"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400
                    <?= !empty($errors['password']) ? 'border-red-400' : '' ?>">
                    <?php if (!empty($errors['password'])): ?>
                        <p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($errors['password']) ?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">ยืนยันรหัสผ่าน</label>
                    <input type="password" name="confirm_password" placeholder="ยืนยันรหัสผ่าน"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400
                    <?= !empty($errors['confirm']) ? 'border-red-400' : '' ?>">
                    <?php if (!empty($errors['confirm'])): ?>
                        <p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($errors['confirm']) ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- เพศ / วันเกิด -->
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">เพศ</label>
                    <select name="gender"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400
                    <?= !empty($errors['gender']) ? 'border-red-400' : '' ?>">
                        <option value="">-- เลือกเพศ --</option>
                        <option value="male" <?= ($old['gender'] ?? '') === 'male'   ? 'selected' : '' ?>>ชาย</option>
                        <option value="female" <?= ($old['gender'] ?? '') === 'female' ? 'selected' : '' ?>>หญิง</option>
                        <option value="other" <?= ($old['gender'] ?? '') === 'other'  ? 'selected' : '' ?>>อื่นๆ</option>
                    </select>
                    <?php if (!empty($errors['gender'])): ?>
                        <p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($errors['gender']) ?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">วันเกิด</label>
                    <input type="date" name="birthdate"
                        value="<?= htmlspecialchars($old['birthdate'] ?? '') ?>"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400
                    <?= !empty($errors['birthdate']) ? 'border-red-400' : '' ?>">
                    <?php if (!empty($errors['birthdate'])): ?>
                        <p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($errors['birthdate']) ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- เบอร์โทร -->
            <div class="mb-6">
                <label class="block text-sm text-gray-600 mb-1">เบอร์โทร <span class="text-gray-400">(ไม่บังคับ)</span></label>
                <input type="tel" name="phone"
                    value="<?= htmlspecialchars($old['phone'] ?? '') ?>"
                    placeholder="0812345678"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>

            <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-lg transition">
                สมัครสมาชิก
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-4">
            มีบัญชีแล้ว?
            <a href="/login" class="text-indigo-600 hover:underline font-medium">เข้าสู่ระบบ</a>
        </p>
    </div>

</body>

</html>