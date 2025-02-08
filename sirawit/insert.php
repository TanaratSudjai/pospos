<?php
include("connect.php");

// รับค่าจากฟอร์ม
$Username = $_POST['Username'];
$Password = $_POST['Password'];
$Firstname = $_POST['Firstname'];
$Lastname = $_POST['Lastname'];
$Email = $_POST['Email'];
$Phone = $_POST['Phone'];
$Age = $_POST['age'];
$Sex = $_POST['sex'];
$Address = $_POST['address'];

// เก็บระดับผู้ใช้เป็น 'user'
$Userlevel = "user"; 

// สร้าง SQL Query เพื่อบันทึกข้อมูล
$sql = "INSERT INTO tb_users (username, password, firstname, lastname, email, phone, address, role, created_at, updated_at)
        VALUES ('$Username', '$Password', '$Firstname', '$Lastname', '$Email', '$Phone', '$Address', '$Userlevel', NOW(), NOW())";

if (mysqli_query($conn, $sql)) {
    echo "<script>";
    echo "alert('บันทึกข้อมูลเรียบร้อยแล้ว');";
    echo "window.location = 'frmlogin.php';";
    echo "</script>";
} else {
    echo "ERROR: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
