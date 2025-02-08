<?php
// ตรวจสอบสถานะของ session และเริ่มต้น session ถ้ายังไม่ได้เริ่ม
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // เริ่ม session หากยังไม่มี session ที่ใช้งานอยู่
}

include "connect.php"; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่า UserID ถูกกำหนดใน session หรือไม่
if (isset($_SESSION["UserID"])) {
    $userID = $_SESSION["UserID"]; // รหัสผู้ใช้ที่ล็อกอิน
    $sql = "SELECT firstname FROM tb_users WHERE user_id = '$userID'"; // ดึงชื่อจริงจากฐานข้อมูล
    $result = mysqli_query($conn, $sql);
    
    if ($result) {
        $row = mysqli_fetch_assoc($result); // ดึงข้อมูลผู้ใช้
        $userName = $row['firstname']; // ใช้ชื่อจริงแทน Username
    } else {
        $userName = "ท่านลูกค้า"; // ถ้าหากไม่สามารถดึงข้อมูลได้
    }
} else {
    $userName = "ท่านลูกค้า"; // ถ้าไม่มีการเข้าสู่ระบบ
}

// ตรวจสอบจำนวนสินค้าที่อยู่ในตะกร้า
$cartCount = 0;
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $cartCount = count($_SESSION['cart']);
}
?>
<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #f8f9fa;">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <div class="container-fluid">
        <!-- Brand -->
        <a href="user_page.php" class="navbar-brand d-flex align-items-center text-dark">
            <img src="img/soxis logo.png" alt="SOXIS Logo" style="height: 50px; width: 50px;">
            <span class="ms-2 fw-bold">SOXIS</span>
        </a>
        <!-- Toggler Button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Menu -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a href="cartpage.php" class="nav-link text-dark fw-semibold" style="position: relative;">
                        <i class="fas fa-shopping-cart"></i> ตระกร้าสินค้า
                        <?php if ($cartCount > 0): ?>
                            <span class="badge bg-danger rounded-circle" style="position: absolute; top: -10px; right: -10px; font-size: 12px; padding: 5px 8px;">
                                <?php echo $cartCount; ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </li>
                <!-- สถานะผู้ใช้ -->
                <li class="nav-item">
                    <span class="nav-link text-dark fw-semibold" style="border: 1px solid #ccc; padding: 5px 15px; border-radius: 25px; background-color: #f8f9fa;">
                        สวัสดี, <?php echo $userName; ?>
                    </span>
                </li>
                <li class="nav-item">
                    <a href="logout.php" class="nav-link text-dark fw-semibold">ออกจากระบบ</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
