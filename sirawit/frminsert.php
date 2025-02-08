<!DOCTYPE html>
<?php
include "navbar.php";
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SOXIS - สมัครสมาชิก</title>
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
<body style="background-color: #f8f9fa;"> 

<form action="insert.php" method="post">
    <h1 class="text-center text-primary my-4">สมัครสมาชิก</h1>

    <div class="container col-md-6 col-lg-4 bg-white shadow-sm rounded p-4" style="margin-top: 20px;">
        <label for="Username" class="fs-5 text-dark">ชื่อผู้ใช้</label>
        <input type="text" id="Username" name="Username" required class="form-control rounded-pill mb-3" placeholder="กรุณากรอกชื่อผู้ใช้">

        <label for="Password" class="fs-5 text-dark">รหัสผ่าน</label>
        <div class="input-group mb-3">
            <input type="password" id="Password" name="Password" required class="form-control rounded-pill" placeholder="กรุณากรอกรหัสผ่าน">
            <button type="button" class="btn btn-light border rounded-end" onclick="togglePasswordVisibility()">
                <i id="togglePasswordIcon" class="fa fa-eye"></i>
            </button>
        </div>

        <label for="Firstname" class="fs-5 text-dark">ชื่อ</label>
        <input type="text" id="Firstname" name="Firstname" required class="form-control rounded-pill mb-3" placeholder="กรุณากรอกชื่อ">

        <label for="Lastname" class="fs-5 text-dark">นามสกุล</label>
        <input type="text" id="Lastname" name="Lastname" required class="form-control rounded-pill mb-3" placeholder="กรุณากรอกนามสกุล">

        <!-- เพิ่มช่องกรอกอีเมล -->
        <label for="Email" class="fs-5 text-dark">อีเมล</label>
        <input type="email" id="Email" name="Email" required class="form-control rounded-pill mb-3" placeholder="กรุณากรอกอีเมล">
       
        <label for="Phone" class="fs-5 text-dark">เบอร์โทร</label>
        <input type="text" id="Phone" name="Phone" required class="form-control rounded-pill mb-3" placeholder="กรุณากรอกเบอร์โทร">

        <label for="age" class="fs-5 text-dark">อายุ</label>
        <input type="number" id="age" name="age" required class="form-control rounded-pill mb-3" placeholder="กรุณากรอกอายุ">

        <label for="sex" class="fs-5 text-dark">เพศ</label>
        <select id="sex" name="sex" required class="form-control rounded-pill mb-3">
            <option value="" disabled selected>กรุณาเลือกเพศ</option>
            <option value="ชาย">ชาย</option>
            <option value="หญิง">หญิง</option>
            <option value="อื่นๆ">อื่นๆ</option>
        </select>

        <label for="address" class="fs-5 text-dark">ที่อยู่</label>
        <textarea id="address" name="address" required class="form-control rounded mb-3" placeholder="กรุณากรอกที่อยู่" rows="3"></textarea>

        <div class="text-center">
            <button type="submit" class="btn btn-success rounded-pill px-4 my-2">ตกลง</button>
            <button type="reset" class="btn btn-danger rounded-pill px-4 my-2">
                <a class="text-white" href="frmlogin.php" style="text-decoration: none;">ยกเลิก</a>
            </button>
        </div>
    </div>
</form>

<br><br>
<?php
include "footer.php";
?>
</body>
</html>
