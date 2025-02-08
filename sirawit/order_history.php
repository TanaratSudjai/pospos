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

$history_order = "SELECT 
                    o.order_id,
                    o.order_date,
                    o.total_amount,
                    o.phone,
                    o.status,
                    od.variant_id,
                    od.quantity,
                    od.price,
                    p.name
                FROM 
                    tb_orders o
                JOIN 
                    tb_orderdetails od ON o.order_id = od.order_id
                JOIN 
                    tb_variants v ON od.variant_id = v.variant_id
                JOIN 
                    tb_products p ON p.product_id = v.product_id
                WHERE 
                    o.user_id = ?
                ORDER BY 
                    o.order_date DESC";

$stmt = mysqli_prepare($conn, $history_order);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ประวัติการซื้อ</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .table thead th {
            background-color: #4a5568;
            color: white;
            font-weight: 500;
            border: none;
        }
        .table-hover tbody tr:hover {
            background-color: #f0f4f8;
            transition: all 0.2s ease;
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
        <!-- Back Button -->
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
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-hashtag me-2"></i>เลขที่คำสั่งซื้อ</th>
                                    <th><i class="far fa-calendar-alt me-2"></i>วันที่สั่งซื้อ</th>
                                    <th><i class="fas fa-box me-2"></i>สินค้า</th>
                                    <th><i class="fas fa-sort-amount-up me-2"></i>จำนวน</th>
                                    <th><i class="fas fa-tag me-2"></i>ราคาต่อชิ้น</th>
                                    <th><i class="fas fa-coins me-2"></i>ราคารวม</th>
                                    <th><i class="fas fa-phone me-2"></i>เบอร์โทรศัพท์</th>
                                    <th><i class="fas fa-info-circle me-2"></i>สถานะ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td class="fw-bold"><?php echo htmlspecialchars($row['order_id']); ?></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($row['order_date'])); ?></td>
                                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                                        <td><?php echo number_format($row['price'], 2); ?> บาท</td>
                                        <td class="fw-bold"><?php echo number_format($row['total_amount'], 2); ?> บาท</td>
                                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                        <td>
                                            <span class="badge rounded-pill <?php 
                                                echo match($row['status']) {
                                                    'pending' => 'bg-warning',
                                                    'processing' => 'bg-info',
                                                    'completed' => 'bg-success',
                                                    'cancelled' => 'bg-danger',
                                                    default => 'bg-secondary'
                                                };
                                            ?>">
                                                <i class="fas <?php 
                                                    echo match($row['status']) {
                                                        'pending' => 'fa-clock',
                                                        'processing' => 'fa-spinner fa-spin',
                                                        'completed' => 'fa-check',
                                                        'cancelled' => 'fa-times',
                                                        default => 'fa-question'
                                                    };
                                                ?> me-1"></i>
                                                <?php echo htmlspecialchars($row['status']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
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