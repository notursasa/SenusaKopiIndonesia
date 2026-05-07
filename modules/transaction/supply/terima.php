<?php
include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/config/database.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id_order = $_GET['id'];

$cek_query = "SELECT order_status FROM SupplyOrder WHERE supply_order_id = '$id_order'";
$cek_res   = mysqli_query($conn, $cek_query);
$data_order = mysqli_fetch_assoc($cek_res);

if ($data_order['order_status'] != 'Preparing') {
    echo "<script>alert('Status order tidak valid untuk diterima!'); window.location='index.php';</script>";
    exit;
}


mysqli_begin_transaction($conn);

try {
    $query_items = "SELECT * FROM SupplyOrderDetail WHERE supply_order_id = '$id_order'";
    $res_items   = mysqli_query($conn, $query_items);

    while ($item = mysqli_fetch_assoc($res_items)) {
        $cabang = $item['branch_id'];
        $bahan  = $item['ingredient_id'];
        $qty    = $item['quantity_bought'];

        $query_stok = "INSERT INTO BranchStock (branch_id, ingredient_id, stock_quantity) 
                       VALUES ('$cabang', '$bahan', $qty) 
                       ON DUPLICATE KEY UPDATE stock_quantity = stock_quantity + $qty";
        
        if (!mysqli_query($conn, $query_stok)) {
            throw new Exception("Gagal update stok: " . mysqli_error($conn));
        }
    }

    $query_update = "UPDATE SupplyOrder SET order_status = 'Done' WHERE supply_order_id = '$id_order'";
    if (!mysqli_query($conn, $query_update)) {
        throw new Exception("Gagal update status order.");
    }

    mysqli_commit($conn);

    echo "<script>alert('Barang berhasil diterima! Stok cabang telah diperbarui.'); window.location='index.php';</script>";

} catch (Exception $e) {
    mysqli_rollback($conn);
    echo "<script>alert('Terjadi Kesalahan: " . $e->getMessage() . "'); window.location='index.php';</script>";
}
?>