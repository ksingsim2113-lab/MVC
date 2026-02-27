<?php
/**
 * @var array  $event
 * @var array  $statusCount
 * @var array  $genderCount
 * @var array  $ageGroups
 * @var int    $checkedIn
 * @var int    $notCheckedIn
 * @var int    $total
 */
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>สถิติ – <?= htmlspecialchars($event['title']) ?></title>
</head>
<body class="bg-gray-50 min-h-screen">

<?php include TEMPLATES_DIR . '/header.php' ?>

<div class="max-w-4xl mx-auto px-4 py-8">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">สถิติกิจกรรม</h1>
            <p class="text-sm text-gray-500 mt-1"><?= htmlspecialchars($event['title']) ?></p>
        </div>
        <a href="/participants?event_id=<?= $event['id'] ?>" class="text-sm text-indigo-600 hover:underline">← กลับ</a>
    </div>

    <?php $approvedTotal = $statusCount['approved']; ?>

    <!-- สถิติ 1: สถานะการลงทะเบียน -->
    <h2 class="font-semibold text-gray-700 mb-3">สถานะการลงทะเบียน (ทั้งหมด <?= $total ?> คน)</h2>
    <div class="grid grid-cols-3 gap-4 mb-8">
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 text-center">
            <p class="text-3xl font-bold text-yellow-600"><?= $statusCount['pending'] ?></p>
            <p class="text-sm text-yellow-600 mt-1">รอการอนุมัติ</p>
        </div>
        <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-center">
            <p class="text-3xl font-bold text-green-600"><?= $statusCount['approved'] ?></p>
            <p class="text-sm text-green-600 mt-1">อนุมัติแล้ว / <?= $event['max_participants'] ?> คน</p>
        </div>
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-center">
            <p class="text-3xl font-bold text-red-600"><?= $statusCount['rejected'] ?></p>
            <p class="text-sm text-red-600 mt-1">ปฏิเสธแล้ว</p>
        </div>
    </div>

    <!-- สถิติ 2: เพศ -->
    <h2 class="font-semibold text-gray-700 mb-3">สัดส่วนเพศ (เฉพาะผู้อนุมัติ)</h2>
    <div class="grid grid-cols-3 gap-4 mb-8">
        <?php
        $genderData = [
            'male'   => ['label' => 'ชาย',   'bg' => 'bg-blue-50',  'border' => 'border-blue-200',  'text' => 'text-blue-600'],
            'female' => ['label' => 'หญิง',  'bg' => 'bg-pink-50',  'border' => 'border-pink-200',  'text' => 'text-pink-600'],
            'other'  => ['label' => 'อื่นๆ', 'bg' => 'bg-gray-50',  'border' => 'border-gray-200',  'text' => 'text-gray-600'],
        ];
        ?>
        <?php foreach ($genderData as $key => $g): ?>
        <div class="<?= $g['bg'] ?> border <?= $g['border'] ?> rounded-xl p-4 text-center">
            <p class="text-3xl font-bold <?= $g['text'] ?>"><?= $genderCount[$key] ?></p>
            <p class="text-sm <?= $g['text'] ?> mt-1"><?= $g['label'] ?></p>
            <?php if ($approvedTotal > 0): ?>
                <p class="text-xs <?= $g['text'] ?> opacity-60 mt-1"><?= round($genderCount[$key] / $approvedTotal * 100) ?>%</p>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- สถิติ 3: Check-in -->
    <h2 class="font-semibold text-gray-700 mb-3">การ Check-in (เฉพาะผู้อนุมัติ)</h2>
    <div class="grid grid-cols-2 gap-4 mb-8">
        <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-4 text-center">
            <p class="text-3xl font-bold text-indigo-600"><?= $checkedIn ?></p>
            <p class="text-sm text-indigo-600 mt-1">Check-in แล้ว</p>
            <?php if ($approvedTotal > 0): ?>
                <p class="text-xs text-indigo-400 mt-1"><?= round($checkedIn / $approvedTotal * 100) ?>%</p>
            <?php endif; ?>
        </div>
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-center">
            <p class="text-3xl font-bold text-gray-500"><?= $notCheckedIn ?></p>
            <p class="text-sm text-gray-500 mt-1">ยังไม่ได้ Check-in</p>
            <?php if ($approvedTotal > 0): ?>
                <p class="text-xs text-gray-400 mt-1"><?= round($notCheckedIn / $approvedTotal * 100) ?>%</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- สถิติ 4: ช่วงอายุ -->
    <h2 class="font-semibold text-gray-700 mb-3">ช่วงอายุ (เฉพาะผู้อนุมัติ)</h2>
    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
        <?php foreach ($ageGroups as $range => $count): ?>
        <?php $pct = $approvedTotal > 0 ? round($count / $approvedTotal * 100) : 0; ?>
        <div class="flex items-center px-4 py-3 border-b border-gray-50 last:border-0">
            <span class="w-16 text-sm text-gray-600"><?= $range ?></span>
            <div class="flex-1 mx-4">
                <div class="bg-gray-100 rounded-full h-3">
                    <div class="bg-indigo-400 h-3 rounded-full" style="width: <?= $pct ?>%"></div>
                </div>
            </div>
            <span class="text-sm font-semibold text-gray-700 w-8 text-right"><?= $count ?></span>
            <span class="text-xs text-gray-400 w-10 text-right"><?= $pct ?>%</span>
        </div>
        <?php endforeach; ?>
    </div>

</div>

<?php include TEMPLATES_DIR . '/footer.php' ?>
</body>
</html>