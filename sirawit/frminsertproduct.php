<?php include "navbar_admin.php"; ?>
<?php include "connect.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOXIS - เพิ่มสินค้า</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.min.js"></script>
</head>
<body style="background-color: #f9f9f9;">

<form action="insert_product.php" method="post" enctype="multipart/form-data" class="py-5">
    <div class="container col-md-8 bg-white p-5 rounded shadow-sm">
        <h1 class="text-center text-dark mb-4">เพิ่มสินค้า</h1>
        
        <div class="mb-3">
            <label class="fs-5 text-dark">ชื่อสินค้า</label>
            <input type="text" name="productname" required class="form-control">
        </div>
        
        <div class="mb-3">
            <label class="fs-5 text-dark">ราคาสินค้า</label>
            <input type="number" name="productprice" required class="form-control">
        </div>
        
        <div class="mb-3">
            <label class="fs-5 text-dark">รายละเอียดสินค้า</label>
            <textarea name="productdetails" required class="form-control" rows="3"></textarea>
        </div>
        
        <?php
        $sql = "SELECT * FROM tb_categories";
        $result = mysqli_query($conn, $sql);
        ?>
        <div class="mb-3">
            <label class="fs-5 text-dark">หมวดหมู่สินค้า</label>
            <select name="category" required class="form-control">
                <option value="">เลือกหมวดหมู่</option>
                <?php while ($row = mysqli_fetch_array($result)) { ?>
                    <option value="<?= $row['category_id'] ?>"><?= $row['category_name'] ?></option>
                <?php } ?>
            </select>
        </div>
        
        <div class="mb-3">
            <label class="fs-5 text-dark">อัปโหลดรูปภาพสินค้า</label>
            <input type="file" name="uploadfile" class="form-control" required>
        </div>
        
        <h3 class="text-dark mt-4">เพิ่มตัวเลือกสินค้า</h3>
        <!-- เพิ่มตัวเลือกสินค้า -->
<div id="variant-container">
    <div class="variant-row mb-3">
        <div class="row">
            <div class="col-md-3"><input type="text" name="color[]" placeholder="สี" class="form-control"></div>
            <div class="col-md-3">
                <select name="size[]" class="form-control">
                    <option value="S">S</option>
                    <option value="M">M</option>
                    <option value="L">L</option>
                    <option value="XL">XL</option>
                </select>
            </div>
            <div class="col-md-3"><input type="number" name="stock_quantity[]" placeholder="จำนวน" class="form-control"></div>
            <div class="col-md-2"><input type="file" name="variant_img[]" class="form-control"></div>
            <div class="col-md-1 text-center">
                <button type="button" class="btn btn-danger remove-variant">-</button>
            </div>
        </div>
    </div>
</div>
<button type="button" id="add-variant" class="btn btn-primary w-100">+ เพิ่มตัวเลือกสินค้า</button>

        
        <div class="text-center mt-4">
            <button type="submit" name="upload" class="btn btn-success w-50">บันทึกสินค้า</button>
            <a href="showproduct.php" class="btn btn-secondary w-50 mt-2">ย้อนกลับ</a>
        </div>
    </div>
</form>

<script>
document.getElementById("add-variant").addEventListener("click", function () {
    let container = document.getElementById("variant-container");
    let newRow = document.createElement("div");
    newRow.classList.add("variant-row", "mb-3");
    newRow.innerHTML = `
        <div class="row">
            <div class="col-md-3"><input type="text" name="color[]" placeholder="สี" class="form-control"></div>
            <div class="col-md-3">
                <select name="size[]" class="form-control">
                    <option value="M">M</option>
                    <option value="L">L</option>
                    <option value="XL">XL</option>
                </select>
            </div>
            <div class="col-md-3"><input type="number" name="stock_quantity[]" placeholder="จำนวน" class="form-control"></div>
            <div class="col-md-2"><input type="file" name="variant_img[]" class="form-control"></div>
            <div class="col-md-1 text-center">
                <button type="button" class="btn btn-danger remove-variant">-</button>
            </div>
        </div>
    `;
    container.appendChild(newRow);
});

document.addEventListener("click", function (event) {
    if (event.target.classList.contains("remove-variant")) {
        event.target.closest(".variant-row").remove();
    }
});
</script>
</body>
</html>
