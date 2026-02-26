<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>รหัส OTP ของคุณ</title>
</head>
<body class="bg-indigo-600 min-h-screen flex items-center justify-center px-4">

    <div class="max-w-xs w-full bg-white rounded-3xl shadow-2xl p-8 text-center relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-2 bg-yellow-400"></div>
        
        <h1 class="text-xl font-bold text-gray-800 mt-4 mb-2">รหัสสำหรับเข้างาน</h1>
        <p class="text-gray-500 text-xs mb-8">กรุณาแสดงรหัสนี้ต่อผู้จัดงาน</p>

        <div class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl py-8 mb-6">
            <span class="text-5xl font-mono font-black text-indigo-600 tracking-widest">
                <?= htmlspecialchars($otpCode) ?>
            </span>
        </div>

        <div class="flex items-center justify-center text-red-500 mb-8">
            <span class="animate-pulse mr-2">🕒</span>
            <p class="text-[10px] font-semibold uppercase tracking-wider">รหัสหมดอายุใน 30 นาที</p>
        </div>

        <a href="/event" class="text-gray-400 hover:text-gray-600 text-sm flex items-center justify-center transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="15 19l-7-7 7-7"></path></svg>
            กลับหน้ากิจกรรม
        </a>
    </div>

</body>
</html>