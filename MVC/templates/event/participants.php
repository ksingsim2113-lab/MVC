<?php
/**
 * @var array $event
 * @var array $participants
 */
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>ผู้เข้าร่วม – <?= $event['title'] ?></title>
</head>

<body class="bg-gray-50 min-h-screen">

<div class="max-w-4xl mx-auto px-4 py-8">

    <a href="/registrations?event_id=<?= $event['id'] ?>"
       class="inline-block mb-6 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded text-sm">
        ← กลับหน้าจัดการผู้สมัคร
    </a>

    <div class="bg-white rounded-2xl shadow p-6 mb-6">
        <h1 class="text-2xl font-bold">
            ผู้เข้าร่วมกิจกรรม
        </h1>
        <p class="text-gray-500 text-sm mt-1">
            <?= $event['title'] ?>
        </p>
    </div>

    <?php if (empty($participants)): ?>
        <div class="bg-white rounded-xl p-6 text-center text-gray-400 border">
            ยังไม่มีผู้เข้าร่วมที่ได้รับการอนุมัติ
        </div>
    <?php else: ?>
       <div class="bg-white rounded-xl shadow border overflow-hidden">
    <table class="w-full text-sm table-fixed">
        <thead class="bg-gray-100 text-gray-600 text-xs uppercase">
        <tr>
            <th class="p-3 text-center w-12">#</th>
            <th class="p-3 text-left w-1/4">ชื่อ</th>
            <th class="p-3 text-left w-1/3">อีเมล</th>
            <th class="p-3 text-left w-1/4">เบอร์โทร</th>
        </tr>
        </thead>

        <tbody>
        <?php foreach ($participants as $i => $p): ?>
            <tr class="border-t hover:bg-gray-50">
                <td class="p-3 text-center"><?= $i + 1 ?></td>
                <td class="p-3 text-left"><?= $p['full_name'] ?></td>
                <td class="p-3 text-left"><?= $p['email'] ?></td>
                <td class="p-3 text-left"><?= $p['phone'] ?? '-' ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
    <?php endif; ?>

</div>

</body>
</html>