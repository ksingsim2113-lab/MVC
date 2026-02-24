<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>ค้นหากิจกรรม</title>
</head>
<body class="bg-gray-50">

    <?php include TEMPLATES_DIR . '/header.php'; ?>
    <div class="container mx-auto px-4 py-8">
        <div class="mb-10 text-center">
            <h1 class="text-3xl font-bold text-gray-800 mb-4">ค้นหากิจกรรมที่น่าสนใจ</h1>
            <div class="max-w-xl mx-auto">
                <input type="text" placeholder="ค้นหาชื่อกิจกรรม..." 
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none shadow-sm">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 border border-gray-100">
                <div class="relative h-48 bg-gray-200">
                    <img src="https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?auto=format&fit=crop&w=800" 
                        alt="Event Image" class="w-full h-full object-cover">
                    <div class="absolute top-2 right-2 bg-blue-600 text-white text-xs px-2 py-1 rounded-full">
                        1/3 รูปภาพ
                    </div>
                </div>

                <div class="p-5">
                    <div class="flex justify-between items-start mb-2">
                        <h2 class="text-xl font-bold text-gray-900 leading-tight">คอนเสิร์ตการกุศล C4C 2024</h2>
                    </div>
                    
                    <div class="space-y-2 mb-6">
                        <p class="text-sm text-gray-600 flex items-center">
                            <span class="mr-2">📅</span> 25 มีนาคม 2568 | 18:00 น.
                        </p>
                        <p class="text-sm text-gray-600 flex items-center">
                            <span class="mr-2">📍</span> หอประชุมใหญ่ มหาวิทยาลัย...
                        </p>
                        <div class="mt-3">
                            <div class="flex justify-between text-xs mb-1 text-gray-500">
                                <span>จำนวนที่รับ</span>
                                <span>20 / 50 คน</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-1.5">
                                <div class="bg-blue-500 h-1.5 rounded-full" style="width: 40%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <button class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded-lg transition">
                            รายละเอียด
                        </button>
                        <button class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                            ลงทะเบียน
                        </button>
                    </div>
                </div>
            </div>
            </div>
    </div>

</body>
</html>