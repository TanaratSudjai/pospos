<?php
session_start();
include "connect.php";

if (!isset($_GET['variant_id'])) {
    echo "<script>alert('กรุณาเลือกสินค้าก่อน'); window.history.back();</script>";
    exit();
}

$variant_id = intval($_GET['variant_id']);

// ดึงข้อมูลสินค้าจากฐานข้อมูล
$sql = "SELECT p.name, v.color, v.size, p.price, v.variant_pic, v.variant_id 
        FROM tb_products p
        JOIN tb_variants v ON p.product_id = v.product_id
        WHERE v.variant_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $variant_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "<script>alert('ไม่พบข้อมูลสินค้า'); window.history.back();</script>";
    exit();
}

// ตรวจสอบว่ามีสินค้าในตะกร้าแล้วหรือยัง
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// ตรวจสอบว่ามีสินค้านี้ในตะกร้าหรือยัง
$found = false;
foreach ($_SESSION['cart'] as &$item) {
    if ($item['variant_id'] == $variant_id) {
        $item['quantity']++;
        $found = true;
        break;
    }
}
unset($item);

// หากยังไม่มีในตะกร้าให้เพิ่มรายการใหม่
if (!$found) {
    $_SESSION['cart'][] = [
        'variant_id' => $product['variant_id'],
        'name' => $product['name'],
        'color' => $product['color'],
        'size' => $product['size'],
        'price' => $product['price'],
        'variant_pic' => $product['variant_pic'],
        'quantity' => 1
    ];
}

echo "<script>alert('เพิ่มสินค้าในตะกร้าเรียบร้อย'); window.location.href='cartpage.php';</script>";
?>
