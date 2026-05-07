<?php
include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/config/database.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $query_cek = "SELECT product_image FROM Product WHERE product_id = '$id'";
    $result_cek = mysqli_query($conn, $query_cek);
    $data = mysqli_fetch_assoc($result_cek);

    $query = "DELETE FROM Product WHERE product_id = '$id'";
    
    if (mysqli_query($conn, $query)) {
        if (!empty($data['product_image'])) {
            $path = $_SERVER['DOCUMENT_ROOT'] . "/senusa_kopi/uploads/products/" . $data['product_image'];
            if (file_exists($path)) {
                unlink($path); 
            }
        }
        
        echo "<script>alert('Produk berhasil dihapus!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus: " . mysqli_error($conn) . "'); window.location='index.php';</script>";
    }
}
?>