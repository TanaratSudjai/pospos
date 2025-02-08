<?php
session_start();
include "connect.php";
include "navbaruse.php";

// ตรวจสอบว่ามีสินค้าหรือไม่ในตะกร้า
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<script>alert('ตะกร้าสินค้าว่างเปล่า'); window.location.href='user_page.php';</script>";
    exit();
}

// debug cart array
$pre_cart = $_SESSION["cart"];



$total_price = 0;

// คำนวณรวมราคาสินค้าในตะกร้า
foreach ($_SESSION['cart'] as $variant_id => $item) {
    $total_price += $item['price'] * $item['quantity'];
}


// เก็บข้อมูลใน $_SESSION เพื่อใช้ใน checkout.php
$_SESSION['total_amount'] = $total_price;
$_SESSION['slip_path'] = isset($_SESSION['slip_path']) ? $_SESSION['slip_path'] : null;

?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.bundle.min.js"></script>
    <title>หน้าตะกร้าสินค้า</title>
    <style>
        .variant-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
        }

        .btn-group {
            display: flex;
            gap: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="text-center mt-4">หน้าตะกร้าสินค้า </h2>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-center">
                        รายการสินค้าที่คุณเลือก <?php
                        print_r($item);
                        ?>
                    </div>
                    <div class="card-body">
                        <form action="update_cart.php" method="POST">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ชื่อสินค้า</th>
                                        <th>สี</th>
                                        <th>ไซส์</th>
                                        <th>จำนวน</th>
                                        <th>ราคา</th>
                                        <th>รวม</th>
                                        <th>ลบ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($_SESSION['cart'] as $variant_id => $item):
                                        $subtotal = $item['price'] * $item['quantity'];
                                        ?>
                                        <tr>
                                            <td><?= htmlspecialchars($item['name']); ?></td>
                                            <td>
                                                <?php if (!empty($item['variant_pic'])): ?>
                                                    <img src="img/<?= htmlspecialchars($item['variant_pic']); ?>"
                                                        alt="<?= htmlspecialchars($item['color']); ?>" class="variant-img">
                                                <?php else: ?>
                                                    ไม่มีภาพ
                                                <?php endif; ?>
                                                <?= htmlspecialchars($item['color']); ?>
                                            </td>
                                            <td><?= htmlspecialchars($item['size']); ?></td>
                                            <td>
                                                <input type="number" name="quantity[<?= $variant_id ?>]"
                                                    value="<?= $item['quantity']; ?>" min="1" max=""
                                                    class="form-control w-50">
                                            </td>
                                            <td><?= number_format($item['price'], 2); ?> ฿</td>
                                            <td><?= number_format($subtotal, 2); ?> ฿</td>
                                            <td><a href="remove_from_cart.php?variant_id=<?= $variant_id ?>"
                                                    class="btn btn-danger btn-sm">ลบ</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>

                            </table>
                            <div class="btn-group w-100 d-flex gap-3">
                                <button type="submit" class="btn btn-primary">อัปเดตตะกร้า</button>
                                <a href="confirm_order.php" class="btn btn-success">ดำเนินการสั่งซื้อ</a>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row back-btn">
                    <div class="col-md-12">
                        <a href="user_page.php" class="btn btn-secondary w-100">ย้อนกลับ</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>