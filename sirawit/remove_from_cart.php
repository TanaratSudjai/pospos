<?php
session_start();

if (isset($_GET['variant_id'])) {
    $variant_id = (int) $_GET['variant_id'];
    if (isset($_SESSION['cart'][$variant_id])) {
        unset($_SESSION['cart'][$variant_id]);
    }
}

header("Location: cartpage.php");
exit();
?>
