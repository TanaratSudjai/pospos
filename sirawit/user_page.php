<?php session_start(); ?>
<?php
include "navbaruse.php";
include "connect.php";

$strKeyword = "";
if (isset($_POST["txtKeyword"])) {
    $strKeyword = $_POST["txtKeyword"];
}

$sql = "SELECT p.product_id, p.name 
        AS product_name, p.price, 
            c.category_name, 
            v.variant_id, 
            v.color, 
            v.size, 
            v.stock_quantity 
        FROM tb_products p 
        JOIN tb_categories c 
        ON p.category_id = c.category_id 
        JOIN tb_variants v 
        ON p.product_id = v.product_id;
        ";

$result = mysqli_query($conn, $sql);

if (!$_SESSION["UserID"]) {
    Header("Location: frmlogin.php");
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
    <title>Product Page</title>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .square-img {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 10px;
        }

        .variant-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="text-center mt-4">รายการสินค้า</h2>
        <form action="user_page.php" method="POST" class="mb-4">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="ค้นหาสินค้า" name="txtKeyword"
                    value="<?php echo $strKeyword; ?>">
                <button class="btn btn-dark" type="submit">ค้นหา</button>
            </div>
        </form>

        <div class="row">
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header text-center">
                            <?= htmlspecialchars($row['product_name']); ?>
                        </div>

                        <!-- Start of Carousel for Images -->
                        <div id="carouselExampleIndicators<?= $row['product_id']; ?>" class="carousel slide"
                            data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <?php
                                // ดึงข้อมูลรูปภาพที่เกี่ยวข้องกับสินค้า
                                $variant_sql = "SELECT variant_pic FROM tb_variants WHERE product_id = {$row['product_id']} AND stock_quantity > 0";
                                $variant_result = mysqli_query($conn, $variant_sql);
                                $first_image = true; // ใช้สำหรับการตั้งค่า active สำหรับรูปแรก
                                while ($variant = mysqli_fetch_assoc($variant_result)) {
                                    $image_path = './img/' . $variant['variant_pic'];
                                    $active_class = $first_image ? 'active' : ''; // ตั้งค่า active สำหรับรูปแรก
                                    echo '<div class="carousel-item ' . $active_class . '">
                                              <img src="' . $image_path . '" class="d-block w-100" alt="product image">
                                          </div>';
                                    $first_image = false; // หลังจากแสดงรูปแรกแล้วเปลี่ยนค่า
                                }
                                ?>
                            </div>
                            <button class="carousel-control-prev" type="button"
                                data-bs-target="#carouselExampleIndicators<?= $row['product_id']; ?>" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button"
                                data-bs-target="#carouselExampleIndicators<?= $row['product_id']; ?>" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                        <!-- End of Carousel for Images -->

                        <div class="card-body">
                            <p class="card-text">รายละเอียด: <?= htmlspecialchars($row['category_name']); ?></p>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">จำนวนรวม: <?= $row['stock_quantity']; ?> ชิ้น</li>
                            <li class="list-group-item">ราคา: <?= number_format($row['price'], 2); ?> บาท</li>
                        </ul>
                        <div class="card-body">
                            <form action="cart_add.php" method="GET">
                                <input type="hidden" name="product_id" value="<?= $row['product_id']; ?>">
                                <select name="variant_id" class="form-select mb-2" required>
                                    <option value="">เลือกสี/ขนาด</option>
                                    <?php
                                    // ดึงข้อมูลสีและขนาดจาก tb_variants
                                    $variant_sql = "SELECT variant_id, color, size FROM tb_variants WHERE product_id = {$row['product_id']} AND stock_quantity > 0";
                                    $variant_result = mysqli_query($conn, $variant_sql);
                                    while ($variant = mysqli_fetch_assoc($variant_result)) {
                                        echo '<option value="' . $variant['variant_id'] . '">' . $variant['color'] . ' - ' . $variant['size'] . '</option>';
                                    }
                                    ?>
                                </select>
                                <button type="submit" class="btn btn-primary w-100">สั่งซื้อสินค้า</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</body>
<?php include "footer.php"; ?>

</html>