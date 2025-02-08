<?php
session_start();

if (isset($_POST['quantity']) && is_array($_POST['quantity'])) {
    foreach ($_POST['quantity'] as $variant_id => $quantity) {
        if (isset($_SESSION['cart'][$variant_id]) && $quantity > 0) {
            $_SESSION['cart'][$variant_id]['quantity'] = $quantity;
        } elseif ($quantity == 0) {
            unset($_SESSION['cart'][$variant_id]);
        }
    }
}

header("Location: cartpage.php");
exit();
?>
