<?php session_start(); ?>
<?php
include "navbar_admin.php";
include "connect.php";

$sql = "SELECT * FROM tb_categories"; // ดึงข้อมูลหมวดหมู่สินค้า
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
    <title>Category Management</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body style="background-color: #f4f6f9;">

<div class="container my-4">
    <form action="" method="post">
        <div class="text-center mb-4">
            <h2 class="text-primary font-weight-bold">Manage Categories</h2>
            <p class="text-muted">Manage all your product categories here</p>
        </div>

        <a class="btn btn-dark my-3" href="frm_insertcategory.php" role="button">Add New Category</a>

        <table class="table table-striped table-bordered table-hover">
            <thead class="thead-light">
                <tr>
                    <th>Category ID</th>
                    <th>Category Name</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_array($result)) { ?>
                <tr>
                    <td><?php echo $row['category_id']; ?></td>
                    <td><?php echo $row['category_name']; ?></td>
                    <td>
                        <a href="edit_category.php?ID=<?php echo $row['category_id']; ?>" class="btn btn-warning btn-sm rounded-pill">Edit</a>
                    </td>
                    <td>
                        <a href="delete_category.php?ID=<?php echo $row['category_id']; ?>" class="btn btn-danger btn-sm rounded-pill">Delete</a>
                    </td>
                </tr>
                <?php } ?>
                <?php mysqli_close($conn); ?>
            </tbody>
        </table>
    </form>
</div>

<!-- Custom Styles -->
<style>
    body {
        font-family: Arial, sans-serif;
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

<?php } ?>
