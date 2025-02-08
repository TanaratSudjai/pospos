<?php session_start(); ?>
<?php
include "navbaruse.php";
include "connect.php";

if (isset($_GET['ID'])) {
    $product_id = $_GET['ID'];

    $sql = "SELECT * FROM tb_products WHERE product_id = $product_id";
    $result = mysqli_query($conn, $sql);
    $product = mysqli_fetch_assoc($result);

    if (!$product) {
        echo "สินค้าที่คุณค้นหาไม่พบ.";
        exit();
    }

    // ดึงข้อมูลสีและไซส์ที่มีจาก tb_variants
    $variant_sql = "SELECT DISTINCT color, variant_pic FROM tb_variants WHERE product_id = $product_id";
    $variant_result = mysqli_query($conn, $variant_sql);

    // ดึงข้อมูลไซส์ของแต่ละสี
    $size_sql = "SELECT DISTINCT color, size FROM tb_variants WHERE product_id = $product_id ORDER BY color, size";
    $size_result = mysqli_query($conn, $size_sql);

    $sizes_by_color = [];
    while ($size_row = mysqli_fetch_assoc($size_result)) {
        $sizes_by_color[$size_row['color']][] = $size_row['size'];
    }
} else {
    echo "ไม่พบรหัสสินค้าที่ระบุ.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.bundle.min.js"></script>
    <title>รายละเอียดสินค้า</title>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #fff;
            font-weight: bold;
            color: #6c757d;
        }
        .square-img {
            width: 100%;
            height: 700px;
            object-fit: cover;
            border-radius: 10px;
        }
        .variant-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 10px;
        }
        .btn {
            border-radius: 25px;
        }
        .variant-list {
            margin-top: 20px;
        }
        .back-btn {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center mt-4">รายละเอียดสินค้า</h2>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <?= htmlspecialchars($product['name']); ?>
                    </div>
                    <img src="./img/<?= htmlspecialchars($product['product_pic']); ?>" class="square-img">
                    <div class="card-body">
                        <p class="card-text"> <?= htmlspecialchars($product['description']); ?> </p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        สีและไซส์ที่มี
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush variant-list">
                            <?php while ($variant = mysqli_fetch_assoc($variant_result)) { ?>
                                <li class="list-group-item">
                                    <div class="d-flex align-items-center">
                                        <img src="img/<?= htmlspecialchars($variant['variant_pic']); ?>" class="variant-img me-3">
                                        <div>
                                            <strong>สี: <?= htmlspecialchars($variant['color']); ?></strong><br>
                                            <span>ไซส์ที่มี: <?= implode(', ', $sizes_by_color[$variant['color']]); ?></span>
                                        </div>
                                    </div>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <div class="row back-btn">
                    <div class="col-md-6">
                        <a href="user_page.php" class="btn btn-secondary w-100">ย้อนกลับ</a>
                    </div>
                    <div class="col-md-6">
                        <a href="cartpage.php?cartid=<?= $product['product_id']; ?>" class="btn btn-success w-100">เพิ่มลงในตะกร้า</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<?php include "footer.php"; ?>
</html>
