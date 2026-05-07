<?php
include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/config/database.php';
$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM Customer WHERE customer_id = '$id'");
header("Location: index.php");
?>