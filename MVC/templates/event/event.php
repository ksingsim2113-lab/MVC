<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>กิจกรรมของฉัน</title>
</head>
<body class="bg-gray-50 min-h-screen">

<?php include TEMPLATES_DIR . '/header.php' ?>

<div class="max-w-5xl mx-auto px-4 py-8">

    <?php if (!empty($success)): ?>
        <div class="bg-green-50 border border-green-300 text-green-700 rounded-lg px-4 py-3 mb-4 text-sm">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">กิจกรรมของฉัน</h1>
        <a href="/create"
            class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
            + สร้างกิจกรรม
        </a>
    </div>

    <?php if (empty($events)): ?>
        <div class="text-center py-16 text-gray-400">
            <p class="text-4xl mb-3">📭</p>
            <p>ยังไม่มีกิจกรรม</p>
            <a href="/create" class="text-indigo-600 hover:underline text-sm mt-2 inline-block">สร้างกิจกรรมแรก</a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($events as $event): ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">

                <div class="h-40 bg-gray-200">
                    <?php if ($event['cover']): ?>
                        <img src="/<?= htmlspecialchars($event['cover']) ?>"
                            class="w-full h-full object-cover" alt="cover">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-gray-400 text-4xl">🎉</div>
                    <?php endif; ?>
                </div>

                <div class="p-4">
                    <h2 class="font-bold text-gray-800 mb-1 truncate"><?= htmlspecialchars($event['title']) ?></h2>
                    <p class="text-xs text-gray-500 mb-1">📅 <?= date('d/m/Y H:i', strtotime($event['start_date'])) ?></p>
                    <p class="text-xs text-gray-500 mb-1">📍 <?= htmlspecialchars($event['location']) ?></p>
                    <p class="text-xs text-gray-500 mb-3">👥 รับสูงสุด <?= $event['max_participants'] ?> คน</p>

                    <div class="flex gap-2">
                        <a href="/edit?id=<?= $event['id'] ?>"
                            class="flex-1 text-center text-sm bg-yellow-50 hover:bg-yellow-100 text-yellow-700 font-medium py-1.5 rounded-lg transition">
                            แก้ไข
                        </a>
                        <a href="/delete?id=<?= $event['id'] ?>"
                            onclick="return confirm('ยืนยันลบกิจกรรมนี้?')"
                            class="flex-1 text-center text-sm bg-red-50 hover:bg-red-100 text-red-600 font-medium py-1.5 rounded-lg transition">
                            ลบ
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include TEMPLATES_DIR . '/footer.php' ?>
</body>
</html>