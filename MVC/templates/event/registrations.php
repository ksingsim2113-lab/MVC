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

    <!-- Back link + Participants button -->
    <div class="flex items-center justify-between mb-4">
        <a href="/event" class="text-sm text-indigo-600 hover:underline">
            ← กลับหน้ากิจกรรมของฉัน
        </a>

        <a href="/participants?event_id=<?= $event['id'] ?>"
           class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
            👥 ดูผู้เข้าร่วม
        </a>
    </div>

    <!-- Event header card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-1">จัดการผู้สมัคร</h1>
        <p class="text-gray-500 text-sm">📌 <?= $event['title'] ?></p>
        <p class="text-gray-400 text-xs mt-1">
            📅 <?= $event['start_date'] ?> &nbsp;|&nbsp;
            📍 <?= $event['location'] ?> &nbsp;|&nbsp;
            👥 อนุมัติแล้ว <span class="font-semibold text-indigo-600"><?= $approvedCount ?></span> / <?= $event['max_participants'] ?> คน
        </p>
    </div>

    <?php if ($success): ?>
        <div class="bg-green-50 border border-green-300 text-green-700 rounded-lg px-4 py-3 mb-4 text-sm">
            ✅ <?= $success ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="bg-red-50 border border-red-300 text-red-700 rounded-lg px-4 py-3 mb-4 text-sm">
            ⚠️ <?= $error ?>
        </div>
    <?php endif; ?>

    <!-- Filter tabs -->
    <div class="flex gap-2 mb-4">
        <?php foreach ($filterTabs as $tab): ?>
            <a href="/registrations?event_id=<?= $event['id'] ?>&filter=<?= $tab['filter'] ?>"
               class="px-4 py-1.5 rounded-full text-sm font-medium transition
               <?= $tab['active'] ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>">
                <?= $tab['label'] ?>
            </a>
        <?php endforeach; ?>
    </div>

    <?php if (empty($participants)): ?>
        <div class="text-center py-16 text-gray-400 bg-white rounded-xl border-2 border-dashed border-gray-200">
            <p class="text-4xl mb-3">📭</p>
            <p>ไม่มีผู้สมัครในหมวดนี้</p>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3">#</th>
                        <th class="px-5 py-3">ชื่อ-นามสกุล</th>
                        <th class="px-5 py-3">อีเมล</th>
                        <th class="px-5 py-3">เพศ</th>
                        <th class="px-5 py-3">เบอร์โทร</th>
                        <th class="px-5 py-3">สถานะ</th>
                        <th class="px-5 py-3">เช็คอิน</th>
                        <th class="px-5 py-3 text-center">การดำเนินการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php foreach ($participants as $i => $p): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-4 text-gray-400"><?= $i + 1 ?></td>
                            <td class="px-5 py-4 font-medium text-gray-800"><?= $p['full_name'] ?></td>
                            <td class="px-5 py-4 text-gray-500"><?= $p['email'] ?></td>
                            <td class="px-5 py-4 text-gray-500"><?= $p['gender'] ?></td>
                            <td class="px-5 py-4 text-gray-500"><?= $p['phone'] ?></td>
                            <td class="px-5 py-4"><?= $p['status_badge'] ?></td>

                            <td class="px-5 py-4 text-center">
                                <?php if ($p['can_check_in']): ?>
                                    <button onclick="openCheckInModal(<?= $p['id'] ?>, '<?= $p['full_name'] ?>')"
                                        class="bg-green-600 hover:bg-green-700 text-white text-[11px] px-3 py-1 rounded transition">
                                        ✅ เช็คอิน
                                    </button>
                                <?php elseif ($p['is_checked_in']): ?>
                                    <span class="text-green-600 font-bold text-[11px]">✔️ เข้างานแล้ว</span>
                                <?php endif; ?>
                            </td>

                            <td class="px-5 py-4">
                                <div class="flex gap-2 justify-center">
                                    <?php if ($p['can_approve']): ?>
                                        <form method="POST" action="/registrations?event_id=<?= $event['id'] ?>">
                                            <input type="hidden" name="registration_id" value="<?= $p['id'] ?>">
                                            <input type="hidden" name="action" value="approved">
                                            <button type="submit"
                                                class="px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-xs font-semibold rounded-lg transition">
                                                ✓ อนุมัติ
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if ($p['can_reject']): ?>
                                        <form method="POST" action="/registrations?event_id=<?= $event['id'] ?>">
                                            <input type="hidden" name="registration_id" value="<?= $p['id'] ?>">
                                            <input type="hidden" name="action" value="rejected">
                                            <button type="submit"
                                                class="px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-600 text-xs font-semibold rounded-lg transition">
                                                ✗ ปฏิเสธ
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

</div><div id="checkInModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 w-80 shadow-2xl">
        <h3 class="font-bold text-lg mb-2 text-gray-800">เช็คชื่อเข้างาน</h3>
        <p id="modalName" class="text-sm text-gray-600 mb-4"></p>
        
        <form action="/check_in" method="POST">
            <input type="hidden" name="registration_id" id="modalRegId">
            <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
            
            <label class="block text-[10px] font-bold text-gray-400 mb-1 uppercase">รหัส OTP 6 หลัก</label>
            <input type="text" name="otp_code" maxlength="6" required 
                   class="w-full text-center text-2xl font-mono tracking-widest border-2 border-indigo-100 rounded-lg py-2 focus:border-indigo-500 outline-none"
                   placeholder="000000" autofocus>
            
            <div class="flex gap-2 mt-6">
                <button type="button" onclick="closeCheckInModal()" class="flex-1 py-2 text-sm text-gray-500 hover:bg-gray-100 rounded-lg transition">ยกเลิก</button>
                <button type="submit" class="flex-1 py-2 text-sm bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-700 transition">ยืนยัน</button>
            </div>
        </form>
    </div>
</div>

<script>
function openCheckInModal(id, name) {
    document.getElementById('modalRegId').value = id;
    document.getElementById('modalName').innerText = 'ผู้สมัคร: ' + name;
    document.getElementById('checkInModal').classList.remove('hidden');
}

function closeCheckInModal() {
    document.getElementById('checkInModal').classList.add('hidden');
}

// ปิด modal เมื่อคลิกพื้นหลัง (เท่ๆ)
window.onclick = function(event) {
    let modal = document.getElementById('checkInModal');
    if (event.target == modal) {
        closeCheckInModal();
    }
}
</script>

<?php include TEMPLATES_DIR . '/footer.php' ?>
</body>

</html>