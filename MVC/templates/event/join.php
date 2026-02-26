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

    <?php if (!empty($success)): ?>
        <div class="bg-green-50 border border-green-300 text-green-700 rounded-lg px-4 py-3 mb-4 text-sm">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">ค้นหาและเข้าร่วมกิจกรรม</h1>
        <p class="text-gray-500 text-sm">เลือกกิจกรรมที่คุณสนใจและกดลงทะเบียนเพื่อเข้าร่วม</p>
    </div>

    <?php if (empty($events)): ?>
        <div class="text-center py-16 text-gray-400">
            <p class="text-4xl mb-3">🔍</p>
            <p>ไม่พบกิจกรรมที่เปิดรับลงทะเบียนในขณะนี้</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($events as $event): ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">

                <div class="h-40 bg-gray-200">
                    <?php if (!empty($event['cover'])): ?>
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

                    <div class="mt-4">
                        <?php if (isset($event['reg_status'])): ?>
                            <?php 
                                $statusClasses = [
                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                    'approved' => 'bg-green-100 text-green-700',
                                    'rejected' => 'bg-red-100 text-red-700'
                                ];
                                $statusLabels = [
                                    'pending' => '⏳ รออนุมัติ',
                                    'approved' => '✅ อนุมัติแล้ว',
                                    'rejected' => '❌ ปฏิเสธการเข้าร่วม'
                                ];
                                $currentStatus = $event['reg_status'];
                            ?>
                            <div class="w-full text-center py-2 rounded-lg text-sm font-bold <?= $statusClasses[$currentStatus] ?>">
                                <?= $statusLabels[$currentStatus] ?>
                            </div>
                            
                            <?php if ($currentStatus === 'approved'): ?>
                                <a href="/otp?event_id=<?= $event['id'] ?>" 
                                   class="block text-center mt-2 text-xs text-indigo-600 hover:underline">
                                   ดูรหัส OTP สำหรับเช็คชื่อ
                                </a>
                            <?php endif; ?>

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

<?php include TEMPLATES_DIR . '/footer.php' ?>
</body>
</html>