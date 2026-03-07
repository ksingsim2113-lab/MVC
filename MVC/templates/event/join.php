<?php

/**
 * @var array  $events
 * @var string $success
 * @var string $error
 * @var string $search
 * @var string $date_start
 * @var string $date_end
 */
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>เข้าร่วมกิจกรรม</title>
</head>

<body class="bg-gray-50 min-h-screen">

    <?php include TEMPLATES_DIR . '/header.php' ?>

    <div class="max-w-5xl mx-auto px-4 py-8">

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">ค้นหาและเข้าร่วมกิจกรรม</h1>
            <p class="text-gray-500 text-sm">เลือกกิจกรรมที่คุณสนใจและกดลงทะเบียนเพื่อเข้าร่วม</p>
        </div>

        <?php if ($success): ?>
            <div class="bg-green-50 border border-green-300 text-green-700 rounded-lg px-4 py-3 mb-4 text-sm">✅ <?= $success ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="bg-red-50 border border-red-300 text-red-700 rounded-lg px-4 py-3 mb-4 text-sm">⚠️ <?= $error ?></div>
        <?php endif; ?>

        <!-- Search form -->
        <form method="GET" action="/join" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">ชื่อกิจกรรม</label>
                    <input type="text" name="search" value="<?= $search ?>" placeholder="ค้นหาชื่อกิจกรรม..."
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">วันเริ่มต้น</label>
                    <input type="date" name="date_start" value="<?= $date_start ?>"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">วันสิ้นสุด</label>
                    <input type="date" name="date_end" value="<?= $date_end ?>"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
            </div>
            <div class="flex gap-2 mt-4">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-5 py-2 rounded-lg transition">
                    🔍 ค้นหา
                </button>
                <a href="/join" class="bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium px-5 py-2 rounded-lg transition">
                    ล้างการค้นหา
                </a>
            </div>
        </form>

        <?php if (empty($events)): ?>
            <div class="text-center py-16 text-gray-400">
                <p class="text-4xl mb-3">🔍</p>
                <p>ไม่พบกิจกรรมที่ตรงกับการค้นหา</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($events as $event): ?>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">

                        <div class="relative h-40 bg-gray-200 overflow-hidden group">
                            <?php
                            $imgs   = $event['images'] ?? [];
                            $sid    = 'js_' . $event['id'];
                            $total  = count($imgs);
                            ?>
                            <?php if ($total === 0): ?>
                                <div class="w-full h-full flex items-center justify-center text-gray-400 text-4xl">🎉</div>
                            <?php else: ?>
                                <div id="<?= $sid ?>" class="flex h-full transition-transform duration-300 ease-in-out">
                                    <?php foreach ($imgs as $path): ?>
                                        <div class="min-w-full h-full flex-shrink-0">
                                            <img src="/<?= htmlspecialchars($path) ?>" class="w-full h-full object-cover" alt="">
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php if ($total > 1): ?>
                                    <button onclick="slideImg('<?= $sid ?>','prev',<?= $total ?>)" class="absolute left-1 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/60 text-white rounded-full w-7 h-7 flex items-center justify-center opacity-0 group-hover:opacity-100 transition text-sm">‹</button>
                                    <button onclick="slideImg('<?= $sid ?>','next',<?= $total ?>)" class="absolute right-1 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/60 text-white rounded-full w-7 h-7 flex items-center justify-center opacity-0 group-hover:opacity-100 transition text-sm">›</button>
                                    <div id="<?= $sid ?>_dots" class="absolute bottom-1 left-0 right-0 flex justify-center gap-1">
                                        <?php for ($d = 0; $d < $total; $d++): ?>
                                            <span class="w-1.5 h-1.5 rounded-full transition-all <?= $d === 0 ? 'bg-white' : 'bg-white/50' ?>"></span>
                                        <?php endfor; ?>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>

                        <div class="p-4">
                            <h2 class="font-bold text-gray-800 mb-1 truncate"><?= htmlspecialchars($event['title']) ?></h2>
                            <p class="text-xs text-gray-500 mb-1">📅 <?= $event['start_date'] ?></p>
                            <p class="text-xs text-gray-500 mb-1">📍 <?= htmlspecialchars($event['location']) ?></p>
                            <p class="text-xs text-gray-500 mb-3">👥 รับสูงสุด <?= $event['max_participants'] ?> คน</p>

                            <div class="mt-4">
                                <?php if (!empty($event['is_owner'])): ?>
                                    <!-- เจ้าของกิจกรรม -->
                                    <div class="w-full text-center py-2 rounded-lg text-sm font-bold bg-indigo-50 text-indigo-600 border border-indigo-200">
                                        🏆 กิจกรรมของคุณ
                                    </div>
                                    <a href="/registrations?event_id=<?= $event['id'] ?>"
                                        class="block text-center mt-2 text-xs text-indigo-600 hover:underline">
                                        จัดการผู้สมัคร →
                                    </a>
                                <?php elseif ($event['reg_status'] === 'pending'): ?>
                                    <div class="w-full text-center py-2 rounded-lg text-sm font-bold bg-yellow-100 text-yellow-700">
                                        ⏳ รออนุมัติ
                                    </div>
                                <?php elseif ($event['reg_status'] === 'approved'): ?>
                                    <div class="w-full text-center py-2 rounded-lg text-sm font-bold bg-green-100 text-green-700">
                                        ✅ อนุมัติแล้ว
                                    </div>
                                    <a href="/otp?event_id=<?= $event['id'] ?>"
                                        class="block text-center mt-2 text-xs text-indigo-600 hover:underline">
                                        ดูรหัส OTP สำหรับเช็คชื่อ
                                    </a>
                                <?php elseif ($event['reg_status'] === 'rejected'): ?>
                                    <div class="w-full text-center py-2 rounded-lg text-sm font-bold bg-red-100 text-red-700">
                                        ❌ ปฏิเสธการเข้าร่วม
                                    </div>
                                <?php else: ?>
                                    <form action="/join" method="POST">
                                        <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
                                        <button type="submit"
                                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold py-2 rounded-lg transition">
                                            ลงทะเบียนเข้าร่วม
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>

    <script src="/js/event.js"></script>
    <?php include TEMPLATES_DIR . '/footer.php' ?>
</body>

</html>