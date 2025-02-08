<?php
session_start();

// ตรวจสอบว่า session มีอยู่หรือไม่
if(isset($_SESSION["UserID"])) {
    // ทำการทำลาย session และรีไดเร็กไปหน้า index.php
    session_destroy();
    header("Location: index.php"); // เปลี่ยนหน้าไปยัง index.php
    exit();
} else {
    // ถ้าไม่มี session ก็รีไดเร็กไปหน้าแรกเลย
    header("Location: index.php");
    exit();
}
?>


