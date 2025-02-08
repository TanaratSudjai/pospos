<?php
// update_payment_status.php
include "connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];

    // Start transaction
    mysqli_begin_transaction($conn);

    try {
        // Insert payment record
        $query_insert_payment = "INSERT INTO tb_payments (order_id, payment_amount, payment_date, payment_status) 
                               SELECT order_id, total_amount, NOW(), 'completed' 
                               FROM tb_orders 
                               WHERE order_id = ?";
        $stmt = mysqli_prepare($conn, $query_insert_payment);
        mysqli_stmt_bind_param($stmt, "i", $order_id);
        mysqli_stmt_execute($stmt);

        // Update order status
        $query_update_order = "UPDATE tb_orders 
                             SET status = 'ชำระแล้ว' 
                             WHERE order_id = ?";
        $stmt = mysqli_prepare($conn, $query_update_order);
        mysqli_stmt_bind_param($stmt, "i", $order_id);
        mysqli_stmt_execute($stmt);

        // Commit transaction
        mysqli_commit($conn);
        echo json_encode(['status' => 'success']);

    } catch (Exception $e) {
        // Rollback in case of error
        mysqli_rollback($conn);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
?>