<?php session_start(); ?>
<?php
include "navbar_admin.php";
include "connect.php";

if (!$_SESSION["UserID"]) {
    Header("Location: frmlogin.php");
} else {
    // ดึงข้อมูลคำสั่งซื้อจากตาราง orders
    $sql_orders = "SELECT * FROM tb_orders";
    $result_orders = mysqli_query($conn, $sql_orders);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Dashboard - Orders Summary</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.min.js"></script>
    <script defer src="https://use.fontawesome.com/releases/v6.0.0/js/all.js" integrity="sha384-l+HksIGR+lyuyBo1+1zCBSRt6v4yklWu7RbG0Cv+jDLDD9WFcEIwZLHioVB4Wkau" crossorigin="anonymous"></script>
</head>
<body style="background-color: #f4f6f9;">

<div class="container my-5">
    <div class="text-center mb-4">
        <h2 class="text-primary font-weight-bold">Orders Summary</h2>
        <p class="text-muted">Below are the details of all orders placed in the system.</p>
    </div>

    <div class="card shadow-sm rounded-lg">
        <div class="card-body">
            <table class="table table-striped table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Order ID</th>
                        <th>User ID</th>
                        <th>Order Date</th>
                        <th>Status</th>
                        <th>Total Amount</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row_order = mysqli_fetch_array($result_orders)) { ?>
                    <tr>
                        <td><?php echo $row_order['order_id']; ?></td>
                        <td><?php echo $row_order['user_id']; ?></td>
                        <td><?php echo $row_order['order_date']; ?></td>
                        <td><?php echo $row_order['status']; ?></td>
                        <td><?php echo number_format($row_order['total_amount'], 2); ?></td>
                        <td>
                            <a href="order_details.php?order_id=<?php echo $row_order['order_id']; ?>" class="btn btn-info btn-sm rounded-pill">View Details</a>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php mysqli_close($conn); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Custom Styles -->
<style>
    body {
        font-family: Arial, sans-serif;
    }

    .card {
        border-radius: 12px;
        background-color: #fff;
    }

    .card-body {
        padding: 20px;
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

</style>

</body>
</html>

<?php
include "footer.php";
?>
<?php } ?>
