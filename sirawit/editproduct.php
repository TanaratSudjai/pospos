<?php
include "connect.php"; 

// รับข้อมูลจากฟอร์ม
$productID = mysqli_real_escape_string($conn, $_POST['ID']);
$productname = mysqli_real_escape_string($conn, $_POST['productname']);
$productprice = mysqli_real_escape_string($conn, $_POST['productprice']);

$productdetails = mysqli_real_escape_string($conn, $_POST['productdetails']);
$category = mysqli_real_escape_string($conn, $_POST['category']); // รับข้อมูลหมวดหมู่

// ตรวจสอบการอัปโหลดรูปภาพใหม่
if (isset($_FILES['uploadfile']) && $_FILES['uploadfile']['error'] == 0) {
    $filename = $_FILES['uploadfile']['name'];
    $tempname = $_FILES['uploadfile']['tmp_name'];
    $folder = "./img/" . $filename;

    // ตรวจสอบประเภทไฟล์ (กรณีต้องการจำกัดประเภทไฟล์ เช่น jpg, png)
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (in_array($_FILES['uploadfile']['type'], $allowed_types)) {
        // อัปโหลดไฟล์รูปภาพ
        if (move_uploaded_file($tempname, $folder)) {
            // อัปเดตข้อมูลสินค้า
            $sql = "UPDATE tb_products SET name='$productname', price='$productprice', stock='$producttotal', description='$productdetails', category_id='$category', product_pic='$filename' WHERE product_id='$productID'";
        } else {
            echo "Error uploading file.";
            exit();
        }
    } else {
        echo "Invalid file type.";
        exit();
    }
} else {
    // ถ้าไม่มีการอัปโหลดรูปภาพใหม่ ให้ใช้รูปภาพเดิม
    $sql = "UPDATE tb_products SET name='$productname', price='$productprice', description='$productdetails', category_id='$category' WHERE product_id='$productID'";
}

// อัปเดตข้อมูลในตาราง `tb_products`
if (mysqli_query($conn, $sql)) {

    // การอัปเดตข้อมูลตัวเลือกสินค้า (variants)
    if (isset($_POST['color']) && isset($_POST['size']) && isset($_POST['stock_quantity'])) {
        $colors = $_POST['color'];
        $sizes = $_POST['size'];
        $stock_quantities = $_POST['stock_quantity'];
        $variant_images = $_FILES['variant_img']['name']; // รูปภาพของตัวเลือกสินค้า

        // ลูปผ่านตัวเลือกสินค้า (variants)
        foreach ($colors as $index => $color) {
            $size = mysqli_real_escape_string($conn, $sizes[$index]);
            $stock_quantity = mysqli_real_escape_string($conn, $stock_quantities[$index]);

            // ตรวจสอบการอัปโหลดรูปภาพสำหรับแต่ละตัวเลือก (variant)
            if (isset($variant_images[$index]) && $_FILES['variant_img']['error'][$index] == 0) {
                $variant_image = $variant_images[$index];
                $variant_tempname = $_FILES['variant_img']['tmp_name'][$index];
                $variant_folder = "img/variants/" . $variant_image;

                // ตรวจสอบประเภทไฟล์รูปภาพ
                if (in_array($_FILES['variant_img']['type'][$index], $allowed_types)) {
                    // อัปโหลดไฟล์รูปภาพ
                    if (move_uploaded_file($variant_tempname, $variant_folder)) {
                        // อัปเดตตัวเลือกสินค้าด้วยภาพใหม่
                        $variant_sql = "UPDATE tb_variants SET color='$color', size='$size', stock_quantity='$stock_quantity', variant_pic='$variant_image' WHERE product_id='$productID' AND color='$color' AND size='$size'";
                    } else {
                        // ถ้าไม่สามารถอัปโหลดภาพได้
                        $variant_sql = "UPDATE tb_variants SET color='$color', size='$size', stock_quantity='$stock_quantity' WHERE product_id='$productID' AND color='$color' AND size='$size'";
                    }
                } else {
                    echo "Invalid variant image type.";
                    exit();
                }
            } else {
                // ถ้าไม่มีการอัปโหลดภาพใหม่ ให้ใช้ภาพเดิม
                $variant_sql = "UPDATE tb_variants SET color='$color', size='$size', stock_quantity='$stock_quantity' WHERE product_id='$productID' AND color='$color' AND size='$size'";
            }

            // อัปเดตข้อมูลตัวเลือกสินค้า (variants)
            if (!mysqli_query($conn, $variant_sql)) {
                echo "Error updating variant: " . mysqli_error($conn);
                exit();
            }
        }
    }

    echo "<script>alert('ข้อมูลสินค้าอัปเดตเรียบร้อย'); window.location='showproduct.php';</script>";
} else {
    echo "Error updating product: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
