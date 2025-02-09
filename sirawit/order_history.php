<?php
include "connect.php";
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$user_id = isset($_SESSION['UserID']) ? $_SESSION['UserID'] : null;

if (!$user_id) {
    header("Location: login.php");
    exit();
}

// ดึงข้อมูลคำสั่งซื้อทั้งหมดของผู้ใช้
$history_order = "SELECT 
                    o.order_id,
                    o.order_date,
                    o.total_amount,
                    o.phone,
                    o.status
                FROM 
                    tb_orders o
                WHERE 
                    o.user_id = ?
                ORDER BY 
                    o.order_date DESC";

$stmt = mysqli_prepare($conn, $history_order);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$order_result = mysqli_stmt_get_result($stmt);

// ดึงข้อมูลสินค้าที่อยู่ในคำสั่งซื้อนั้นๆ
$order_details = [];
$details_query = "SELECT 
                    od.order_id,
                    od.variant_id,
                    od.quantity,
                    od.price,
                    p.name
                FROM 
                    tb_orderdetails od
                JOIN 
                    tb_variants v ON od.variant_id = v.variant_id
                JOIN 
                    tb_products p ON p.product_id = v.product_id
                WHERE 
                    od.order_id = ?";

$stmt_details = mysqli_prepare($conn, $details_query);

while ($order = mysqli_fetch_assoc($order_result)) {
    $order_id = $order['order_id'];

    // ดึงรายการสินค้าของ order_id ปัจจุบัน
    mysqli_stmt_bind_param($stmt_details, "i", $order_id);
    mysqli_stmt_execute($stmt_details);
    $details_result = mysqli_stmt_get_result($stmt_details);

    $order['items'] = [];
    while ($detail = mysqli_fetch_assoc($details_result)) {
        $order['items'][] = $detail;
    }

    $order_details[] = $order;
}
mysqli_stmt_close($stmt_details);
mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ประวัติการซื้อ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .table thead th {
            background-color: #4a5568;
            color: white;
            font-weight: 500;
            border: none;
        }

        .back-btn {
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            transform: translateX(-5px);
        }

        .card-header {
            background-color: #4a5568;
            color: white;
            border-radius: 15px 15px 0 0 !important;
        }

        .badge {
            font-size: 0.85em;
            padding: 8px 12px;
        }

        .table td {
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <div class="container my-5">
        <a href="/user_page.php" class="btn btn-outline-secondary mb-4 back-btn">
            <i class="fas fa-arrow-left me-2"></i>ย้อนกลับ
        </a>

        <div class="card">
            <div class="card-header py-3">
                <h4 class="mb-0">
                    <i class="fas fa-history me-2"></i>ประวัติการซื้อ
                </h4>
            </div>
            <div class="card-body">
                <?php if (!empty($order_details)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>เลขที่คำสั่งซื้อ</th>
                                    <th>วันที่สั่งซื้อ</th>
                                    <th>ราคารวม</th>
                                    <th>เบอร์โทรศัพท์</th>
                                    <th>สถานะ</th>
                                    <th>สินค้า</th>
                                    <th>จ่ายเงิน</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order_details as $order): ?>
                                    <tr>
                                        <td class="fw-bold"><?php echo htmlspecialchars($order['order_id']); ?></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($order['order_date'])); ?></td>
                                        <td class="fw-bold"><?php echo number_format($order['total_amount'], 2); ?> บาท</td>
                                        <td><?php echo htmlspecialchars($order['phone']); ?></td>
                                        <td>
                                            <span class="badge rounded-pill <?php
                                                                            echo match ($order['status']) {
                                                                                'pending' => 'bg-warning',
                                                                                'processing' => 'bg-info',
                                                                                'completed' => 'bg-success',
                                                                                'cancelled' => 'bg-danger',
                                                                                'ชำระแล้ว' => 'bg-success', // เปลี่ยนเป็นสีเขียว
                                                                                default => 'bg-secondary'
                                                                            };
                                                                            ?>">
                                                <i class="fas <?php
                                                                echo match ($order['status']) {
                                                                    'pending' => 'fa-clock',
                                                                    'processing' => 'fa-spinner fa-spin',
                                                                    'completed' => 'fa-check',
                                                                    'cancelled' => 'fa-times',
                                                                    'ชำระแล้ว' => 'fa-money-bill-wave', // เปลี่ยนไอคอนเป็นเงิน
                                                                    default => 'fa-question'
                                                                };
                                                                ?> me-1"></i>
                                                <?php echo htmlspecialchars($order['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <ul class="list-unstyled">
                                                <?php foreach ($order['items'] as $item): ?>
                                                    <li>
                                                        <i class="fas fa-box"></i>
                                                        <?php echo htmlspecialchars($item['name']); ?>
                                                        (<?php echo htmlspecialchars($item['quantity']); ?> ชิ้น ×
                                                        <?php echo number_format($item['price'], 2); ?> บาท)
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </td>
                                        <td>
                                            <?php if ($order['status'] !== 'ชำระแล้ว'): ?>
                                                <a href="/promptpay_get.php?order_id=<?php echo $order['order_id']; ?>">จ่ายเลย</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>

                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info d-flex align-items-center" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <div>
                            ยังไม่มีประวัติการสั่งซื้อ
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>