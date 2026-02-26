<?php
/**
 * @var array  $event
 * @var array  $participants
 * @var array  $filterTabs
 * @var int    $approvedCount
 * @var string $success
 * @var string $error
 */
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>จัดการผู้สมัคร – <?= $event['title'] ?></title>
</head>

<body class="bg-gray-50 min-h-screen">

<?php include TEMPLATES_DIR . '/header.php' ?>

<div class="max-w-5xl mx-auto px-4 py-8">

    <!-- ปุ่มกลับ -->
    <a href="/event"
       class="inline-block mb-6 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
        ← กลับหน้ากิจกรรมของฉัน
    </a>

    <!-- Event header -->
    <div class="bg-white rounded-2xl shadow-sm border p-6 mb-6">
        <h1 class="text-2xl font-bold mb-2">จัดการผู้สมัคร</h1>
        <p class="text-sm text-gray-500"><?= $event['title'] ?></p>
        <p class="text-xs text-gray-400 mt-1">
            📅 <?= $event['start_date'] ?> |
            📍 <?= $event['location'] ?> |
            👥 <?= $approvedCount ?> / <?= $event['max_participants'] ?> คน
        </p>
    </div>

    <!-- Flash -->
    <?php if (!empty($success)): ?>
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4 text-sm">
            <?= $success ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 text-sm">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <!-- Filter -->
    <div class="flex gap-2 mb-4">
        <?php foreach ($filterTabs as $tab): ?>
            <a href="/registrations?event_id=<?= $event['id'] ?>&filter=<?= $tab['filter'] ?>"
               class="px-4 py-1.5 rounded-full text-sm font-medium
               <?= $tab['active'] ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-600' ?>">
                <?= $tab['label'] ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- ตาราง -->
    <?php if (empty($participants)): ?>
        <div class="text-center py-10 text-gray-400 bg-white rounded-xl border">
            ไม่มีผู้สมัคร
        </div>
    <?php else: ?>
        <div class="bg-white rounded-xl shadow border overflow-hidden">
            <table class="w-full text-sm table-fixed">
                <thead class="bg-gray-100 text-gray-600 text-xs uppercase">
                <tr>
                    <th class="p-3 text-center w-12">#</th>
                    <th class="p-3 text-left">ชื่อ</th>
                    <th class="p-3 text-left">อีเมล</th>
                    <th class="p-3 text-center w-32">สถานะ</th>
                    <th class="p-3 text-center w-48">จัดการ</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($participants as $i => $p): ?>
                    <tr class="border-t hover:bg-gray-50">
                        <td class="p-3 text-center"><?= $i + 1 ?></td>
                        <td class="p-3 text-left"><?= $p['full_name'] ?></td>
                        <td class="p-3 text-left"><?= $p['email'] ?></td>
                        <td class="p-3 text-center"><?= $p['status_badge'] ?></td>

                        <td class="p-3">
                            <div class="flex flex-col items-center gap-2">

                                <div class="flex gap-2">

                                    <?php if (!empty($p['can_approve'])): ?>
                                        <form method="POST" action="/registrations?event_id=<?= $event['id'] ?>">
                                            <input type="hidden" name="registration_id" value="<?= $p['id'] ?>">
                                            <input type="hidden" name="action" value="approved">
                                            <button type="submit"
                                                    class="bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded text-xs">
                                                อนุมัติ
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if (!empty($p['can_reject'])): ?>
                                        <form method="POST" action="/registrations?event_id=<?= $event['id'] ?>">
                                            <input type="hidden" name="registration_id" value="<?= $p['id'] ?>">
                                            <input type="hidden" name="action" value="rejected">
                                            <button type="submit"
                                                    class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">
                                                ปฏิเสธ
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                </div>

                                <a href="/participants?event_id=<?= $event['id'] ?>"
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-0.5 rounded text-xs">
                                    ผู้เข้าร่วม
                                </a>

                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

</div>

<?php include TEMPLATES_DIR . '/footer.php' ?>
</body>
</html>