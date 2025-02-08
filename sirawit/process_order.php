<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include "connect.php";

// เปิดการแสดง error
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<script>alert('ไม่พบสินค้าในตะกร้า'); window.location.href='user_page.php';</script>";
    exit();
}



if (!isset($_SESSION['UserID'])) {
    echo "<script>alert('กรุณาเข้าสู่ระบบก่อนทำการสั่งซื้อ'); window.location.href='login.php';</script>";
    exit();
}

// รับข้อมูลจากฟอร์ม
$phone = mysqli_real_escape_string($conn, $_POST['phone']);
$user_id = (int) $_SESSION['UserID'];
$total_price = 0; // ตัวแปรสำหรับเก็บราคารวม
try {
    // เริ่มต้น transaction
    mysqli_begin_transaction($conn);

    $total_price = 0;
    $stock_check_failed = false;
    $out_of_stock_items = [];

    foreach ($_SESSION['cart'] as $variant_id => $item) {
        // ตรวจสอบสต็อกสินค้า
        $check_stock = "SELECT stock_quantity FROM tb_variants WHERE variant_id = ?";
        $stmt = mysqli_prepare($conn, $check_stock);
        mysqli_stmt_bind_param($stmt, "i", $variant_id);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("SQL Error: " . mysqli_error($conn));
        }
        mysqli_stmt_store_result($stmt);
        mysqli_stmt_bind_result($stmt, $check_stock);
        mysqli_stmt_fetch($stmt);

        if ($item['quantity'] > $check_stock) {
            $stock_check_failed = true;
            $out_of_stock_items[] = $item['name'];
        }

        $total_price += $item['price'] * $item['quantity'];
        mysqli_stmt_close($stmt);
    }

    if ($stock_check_failed) {
        throw new Exception("สินค้าไม่พอจำหน่าย: " . implode(", ", $out_of_stock_items));
    }

    // สร้างคำสั่งซื้อใหม่
    $order_sql = "INSERT INTO tb_orders (user_id, order_date, total_amount, phone, status) 
                  VALUES (?, NOW(), ?, ?, 'รอการชำระเงิน')";
    $stmt = mysqli_prepare($conn, $order_sql);
    mysqli_stmt_bind_param($stmt, "ids", $user_id, $total_price, $phone);
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("SQL Error: " . mysqli_error($conn));
    }
    $order_id = mysqli_insert_id($conn);
    mysqli_stmt_close($stmt);

    // เพิ่มรายการสินค้าในคำสั่งซื้อ
    $item_sql = "INSERT INTO tb_orderdetails (order_id, variant_id, quantity, price) VALUES (?, ?, ?, ?)";
    $stmt_item = mysqli_prepare($conn, $item_sql);
    foreach ($_SESSION['cart'] as $variant_id => $item) {
        mysqli_stmt_bind_param($stmt_item, "iiid", $order_id, $variant_id, $item['quantity'], $item['price']);
        if (!mysqli_stmt_execute($stmt_item)) {
            throw new Exception("SQL Error: " . mysqli_error($conn));
        }
    }
    mysqli_stmt_close($stmt_item);

    // ยืนยัน transaction
    mysqli_commit($conn);

    // ล้างตะกร้าสินค้า
    unset($_SESSION['cart']);

    // แสดงข้อความสำเร็จ
    echo "<script        alert('สั่งซื้อสินค้าสำเร็จ! เลขที่คำสั่งซื้อของคุณคือ: " . $order_id . "');
        window.location.href='order_history.php';
    </script>";
} catch (Exception $e) {
    // หากเกิดข้อผิดพลาด ให้ rollback การทำงานทั้งหมด
    mysqli_rollback($conn);
    error_log("Order Error: " . $e->getMessage());
    echo "<script>
        alert('เกิดข้อผิดพลาดในการสั่งซื้อ: " . htmlspecialchars($e->getMessage()) . "');
        window.location.href='cartpage.php';
    </script>";
}
?>