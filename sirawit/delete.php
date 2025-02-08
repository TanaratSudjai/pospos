<?php
include "connect.php";

// รับค่า ID จากฟอร์ม
$ID = $_POST['ID'];

// คำสั่ง SQL สำหรับลบข้อมูลผู้ใช้จากตาราง users
$sql = "DELETE FROM `tb_users` WHERE `user_id` = '$ID'";

// ตรวจสอบการดำเนินการคำสั่ง SQL
if(mysqli_query($conn, $sql)){
    echo "<script language=\"javascript\">";
    echo "alert('ลบข้อมูลเรียบร้อยแล้ว');"; // แสดงข้อความเมื่อทำการลบสำเร็จ
    echo "window.location = 'admin_page.php';"; // เปลี่ยนเส้นทางไปหน้า admin_page.php
    echo "</script>";
}else{
    echo "ERROR: Delete failed " . mysqli_error($conn); // แสดงข้อความหากเกิดข้อผิดพลาด
}

mysqli_close($conn); // ปิดการเชื่อมต่อกับฐานข้อมูล
?>
