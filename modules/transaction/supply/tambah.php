<?php
include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/header.php';

$query_sup = "SELECT * FROM Supplier ORDER BY supplier_name ASC";
$res_sup = mysqli_query($conn, $query_sup);

if (isset($_POST['buat_order'])) {
    $supplier_id = cleanInput($_POST['supplier_id']);
    
    $new_id = generateId('SY', 'SupplyOrder', 'supply_order_id');
    
    $order_status = 'Waiting For Payment';
    $payment_status = 'Waiting for Verification';

    $query = "INSERT INTO SupplyOrder (supply_order_id, supplier_id, order_status, payment_status) 
              VALUES ('$new_id', '$supplier_id', '$order_status', '$payment_status')";

    if (mysqli_query($conn, $query)) {
        echo "<script>window.location='kelola_item.php?id=$new_id';</script>";
    } else {
        echo "<script>alert('Gagal: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h3><i class="fas fa-cart-plus"></i> Buat Order Pembelian Baru</h3>
    <p class="text-muted">Langkah 1: Pilih Supplier terlebih dahulu.</p>
    
    <form action="" method="POST">
        <label>Pilih Supplier</label>
        <select name="supplier_id" required style="font-size: 1rem; padding: 12px;">
            <option value="">-- Pilih Supplier --</option>
            <?php while ($sup = mysqli_fetch_assoc($res_sup)) { ?>
                <option value="<?php echo $sup['supplier_id']; ?>">
                    <?php echo $sup['supplier_name']; ?>
                </option>
            <?php } ?>
        </select>
        
        <div style="margin-top: 30px;">
            <button type="submit" name="buat_order" class="btn btn-primary" style="width: 100%;">
                Lanjut ke Pilih Barang <i class="fas fa-arrow-right"></i>
            </button>
            <br><br>
            <a href="index.php" style="display:block; text-align:center; color: #666; text-decoration:none;">Batal</a>
        </div>
    </form>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/footer.php'; ?>