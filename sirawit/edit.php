<?php 
include("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าจากฟอร์ม
    $ID = $_POST['ID']; // รับค่า user_id จากฟอร์ม
    $Username = $_POST['Username']; // รับค่า Username
    $Password = $_POST['Password']; // รับค่า Password
    $Firstname = $_POST['Firstname']; // รับค่า Firstname
    $Lastname = $_POST['Lastname']; // รับค่า Lastname
    $Email = $_POST['Email']; // รับค่า Email
    $Phone = $_POST['Phone']; // รับค่า Phone
    $Address = $_POST['Address']; // รับค่า Address

    // คำสั่ง SQL สำหรับอัพเดตข้อมูลในฐานข้อมูล
    $sql = "UPDATE tb_users 
            SET username='$Username', password='$Password', firstname='$Firstname', lastname='$Lastname', email='$Email', phone='$Phone', address='$Address' 
            WHERE user_id='$ID'"; // อัปเดตข้อมูลที่ตรงกับ user_id

    // ตรวจสอบการอัปเดต
    if (mysqli_query($conn, $sql)) {
        echo "<script language=\"javascript\">";
        echo "alert('ข้อมูลได้รับการอัปเดตเรียบร้อยแล้ว');"; // แสดงข้อความเมื่อการอัปเดตสำเร็จ
        echo "window.location = 'admin_page.php';"; // เปลี่ยนเส้นทางไปยังหน้า admin_page.php
        echo "</script>";
    } else {
        echo "ERROR: " . mysqli_error($conn); // แสดงข้อผิดพลาดหากเกิดปัญหา
    }
}

mysqli_close($conn); // ปิดการเชื่อมต่อกับฐานข้อมูล
?>
