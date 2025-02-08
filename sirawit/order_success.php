<?php
session_start();
include "connect.php";
include "navbaruse.php";

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['UserID'])) {
    echo "<script>alert('กรุณาล็อกอิน'); window.location.href='login.php';</script>";
    exit();
}

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<script>alert('ไม่มีสินค้าในตะกร้า'); window.location.href='user_page.php';</script>";
    exit();
}

$user_id = $_SESSION['UserID'];
$total_price = 0;
$order_date = date("Y-m-d H:i:s");

// คำนวณราคาสินค้ารวม
foreach ($_SESSION['cart'] as $item) {
    $total_price += $item['price'] * $item['quantity'];
}

// บันทึกข้อมูลคำสั่งซื้อใน tb_orders
$order_query = "INSERT INTO tb_orders (user_id, total_price, order_date) VALUES ('$user_id', '$total_price', '$order_date')";
if (mysqli_query($conn, $order_query)) {
    $order_id = mysqli_insert_id($conn); // ดึง order_id ล่าสุด

    // บันทึกรายการสินค้าใน tb_orderdetails
    foreach ($_SESSION['cart'] as $variant_id => $item) {
        $product_name = $item['name'];
        $color = $item['color'];
        $size = $item['size'];
        $quantity = $item['quantity'];
        $price = $item['price'];
        $subtotal = $price * $quantity;

        $detail_query = "INSERT INTO tb_orderdetails (order_id, variant_id, product_name, color, size, quantity, price, subtotal) 
                          VALUES ('$order_id', '$variant_id', '$product_name', '$color', '$size', '$quantity', '$price', '$subtotal')";
        mysqli_query($conn, $detail_query);
    }

    // ล้างตะกร้าสินค้า
    unset($_SESSION['cart']);

    echo "<script>alert('ขอบคุณสำหรับการสั่งซื้อ!'); window.location.href='order_success.php';</script>";
} else {
    echo "<script>alert('เกิดข้อผิดพลาดในการบันทึกข้อมูลคำสั่งซื้อ'); window.location.href='cartpage.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.bundle.min.js"></script>
    <title>การสั่งซื้อสำเร็จ</title>
    <style>
        .success-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }
        .success-icon {
            font-size: 80px;
            color: #28a745;
        }
        .btn-home {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-container">
            <div class="success-icon">✔️</div>
            <h1 class="mt-4">การสั่งซื้อสำเร็จ!</h1>
            <p>ขอบคุณสำหรับการสั่งซื้อของคุณ</p>
            <p>เราจะทำการจัดส่งสินค้าโดยเร็วที่สุด</p>
            <a href="user_page.php" class="btn btn-primary btn-home">กลับไปที่หน้าหลัก</a>
        </div>
    </div>
</body>
</html>
