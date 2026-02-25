<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>แก้ไขกิจกรรม</title>
</head>
<body class="bg-gray-50 min-h-screen">

<?php include TEMPLATES_DIR . '/header.php' ?>

<div class="max-w-2xl mx-auto px-4 py-8">
    <div class="bg-white rounded-2xl shadow-md p-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">แก้ไขกิจกรรม</h1>

        <!-- รูปภาพปัจจุบัน -->
        <?php if (!empty($images)): ?>
        <div class="mb-4">
            <label class="block text-sm text-gray-600 mb-2">รูปภาพปัจจุบัน</label>
            <div class="flex gap-2 flex-wrap">
                <?php foreach ($images as $img): ?>
                    <img src="/<?= htmlspecialchars($img['path']) ?>"
                        class="w-20 h-20 object-cover rounded-lg border border-gray-200" alt="event">
                <?php endforeach; ?>
            </div>
            <p class="text-xs text-gray-400 mt-1">อัปโหลดรูปใหม่เพื่อแทนที่รูปเดิมทั้งหมด</p>
        </div>
        <?php endif; ?>

        <form method="POST" action="/event/edit?id=<?= $event['id'] ?>" enctype="multipart/form-data">

            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">ชื่อกิจกรรม</label>
                <input type="text" name="title"
                    value="<?= htmlspecialchars($event['title']) ?>"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400
                    <?= !empty($errors['title']) ? 'border-red-400' : '' ?>">
                <?php if (!empty($errors['title'])): ?>
                    <p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($errors['title']) ?></p>
                <?php endif; ?>
            </div>

            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">รายละเอียด</label>
                <textarea name="description" rows="4"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"><?= htmlspecialchars($event['description']) ?></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">สถานที่</label>
                <input type="text" name="location"
                    value="<?= htmlspecialchars($event['location']) ?>"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>

            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">จำนวนคนที่รับสูงสุด</label>
                <input type="number" name="max_participants" min="1"
                    value="<?= $event['max_participants'] ?>"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">วันเริ่มต้น</label>
                    <input type="datetime-local" name="start_date"
                        value="<?= date('Y-m-d\TH:i', strtotime($event['start_date'])) ?>"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">วันสิ้นสุด</label>
                    <input type="datetime-local" name="end_date"
                        value="<?= date('Y-m-d\TH:i', strtotime($event['end_date'])) ?>"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm text-gray-600 mb-1">อัปโหลดรูปภาพใหม่ <span class="text-gray-400">(ไม่บังคับ)</span></label>
                <input type="file" name="images[]" accept="image/*" multiple
                    class="text-sm text-gray-500 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100">
            </div>

            <div class="flex gap-3">
                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                    บันทึก
                </button>
                <a href="/event"
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