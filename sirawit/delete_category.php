<?php session_start(); ?>
<?php
include "navbar_admin.php";
include "connect.php";

if (!isset($_GET['ID'])) {
    Header("Location: category_management.php");
    exit();
}

$category_id = $_GET['ID'];

$sql = "DELETE FROM tb_categories WHERE category_id = '$category_id'";

if (mysqli_query($conn, $sql)) {
    Header("Location: category_management.php");
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
