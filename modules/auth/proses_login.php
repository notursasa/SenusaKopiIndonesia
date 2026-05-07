<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/config/database.php';
include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/helpers/functions.php';

if (isset($_POST['login'])) {
    $username = cleanInput($_POST['username']);
    $password = cleanInput($_POST['password']);

    $query = "SELECT * FROM Staff WHERE staff_username = '$username' AND staff_password = '$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        $_SESSION['staff_id']   = $data['staff_id'];
        $_SESSION['username']   = $data['staff_username'];
        $_SESSION['role']       = $data['staff_role'];
        $_SESSION['branch_id']  = $data['branch_id']; 
        
        header("Location: /senusa_kopi/modules/dashboard/index.php");
    } else {
        header("Location: login.php?error=Username atau Password Salah!");
    }
}
?>