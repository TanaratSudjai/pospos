<?php
session_start();
include "connect.php";
include "navbaruse.php";

// เปิดการแสดง error
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['UserID'])) {
    echo "<script>alert('กรุณาเข้าสู่ระบบก่อนทำการสั่งซื้อ'); window.location.href='login.php';</script>";
    exit();
}

// ตรวจสอบว่ามีสินค้าในตะกร้าหรือไม่
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<script>alert('ไม่พบสินค้าในตะกร้า'); window.location.href='user_page.php';</script>";
    exit();
}

$total_price = 0;
foreach ($_SESSION['cart'] as $item) {
    $total_price += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ยืนยันการสั่งซื้อ</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.bundle.min.js"></script>
    <style>
        .order-summary {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .product-list {
            max-height: 400px;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center mb-4">ยืนยันการสั่งซื้อ</h2>
        
        <div class="row">
            <div class="col-md-8">
                <div class="order-summary">
                    <h4>รายการสินค้า</h4>
                    <div class="product-list">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>สินค้า</th>
                                    <th>สี</th>
                                    <th>ไซส์</th>
                                    <th>จำนวน</th>
                                    <th>ราคาต่อชิ้น</th>
                                    <th>รวม</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($_SESSION['cart'] as $variant_id => $item): 
                                    $subtotal = $item['price'] * $item['quantity'];
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['name']) ?></td>
                                    <td>
                                        <?php if (!empty($item['variant_pic'])): ?>
                                            <img src="img/<?= htmlspecialchars($item['variant_pic']) ?>" alt="<?= htmlspecialchars($item['color']) ?>" style="width: 30px; height: 30px; object-fit: cover;">
                                        <?php endif; ?>
                                        <?= htmlspecialchars($item['color']) ?>
                                    </td>
                                    <td><?= htmlspecialchars($item['size']) ?></td>
                                    <td><?= $item['quantity'] ?></td>
                                    <td><?= number_format($item['price'], 2) ?> ฿</td>
                                    <td><?= number_format($subtotal, 2) ?> ฿</td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="order-summary">
                    <h4>สรุปคำสั่งซื้อ</h4>
                    <div class="mt-3">
                        <p><strong>ราคารวมทั้งหมด:</strong> <?= number_format($total_price, 2) ?> ฿</p>
                    </div>
                    
                    <form action="process_order.php" method="POST" class="mt-4">
                        <div class="mb-3">
                            <label for="address" class="form-label">ที่อยู่จัดส่ง</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">เบอร์โทรศัพท์</label>
                            <input type="tel" class="form-control" id="phone" name="phone" required pattern="[0-9]{10}">
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">ยืนยันการสั่งซื้อ</button>
                            <a href="cartpage.php" class="btn btn-outline-secondary">กลับไปหน้าตะกร้าสินค้า</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
