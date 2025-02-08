<!DOCTYPE html>
<?php
include "navbar_admin.php";
include "connect.php";

$ID = isset($_GET['user_id']) ? $_GET['user_id'] : ''; 
$sql = "SELECT * FROM tb_users WHERE user_id = '$ID'";

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
} else {
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
    } else {
        echo "ไม่พบข้อมูลผู้ใช้";
        exit();
    }
}
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>View User Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5" style="max-width: 600px;">
        <form method="post" action="delete.php" class="p-4 border rounded-3 shadow-sm bg-white">
            <h3 class="text-center mb-4">รายละเอียดสมาชิก</h3>
            
            <input type="hidden" name="ID" value="<?php echo $row['user_id']; ?>" />

            <div class="mb-3">
                <label for="Username" class="form-label">Username</label>
                <input type="text" id="Username" name="Username" class="form-control" value="<?php echo $row['username']; ?>" readonly>
            </div>

            <div class="mb-3">
                <label for="Password" class="form-label">Password</label>
                <input type="text" id="Password" name="Password" class="form-control" value="<?php echo $row['password']; ?>" readonly>
            </div>

            <div class="mb-3">
                <label for="Firstname" class="form-label">Firstname</label>
                <input type="text" id="Firstname" name="Firstname" class="form-control" value="<?php echo $row['firstname']; ?>" readonly>
            </div>

            <div class="mb-3">
                <label for="Lastname" class="form-label">Lastname</label>
                <input type="text" id="Lastname" name="Lastname" class="form-control" value="<?php echo $row['lastname']; ?>" readonly>
            </div>

            <div class="mb-3">
                <label for="Email" class="form-label">Email</label>
                <input type="email" id="Email" name="Email" class="form-control" value="<?php echo $row['email']; ?>" readonly>
            </div>

            <div class="mb-3">
                <label for="Phone" class="form-label">Phone</label>
                <input type="text" id="Phone" name="Phone" class="form-control" value="<?php echo $row['phone']; ?>" readonly>
            </div>

            <div class="mb-3">
                <label for="Address" class="form-label">Address</label>
                <input type="text" id="Address" name="Address" class="form-control" value="<?php echo $row['address']; ?>" readonly>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-danger">ลบ</button>
                <a href="admin_page.php" class="btn btn-secondary text-white">ยกเลิก</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
