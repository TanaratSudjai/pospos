<?php
include "connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productname = $_POST["productname"];
    $productprice = $_POST["productprice"];
    $productdetails = $_POST["productdetails"];
    $category = $_POST["category"];

    // อัปโหลดรูปภาพสินค้า
    $image = $_FILES["uploadfile"]["name"];
    $target = "img/" . basename($image);
    move_uploaded_file($_FILES["uploadfile"]["tmp_name"], $target);

    // เพิ่มข้อมูลลงตาราง tb_products
    $sql = "INSERT INTO tb_products (name, price, description, category_id, product_pic) 
            VALUES ('$productname', '$productprice', '$productdetails', '$category', '$image')";
    
    if (mysqli_query($conn, $sql)) {
        $product_id = mysqli_insert_id($conn); // ดึง ID สินค้าที่เพิ่มล่าสุด

        // เพิ่มตัวเลือกสินค้า
        if (!empty($_POST["color"])) {
            foreach ($_POST["color"] as $index => $color) {
                $size = $_POST["size"][$index];
                $stock_quantity = $_POST["stock_quantity"][$index];

                // อัปโหลดรูปตัวเลือกสินค้า
                $variant_pic = $_FILES["variant_img"]["name"][$index]; // ใช้ variant_pic แทน variant_img
                $variant_target = "img/variants/" . basename($variant_pic);
                move_uploaded_file($_FILES["variant_img"]["tmp_name"][$index], $variant_target);

                // บันทึกตัวเลือกสินค้า
                $sql_variant = "INSERT INTO tb_variants (product_id, color, size, stock_quantity, variant_pic) 
                                VALUES ('$product_id', '$color', '$size', '$stock_quantity', '$variant_pic')";
                mysqli_query($conn, $sql_variant);
            }
        }

        echo "<script>alert('เพิ่มสินค้าสำเร็จ'); window.location='showproduct.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
