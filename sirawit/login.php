<?php
session_start();

if (isset($_POST['Username'])) {
    include("connect.php");

    // รับข้อมูลจากฟอร์ม
    $Username = $_POST['Username'];
    $Password = $_POST['Password'];

    // ค้นหาผู้ใช้จากฐานข้อมูล
    $sql = "SELECT * FROM tb_users WHERE username = '$Username' AND password = '$Password'";
    $result = mysqli_query($conn, $sql);

    // ตรวจสอบว่ามีผู้ใช้หรือไม่
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result);

        // เก็บข้อมูลผู้ใช้ใน session
        $_SESSION["UserID"] = $row["user_id"];
        $_SESSION["Username"] = $row["username"]; // ชื่อผู้ใช้ (จากฟิลด์ 'username')
        $_SESSION["Userlevel"] = $row["role"]; // ใช้ "role" แทน "Userlevel"
        $_SESSION["Firstname"] = $row["firstname"]; // ชื่อจริง
        $_SESSION["Lastname"] = $row["lastname"]; // นามสกุล

        // ตรวจสอบระดับผู้ใช้
        if ($_SESSION["Userlevel"] == "admin") {
            // ถ้าเป็นผู้ดูแลระบบ
            Header("Location: admin_page.php"); // ไปที่หน้า admin_page
        } elseif ($_SESSION["Userlevel"] == "user") {
            // ถ้าเป็นผู้ใช้งาน
            Header("Location: user_page.php"); // ไปที่หน้า user_page
        }
    } else {
        // ถ้าไม่พบผู้ใช้
        echo "<script>";
        echo "alert(\"ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง\");";
        echo "window.history.back()";
        echo "</script>";
    }
} else {
    // ถ้าไม่มีการส่งข้อมูลจากฟอร์ม
    Header("Location: frmlogin.php"); // ถ้าไม่มีการส่งข้อมูลจะไปที่หน้าล็อกอิน
}
?>

