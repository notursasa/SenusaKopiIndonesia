<?php
include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/config/database.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $query = "DELETE FROM ingredient WHERE ingredient_id = '$id'";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Data berhasil dihapus!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data. Kemungkinan data ini sedang digunakan di tabel lain.'); window.location='index.php';</script>";
    }
}
?>