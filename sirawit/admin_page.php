<?php
session_start(); // เริ่มเซสชั่นเพื่อการจัดการกับข้อมูลผู้ใช้

include "navbar_admin.php";
include "connect.php"; // เชื่อมต่อกับฐานข้อมูล

// ตรวจสอบการเชื่อมต่อฐานข้อมูล
$sql = "SELECT * FROM tb_users WHERE role = 'user'"; // เพิ่มเงื่อนไข WHERE role = 'user'
$result = mysqli_query($conn, $sql);

// ตรวจสอบว่า UserID ของ session มีค่าหรือไม่
if (!$_SESSION["UserID"]) {
    header("Location: frmlogin.php"); // หากไม่มีการล็อกอิน จะถูกเปลี่ยนเส้นทางไปที่หน้า login
    exit(); // หยุดการทำงาน
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.min.js"></script>
    <script defer src="https://use.fontawesome.com/releases/v6.0.0/js/all.js" integrity="sha384-l+HksIGR+lyuyBo1+1zCBSRt6v4yklWu7RbG0Cv+jDLDD9WFcEIwZLHioVB4Wkau" crossorigin="anonymous"></script>
</head>
<body style="background-color: #f4f6f9;"> <!-- ปรับสีพื้นหลังให้สบายตา -->

<div class="container my-5">
    <div class="text-center mb-4">
        <h2 class="text-primary font-weight-bold">Admin Dashboard</h2>
        <p class="text-muted">Welcome to the admin panel. Manage users and their details.</p>
    </div>

    <div class="card shadow-sm rounded-lg">
        <div class="card-body">
            <table class="table table-striped table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Firstname</th>
                        <th>Lastname</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Gender</th>
                        <th>Address</th>
                        <th>Role</th>
                        <th>Update</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // ตรวจสอบว่า query ดึงข้อมูลมาได้หรือไม่
                    if ($result) {
                        while ($row = mysqli_fetch_array($result)) { ?>
                        <tr>
                            <td><?php echo $row['user_id']; ?></td>
                            <td><?php echo $row['username']; ?></td>
                            <td><?php echo $row['password']; ?></td>
                            <td><?php echo $row['firstname']; ?></td>
                            <td><?php echo $row['lastname']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['phone']; ?></td>
                            <td><?php echo $row['gender']; ?></td>
                            <td><?php echo $row['address']; ?></td>
                            <td><?php echo $row['role']; ?></td>
                            <td>
                                <a href="frm_edit.php?user_id=<?php echo $row['user_id']; ?>" class="btn btn-warning btn-sm rounded-pill">Edit</a>
                            </td>
                            <td>
                                <a href="frm_delete.php?user_id=<?php echo $row['user_id']; ?>" class="btn btn-danger btn-sm rounded-pill">Delete</a>
                            </td>
                        </tr>
                        <?php 
                        }
                    } else {
                        echo "<tr><td colspan='12' class='text-center text-danger'>No data found.</td></tr>";
                    }
                    mysqli_close($conn); // ปิดการเชื่อมต่อฐานข้อมูล
                    ?>
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

    .status-box {
        display: inline-block;
        padding: 10px 20px;
        font-size: 1.2rem;
        background-color: #fff;
        color: #333;
        border: 2px solid #ccc;
        border-radius: 6px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .status-box:hover {
        border-color: rgb(75, 67, 51);
        color: rgb(35, 37, 29);
    }
</style>

</body>
</html>

<?php
include "footer.php"; // เรียกใช้ footer ที่คุณต้องการแสดง
?>

<?php } ?> <!-- ปิดเงื่อนไข if -->
