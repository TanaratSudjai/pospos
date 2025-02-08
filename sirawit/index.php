<?php
include "navbar.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SOXIS</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.min.js"></script>
    <script defer src="https://use.fontawesome.com/releases/v6.0.0/js/all.js" 
        integrity="sha384-l+HksIGR+lyuyBo1+1zCBSRt6v4yklWu7RbG0Cv+jDLDD9WFcEIwZLHioVB4Wkau" crossorigin="anonymous"></script>
</head>
<body style="background-color: #f8f9fa; font-family: 'Arial', sans-serif;">
<style>
        /* ใช้โทนสีขาว มินิมอล */
        body {
            background-color: #fff; /* พื้นหลังสีขาว */
            font-family: 'Arial', sans-serif;
            color: #333; /* สีตัวอักษร */
        }

        h1, h3 {
            color: #333;
        }

        /* Header image */
        img.d-block {
            width: 100%;
            height: 500px; /* ขนาดรูปภาพให้เล็กลง */
            object-fit: cover; /* ทำให้รูปไม่บิดเบี้ยว */
        }

        /* About Section */
        section#About {
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* เงาให้มินิมอล */
            padding: 30px;
            border-radius: 8px;
        }

        /* Product photo section */
        section#photo {
            background-color: #f8f9fa;
            padding: 50px 0;
        }

        .row.g-4 img {
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1); /* เงารูปภาพเล็กๆ */
            max-width: 100%; /* รูปไม่เกินขนาดที่กำหนด */
            height: auto;
        }

        .col-sm-3 {
            padding: 10px;
        }

        .text-center {
            text-align: center;
        }

        .btn-dark {
            background-color: #333; /* เปลี่ยนสีปุ่ม */
            color: #fff;
        }
    </style>
<!-- Header Image -->
<img src="img\WELCOME.png" width="1920" height="500" class="mx-auto d-block mb-4" alt="Header Image">

<!-- About Section -->
<section id="About" class="text-center py-5 bg-white shadow-sm rounded">
    <div class="container">
        <h1 class="mb-4 text-black"><i class="fa-solid fa-comments"></i> เกี่ยวกับเรา</h1>
        <div class="row text-secondary">
            <div class="col-sm-6 mb-4">
                <h3 class="text-dark"><i class="fa-solid fa-user-group"></i> ข้อมูลร้านค้า</h3>
                <p>So Xis เป็นร้านขายชุดเดรสแฟชั่นที่ตอบโจทย์ทุกสไตล์ สาวๆ ที่ชอบช็อปปิ้งออนไลน์</p>
            </div>
            <div class="col-sm-6 mb-4">
                <h3 class="text-dark"><i class="fa-solid fa-bullhorn"></i> เวลาทำการ</h3>
                <p>เปิดบริการทุกวัน สั่งซื้อได้ตลอด 24 ชั่วโมงผ่านเว็บไซต์ของเรา</p>
            </div>
        </div>
    </div>
</section>

<!-- Product Photo Section -->
<section id="photo" class="mt-5 py-5">
    <div class="container">
        <h1 class="text-center text-black mb-4">ภาพตัวอย่างสินค้า</h1>
        <div class="row g-4">
            <div class="col-sm-3">
                <img src="img/LINE_ALBUM_24167 _1_๒๔๐๑๒๔_18.jpg" class="img-fluid rounded shadow-sm" alt="Product 1">
            </div>
            <div class="col-sm-3">
                <img src="img/LINE_ALBUM_24167 _1_๒๔๐๑๒๔_16.jpg" class="img-fluid rounded shadow-sm" alt="Product 2">
            </div>
            <div class="col-sm-3">
                <img src="img/LINE_ALBUM_24167 _1_๒๔๐๑๒๔_13.jpg" class="img-fluid rounded shadow-sm" alt="Product 3">
            </div>
            <div class="col-sm-3">
                <img src="img/LINE_ALBUM_24167 _1_๒๔๐๑๒๔_9.jpg" class="img-fluid rounded shadow-sm" alt="Product 4">
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<?php include "footer.php"; ?>

</body>
</html>
