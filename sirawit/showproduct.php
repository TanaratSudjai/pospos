<?php session_start(); ?>
<?php
include "navbar_admin.php";
include "connect.php";

// เปลี่ยน query เพื่อดึงข้อมูลจากทั้ง tb_products และ tb_variants
$sql = "
SELECT p.*, v.stock_quantity 
FROM tb_products p
LEFT JOIN tb_variants v ON p.product_id = v.product_id
";

$result = mysqli_query($conn, $sql);

if (!$_SESSION["UserID"]) {
    Header("Location: frmlogin.php");
    exit();
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Product Management</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.min.js"></script>
</head>
<body style="background-color: #f4f6f9;">

<div class="container my-5">
    <div class="text-center mb-4">
        <h2 class="text-primary font-weight-bold">Product Management</h2>
        <p class="text-muted">Manage all your products here</p>
    </div>

    <div class="card shadow-sm rounded-lg">
        <div class="card-body">
            <!-- เพิ่มปุ่มหมวดหมู่สินค้า -->
            <a class="btn btn-dark mb-3" href="category_management.php" role="button">Manage Categories</a>
            <!-- ปุ่มเพิ่มสินค้าใหม่ -->
            <a class="btn btn-dark mb-3" href="frminsertproduct.php" role="button">Add New Product</a>

            <table class="table table-striped table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Product ID</th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Image</th>
                        <th>Description</th>
                        <th>Variants</th> <!-- เพิ่มคอลัมน์ตัวเลือกสินค้า -->
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    while ($row = mysqli_fetch_array($result)) { 
                        $product_id = $row['product_id'];
                    ?>
                    <tr>
                        <td><?php echo $row['product_id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo number_format($row['price'], 2); ?></td>
                        <td><?php echo $row['stock_quantity']; ?></td>
                        <td><img src="./img/<?php echo $row['product_pic']; ?>" width="80px" class="rounded"></td>
                        <td class="text-left"><?php echo $row['description']; ?></td>

                        <!-- ดึงตัวเลือกสินค้าจาก tb_variants -->
                        <td>
                            <ul>
                                <?php 
                                $sql_variants = "SELECT * FROM tb_variants WHERE product_id = '$product_id'";
                                $variants_result = mysqli_query($conn, $sql_variants);
                                while ($variant = mysqli_fetch_assoc($variants_result)) { 
                                    echo "<li>" . $variant['color'] . " - " . $variant['size'] . " (" . $variant['stock_quantity'] . ")</li>";
                                } 
                                ?>
                            </ul>
                        </td>

                        <td>
                            <a href="frm_editproduct.php?ID=<?php echo $row['product_id']; ?>" class="btn btn-warning btn-sm rounded-pill">Edit</a>
                        </td>
                        <td>
                            <a href="frm_deleteproduct.php?ID=<?php echo $row['product_id']; ?>" class="btn btn-danger btn-sm rounded-pill">Delete</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

<!-- Custom Styles -->
<style>
    body {
        font-family: Arial, sans-serif;
    }

    .card {
        border-radius: 12px;
        background-color: #fff;
    }

    .card-body {
        padding: 20px;
    }

    .btn {
        border-radius: 50px;
        font-size: 0.9rem;
        padding: 6px 16px;
    }

    .table {
        margin-top: 20px;
    }

    th, td {
        text-align: center;
        vertical-align: middle;
    }

    .thead-light th {
        background-color: #f0f0f0;
        color: #495057;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #f9f9f9;
    }

    .table-hover tbody tr:hover {
        background-color: #f1f1f1;
    }

    td img {
        border-radius: 5px;
    }

    .text-left {
        text-align: left;
    }

    .btn-sm {
        padding: 5px 10px;
    }

    .btn-warning, .btn-danger {
        font-size: 0.9rem;
    }

</style>

</body>
</html>

<?php
include "footer.php";
?>
<?php } ?>
