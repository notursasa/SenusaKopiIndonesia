<?php
include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/config/database.php';
$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM Staff WHERE staff_id = '$id'");
header("Location: index.php");
?>