<html>

<head></head>

<body>
    <!-- Header  -->
    <nav>
        <?php include 'header.php' ?>
    </nav>
    <!-- Header  -->

    <!-- ส่วนแสดงผลหลักของหน้า -->
    <main>
        <form method="POST">
            <label>อีเมล</label><br>
            <input type="email" name="email" /><br>
            <label>รหัสผ่าน</label><br>
            <input type="password" name="password" /><br>
            <button type="submit">เข้าสู่ระบบ</button>
        </form>
    </main>
    <!-- ส่วนแสดงผลหลักของหน้า -->

    <!-- Footer  -->
    <?php include 'footer.php' ?>
    <!-- Footer  -->
</body>

</html>