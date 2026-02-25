<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>สร้างกิจกรรม</title>
</head>
<body class="bg-gray-50 min-h-screen">

<?php include TEMPLATES_DIR . '/header.php' ?>

<div class="max-w-2xl mx-auto px-4 py-8">
    <div class="bg-white rounded-2xl shadow-md p-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">สร้างกิจกรรม</h1>

        <form method="POST" action="/create" enctype="multipart/form-data">

            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">ชื่อกิจกรรม</label>
                <input type="text" name="title"
                    value="<?= htmlspecialchars($old['title'] ?? '') ?>"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400
                    <?= !empty($errors['title']) ? 'border-red-400' : '' ?>">
                <?php if (!empty($errors['title'])): ?>
                    <p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($errors['title']) ?></p>
                <?php endif; ?>
            </div>

            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">รายละเอียด</label>
                <textarea name="description" rows="4"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400
                    <?= !empty($errors['description']) ? 'border-red-400' : '' ?>"><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
                <?php if (!empty($errors['description'])): ?>
                    <p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($errors['description']) ?></p>
                <?php endif; ?>
            </div>

            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">สถานที่</label>
                <input type="text" name="location"
                    value="<?= htmlspecialchars($old['location'] ?? '') ?>"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400
                    <?= !empty($errors['location']) ? 'border-red-400' : '' ?>">
                <?php if (!empty($errors['location'])): ?>
                    <p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($errors['location']) ?></p>
                <?php endif; ?>
            </div>

            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">จำนวนคนที่รับสูงสุด</label>
                <input type="number" name="max_participants" min="1"
                    value="<?= htmlspecialchars($old['max_participants'] ?? '') ?>"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400
                    <?= !empty($errors['max_participants']) ? 'border-red-400' : '' ?>">
                <?php if (!empty($errors['max_participants'])): ?>
                    <p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($errors['max_participants']) ?></p>
                <?php endif; ?>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">วันเริ่มต้น</label>
                    <input type="datetime-local" name="start_date"
                        value="<?= htmlspecialchars($old['start_date'] ?? '') ?>"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400
                        <?= !empty($errors['start_date']) ? 'border-red-400' : '' ?>">
                    <?php if (!empty($errors['start_date'])): ?>
                        <p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($errors['start_date']) ?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">วันสิ้นสุด</label>
                    <input type="datetime-local" name="end_date"
                        value="<?= htmlspecialchars($old['end_date'] ?? '') ?>"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400
                        <?= !empty($errors['end_date']) ? 'border-red-400' : '' ?>">
                    <?php if (!empty($errors['end_date'])): ?>
                        <p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($errors['end_date']) ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm text-gray-600 mb-1">รูปภาพกิจกรรม <span class="text-gray-400">(เลือกได้หลายรูป)</span></label>
                <input type="file" name="images[]" accept="image/*" multiple
                    class="text-sm text-gray-500 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100">
                <p class="text-xs text-gray-400 mt-1">JPG, PNG, GIF, WEBP ไม่เกิน 5MB ต่อรูป</p>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                    สร้างกิจกรรม
                </button>
                <a href="/events"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold px-6 py-2 rounded-lg transition">
                    ยกเลิก
                </a>
            </div>

        </form>
    </div>
</div>

<?php include TEMPLATES_DIR . '/footer.php' ?>
</body>
</html>