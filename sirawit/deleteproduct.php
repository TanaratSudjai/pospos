<?php 
include "connect.php";

$ID = $_POST['ID'];  // รับ ID จากฟอร์ม

// ลบข้อมูลใน tb_variants ก่อน
$delete_variants_sql = "DELETE FROM tb_variants WHERE product_id = '$ID'";
mysqli_query($conn, $delete_variants_sql);

// ลบข้อมูลใน tb_products
$delete_product_sql = "DELETE FROM tb_products WHERE product_id = '$ID'";
if (mysqli_query($conn, $delete_product_sql)) {
    // รีเซ็ตค่า AUTO_INCREMENT ให้เริ่มต้นใหม่หลังจากการลบ
    $reset_auto_increment_sql = "ALTER TABLE tb_products AUTO_INCREMENT = 1";
    mysqli_query($conn, $reset_auto_increment_sql);

    echo "<script>alert('สินค้าถูกลบเรียบร้อยแล้ว'); window.location='showproduct.php';</script>";
} else {
    echo "Error deleting product: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
