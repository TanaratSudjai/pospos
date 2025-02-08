<?php 
include "navbar_admin.php";
include "connect.php"; 

$ID = $_GET['ID'];  // รับ ID จาก URL
$sql = "SELECT * FROM tb_products WHERE product_id='$ID'";  // ใช้ชื่อฟิลด์ที่ตรงกับฐานข้อมูล
if (!$conn) {
    die("Connection Failed" . mysqli_connect_error());
} else {
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);

    // ดึงข้อมูลตัวเลือกสินค้าจาก tb_variants
    $variant_sql = "SELECT * FROM tb_variants WHERE product_id = '$ID'";
    $variant_result = mysqli_query($conn, $variant_sql);

    // ดึงข้อมูลหมวดหมู่สินค้าจาก tb_categories
    $category_sql = "SELECT * FROM tb_categories";
    $category_result = mysqli_query($conn, $category_sql);
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Product</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.min.js"></script>
    <script defer src="https://use.fontawesome.com/releases/v6.0.0/js/all.js" integrity="sha384-l+HksIGR+lyuyBo1+1zCBSRt6v4yklWu7RbG0Cv+jDLDD9WFcEIwZLHioVB4Wkau" crossorigin="anonymous"></script>
</head>
<body style="background-color: #f9f9f9; font-family: 'Arial', sans-serif;">
<div class="container" style="margin-top: 30px;">
    <form method="post" action="editproduct.php" enctype="multipart/form-data">
        <div class="card shadow-sm border-light rounded" style="background-color: #ffffff; padding: 30px;">
            <h3 class="text-center text-dark mb-4">แก้ไขข้อมูลสินค้า</h3>

            <!-- ฟอร์มแก้ไขข้อมูลสินค้า -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="productname" class="form-label">ชื่อสินค้า</label>
                    <input class="form-control" name="productname" type="text" id="productname" value="<?php echo "$row[name]"; ?>" />
                </div>
                <div class="col-md-6">
                    <label for="productprice" class="form-label">ราคาสินค้า</label>
                    <input class="form-control" name="productprice" type="text" id="productprice" value="<?php echo "$row[price]"; ?>" />
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="productdetails" class="form-label">รายละเอียดสินค้า</label>
                    <input class="form-control" name="productdetails" type="text" id="productdetails" value="<?php echo "$row[description]"; ?>" />
                </div>
            </div>

            <!-- เปลี่ยน input เป็น select สำหรับหมวดหมู่สินค้า -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="category" class="form-label">หมวดหมู่สินค้า</label>
                    <select class="form-control" name="category" id="category">
                        <?php while ($category_row = mysqli_fetch_array($category_result)) { ?>
                            <option value="<?php echo $category_row['category_id']; ?>" <?php echo ($row['category_id'] == $category_row['category_id']) ? 'selected' : ''; ?>>
                                <?php echo $category_row['category_name']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="uploadfile" class="form-label">รูปภาพสินค้า</label>
                    <!-- แสดงภาพเดิม -->
                    <div>
                        <img src="img/<?php echo $row['product_pic']; ?>" alt="Product Image" width="100" height="100">
                    </div>
                    <input class="form-control mt-2" type="file" name="uploadfile" id="uploadfile" />
                </div>
            </div>

            <!-- แสดงข้อมูลตัวเลือกสินค้า (variants) -->
            <h5 class="mt-4">ตัวเลือกสินค้า (Variants)</h5>
            <?php while ($variant_row = mysqli_fetch_array($variant_result)) { ?>
                <div class="variant-group mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="color" class="form-label">สี</label>
                            <input class="form-control" name="color[]" type="text" value="<?php echo $variant_row['color']; ?>" />
                        </div>
                        <div class="col-md-3">
                            <label for="size" class="form-label">ไซส์</label>
                            <input class="form-control" name="size[]" type="text" value="<?php echo $variant_row['size']; ?>" />
                        </div>
                        <div class="col-md-3">
                            <label for="stock_quantity" class="form-label">สต๊อก</label>
                            <input class="form-control" name="stock_quantity[]" type="number" value="<?php echo $variant_row['stock_quantity']; ?>" />
                        </div>
                        <div class="col-md-3">
                            <label for="variant_img" class="form-label">รูปภาพตัวเลือกสินค้า</label>
                            <div>
                                <img src="img/<?php echo $variant_row['variant_pic']; ?>" alt="Variant Image" width="50" height="50">
                            </div>
                            <input class="form-control mt-2" type="file" name="variant_img[]" />
                        </div>
                    </div>
                </div>
            <?php } ?>

            <!-- Hidden field for product ID -->
            <input class="form-control" name="ID" type="hidden" id="ID" value="<?php echo "$row[product_id]"; ?>" />

            <!-- ปุ่มตกลงและยกเลิก -->
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-success w-25">ตกลง</button>
                <button type="reset" class="btn btn-danger w-25">
                    <a class="link-light" href="showproduct.php" style="text-decoration:none">ยกเลิก</a>
                </button>
            </div>
        </div>
    </form>
</div>
</body>
</html>
