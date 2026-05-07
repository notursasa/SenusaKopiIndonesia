<?php
include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/config/database.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $query = "DELETE FROM ProductCategory WHERE category_id = '$id'";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Kategori berhasil dihapus!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data: " . mysqli_error($conn) . "'); window.location='index.php';</script>";
    }
}
?>