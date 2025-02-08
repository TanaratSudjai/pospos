<?php session_start(); ?>
<?php
include "navbar_admin.php";
include "connect.php";

if (!isset($_GET['ID'])) {
    Header("Location: category_management.php");
    exit();
}

$category_id = $_GET['ID'];

$sql = "SELECT * FROM tb_categories WHERE category_id = '$category_id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_name = $_POST['category_name'];

    $update_sql = "UPDATE categories SET category_name = '$category_name' WHERE category_id = '$category_id'";
    if (mysqli_query($conn, $update_sql)) {
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
    <title>Edit Category</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body style="background-color: #f4f6f9;">

<div class="container my-4">
    <form method="POST">
        <div class="text-center mb-4">
            <h2 class="text-primary">Edit Category</h2>
        </div>

        <div class="card shadow-sm rounded-lg">
            <div class="card-body">
                <div class="form-group mb-3">
                    <label for="category_name" class="form-label">Category Name</label>
                    <input type="text" class="form-control" id="category_name" name="category_name" value="<?php echo $row['category_name']; ?>" required>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-success w-25 my-4">Save Changes</button>
                    <a href="category_management.php" class="btn btn-danger w-25 my-4">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>

</body>
</html>
