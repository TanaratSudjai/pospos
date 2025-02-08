<?php
session_start();
include "connect.php";
include "navbaruse.php";

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['UserID'])) {
    echo "<script>alert('กรุณาล็อกอินก่อนทำการสั่งซื้อ'); window.location.href='login.php';</script>";
    exit();
}

// ตรวจสอบข้อมูลใน session
if (!isset($_SESSION['cart']) || empty($_SESSION['cart']) || !isset($_SESSION['total_amount']) || !isset($_SESSION['slip_path'])) {
    echo "<script>alert('ไม่มีข้อมูลการสั่งซื้อ'); window.location.href='cartpage.php';</script>";
    exit();
}

$user_id = $_SESSION['UserID'];
$total_amount = $_SESSION['total_amount'];
$order_date = date("Y-m-d H:i:s");
$status = 'pending';

// ตรวจสอบสต๊อกสินค้าก่อนบันทึกการสั่งซื้อ
foreach ($_SESSION['cart'] as $variant_id => $item) {
    $quantity = $item['quantity'];

    // ตรวจสอบจำนวนสินค้าคงเหลือในสต๊อก
    $variant_check_query = "SELECT stock_quantity FROM tb_variants WHERE variant_id = '$variant_id'";
    $result = mysqli_query($conn, $variant_check_query);

    if ($result && mysqli_num_rows($result) > 0) {
        $variant_data = mysqli_fetch_assoc($result);

        // ตรวจสอบว่าจำนวนในสต๊อกเพียงพอหรือไม่
        if ($variant_data['stock_quantity'] < $quantity) {
            echo "<script>alert('จำนวนสินค้าไม่เพียงพอในสต๊อกสำหรับสินค้า: {$item['name']}'); window.location.href='cartpage.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('สินค้าไม่พบในฐานข้อมูล'); window.location.href='cartpage.php';</script>";
        exit();
    }
}

// บันทึกข้อมูลการสั่งซื้อใน tb_orders
$insert_order_query = "INSERT INTO tb_orders (user_id, order_date, status, total_amount) VALUES ('$user_id', '$order_date', '$status', '$total_amount')";
mysqli_query($conn, $insert_order_query);
$order_id = mysqli_insert_id($conn);

// บันทึกข้อมูลรายละเอียดการสั่งซื้อใน tb_orderdetails
foreach ($_SESSION['cart'] as $variant_id => $item) {
    $quantity = $item['quantity'];
    $price = $item['price'];
    $subtotal = $price * $quantity;

    $insert_detail_query = "INSERT INTO tb_orderdetails (order_id, variant_id, quantity, price, subtotal) 
                            VALUES ('$order_id', '$variant_id', '$quantity', '$price', '$subtotal')";
    mysqli_query($conn, $insert_detail_query);

    // อัปเดตจำนวนสต๊อกสินค้า
    $new_stock_quantity = $variant_data['stock_quantity'] - $quantity;
    $update_stock_query = "UPDATE tb_variants SET stock_quantity = '$new_stock_quantity' WHERE variant_id = '$variant_id'";
    mysqli_query($conn, $update_stock_query);
}

// บันทึกข้อมูลการชำระเงินใน tb_payments
$slip_path = $_SESSION['slip_path'];
$payment_date = date("Y-m-d H:i:s");
$payment_status = 'pending';

$insert_payment_query = "INSERT INTO tb_payments (order_id, payment_date, amount, status, payment_pic) 
                         VALUES ('$order_id', '$payment_date', '$total_amount', '$payment_status', '$slip_path')";
mysqli_query($conn, $insert_payment_query);

// ล้างข้อมูลตะกร้าและ session หลังสั่งซื้อเสร็จ
unset($_SESSION['cart']);
unset($_SESSION['total_amount']);
unset($_SESSION['slip_path']);

// แสดงข้อความยืนยัน
echo "<script>alert('ขอบคุณสำหรับการสั่งซื้อ! รอการตรวจสอบการชำระเงินจากเจ้าหน้าที่'); window.location.href='user_page.php';</script>";
exit();
?>
