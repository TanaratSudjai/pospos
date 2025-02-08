<?php session_start(); ?>
<?php
include "navbar_admin.php";
include "connect.php";

if (!$_SESSION["UserID"]) {
    Header("Location: frmlogin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_name = $_POST['category_name'];

    // เพิ่มหมวดหมู่สินค้าใหม่
    $insert_sql = "INSERT INTO tb_categories (category_name) VALUES ('$category_name')";
    if (mysqli_query($conn, $insert_sql)) {
        Header("Location: category_management.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Category</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body style="background-color: #f4f6f9;">

<div class="container my-4">
    <form method="POST">
        <div class="text-center mb-4">
            <h2 class="text-primary">Add New Category</h2>
        </div>

        <div class="card shadow-sm rounded-lg">
            <div class="card-body">
                <div class="form-group mb-3">
                    <label for="category_name" class="form-label">Category Name</label>
                    <input type="text" class="form-control" id="category_name" name="category_name" required placeholder="Enter category name">
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-success w-25 my-4">Add Category</button>
                    <a href="category_management.php" class="btn btn-danger w-25 my-4">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>

</body>
</html>
