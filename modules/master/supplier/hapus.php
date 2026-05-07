<?php
include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/config/database.php';
$id = $_GET['id'];
$query = "DELETE FROM Supplier WHERE supplier_id = '$id'";
if (mysqli_query($conn, $query)) {
    echo "<script>window.location='index.php';</script>";
}
?> 