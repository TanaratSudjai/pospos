<?php
include "connect.php";
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Function to generate PromptPay Payload
function generatePayload($promptpayID, $amount = 0)
{
    $promptpayID = preg_replace('/[^0-9]/', '', $promptpayID); // Remove non-numeric characters

    // Service Code for PromptPay
    $merchantType = "0016A000000677010111";

    if (strlen($promptpayID) == 10) {
        // Mobile Number (Phone Number must start with '66')
        $target = "0066" . substr($promptpayID, 1);
    } else if (strlen($promptpayID) == 15) {
        // Tax ID
        $target = "00" . $promptpayID;
    } else {
        return false; // Invalid PromptPay ID
    }

    // Create the EMVCo QR Code structure
    $payload  = "000201"; // Version
    $payload .= "010211"; // QR Code Type (Static QR)
    $payload .= "2937";   // Length of PromptPay Data
    $payload .= "0016" . $merchantType; // PromptPay Service
    $payload .= "0114" . $target; // Mobile Number or Tax ID
    $payload .= "530376"; // Currency (THB)

    if ($amount > 0) {
        $amountStr = number_format($amount, 2, '.', '');
        $payload .= "54" . str_pad(strlen($amountStr), 2, '0', STR_PAD_LEFT) . $amountStr;
    }

    $payload .= "5802TH"; // Country Code (Thailand)
    $payload .= "6304";   // Checksum placeholder

    // Calculate CRC16 checksum
    $payload .= calculateCRC16($payload);

    return $payload;
}

// ✅ **CRC16 Calculation**
function calculateCRC16($payload)
{
    $crc = 0xFFFF;
    for ($i = 0; $i < strlen($payload); $i++) {
        $x = (($crc >> 8) ^ ord($payload[$i])) & 0xFF;
        $x ^= $x >> 4;
        $crc = (($crc << 8) ^ ($x << 12) ^ ($x << 5) ^ $x) & 0xFFFF;
    }
    return $crc;
}



$order_id = $_GET['order_id'];

$query_order = "SELECT
                    tb_orders.order_id, 
                    tb_orders.total_amount, 
                    tb_orders.order_date
                FROM
                    tb_orders
                WHERE 
                    tb_orders.order_id = ?";

$stmt = mysqli_prepare($conn, $query_order);
mysqli_stmt_bind_param($stmt, "i", $order_id);
mysqli_stmt_execute($stmt);
$order_result = mysqli_stmt_get_result($stmt);
$order_data = mysqli_fetch_assoc($order_result);

// Set your PromptPay number here
$promptpay_number = "0641742127"; // เปลี่ยนเป็นเบอร์พร้อมเพย์ของร้านค้า
$amount = $order_data['total_amount'];


$qr = "https://promptpay.io/0641742127.png/" . $order_data['total_amount'];



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PromptPay Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title mb-0 text-center">
                            <i class="fas fa-qrcode me-2"></i>PromptPay Payment
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <!-- QR Code -->
                            <div class="bg-light p-4 mb-3 rounded">
                                <?php 
                                    echo '<img src="' . $qr . '" />';
                                
                                ?>


                            </div>
                            <div class="small text-muted">
                                Scan with any Banking app that supports PromptPay
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th class="bg-light">Order ID:</th>
                                        <td><?php echo $order_data['order_id']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Amount:</th>
                                        <td>฿<?php echo number_format($order_data['total_amount'], 2); ?></td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Date:</th>
                                        <td><?php echo date('d/m/Y H:i', strtotime($order_data['order_date'])); ?></td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">PromptPay Number:</th>
                                        <td><?php echo $promptpay_number; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button class="btn btn-success btn-lg" id="confirmPayment">
                                <i class="fas fa-check-circle me-2"></i>Confirm Payment
                            </button>
                            <a href="order.php" class="btn btn-secondary btn-lg">
                                <i class="fas fa-arrow-left me-2"></i>Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#confirmPayment').click(function() {
                if (confirm('ยืนยันการชำระเงิน?')) {
                    $.ajax({
                        url: 'update_payment_status.php',
                        type: 'POST',
                        data: {
                            order_id: <?php echo $order_id; ?>
                        },
                        success: function(response) {
                            alert('ชำระเงินเรียบร้อยแล้ว');
                            window.location.href = 'order_history.php';
                        },
                        error: function() {
                            alert('เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง');
                        }
                    });
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>