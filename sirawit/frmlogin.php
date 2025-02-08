<!DOCTYPE html>
<?php
include "navbar.php";
include "connect.php";
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SOXIS</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.min.js"></script>
    <script defer src="https://use.fontawesome.com/releases/v6.0.0/js/all.js" integrity="sha384-l+HksIGR+lyuyBo1+1zCBSRt6v4yklWu7RbG0Cv+jDLDD9WFcEIwZLHioVB4Wkau" crossorigin="anonymous"></script>
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById("Password");
            const toggleIcon = document.getElementById("togglePasswordIcon");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleIcon.classList.remove("fa-eye");
                toggleIcon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                toggleIcon.classList.remove("fa-eye-slash");
                toggleIcon.classList.add("fa-eye");
            }
        }
    </script>
</head>
<body>
<form name="frmlogin" method="post" action="login.php">
    <div class="container col-md-4 bg-light rounded shadow-lg" style="margin-top: 20px; padding: 20px;">
        <h2 class="text-center my-3 text-dark">ลงชื่อเข้าใช้</h2>
        
        <label for="Username" class="fs-5 text-dark">ชื่อผู้ใช้</label>
        <input type="text" id="Username" name="Username" required class="form-control mb-3" placeholder="กรุณากรอกชื่อผู้ใช้">
        
        <label for="Password" class="fs-5 text-dark">รหัสผ่าน</label>
        <div class="input-group mb-3">
            <input type="password" id="Password" name="Password" required class="form-control" placeholder="กรุณากรอกรหัสผ่าน">
            <button type="button" class="btn btn-light border rounded-end" onclick="togglePasswordVisibility()">
                <i id="togglePasswordIcon" class="fa fa-eye"></i>
            </button>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-success w-25 my-2">เข้าสู่ระบบ</button>
            <button type="reset" class="btn btn-danger w-25 my-2">
                <a class="link-light" href="index.php" style="text-decoration:none">ยกเลิก</a>
            </button>
        </div>   

        <div class="wrapper text-center">
            <a class="btn btn-secondary w-50 my-2" href="frminsert.php" style="text-decoration:none">สมัครสมาชิกได้ที่นี่!</a>
        </div>
    </div>
</form>

</body>
<br>
<br>
<?php
include "footer.php";
?>
</html>

<?php
// ตรวจสอบการเข้าสู่ระบบ (login.php)
session_start();

if (isset($_POST['Username']) && isset($_POST['Password'])) {
    include("connect.php");

    // รับข้อมูลจากฟอร์ม
    $Username = $_POST['Username'];
    $Password = $_POST['Password'];

    // ใช้ SQL Injection Prevention (Prepared Statements)
    $sql = "SELECT * FROM tb_users WHERE username = ? AND password = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $Username, $Password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // ตรวจสอบว่ามีผู้ใช้หรือไม่
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result);

        // เก็บข้อมูลผู้ใช้ใน session
        $_SESSION["UserID"] = $row["user_id"];
        $_SESSION["User"] = $row["username"]; // ใช้ชื่อผู้ใช้แทน Firstname Lastname
        $_SESSION["Userlevel"] = $row["role"]; // ใช้ "role" แทน "Userlevel"

        // ตรวจสอบระดับผู้ใช้
        if ($_SESSION["Userlevel"] == "admin") {
            Header("Location: admin_page.php"); // สำหรับผู้ใช้ที่เป็น admin
        } elseif ($_SESSION["Userlevel"] == "user") {
            Header("Location: user_page.php"); // สำหรับผู้ใช้ที่เป็น user
        }
    } else {
        echo "<script>";
        echo "alert(\"ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง\");";
        echo "window.history.back()";
        echo "</script>";
    }
} else {
    Header("Location: frmlogin.php"); // ถ้าไม่มีการส่งข้อมูลจะไปที่หน้าล็อกอิน
}
?>
