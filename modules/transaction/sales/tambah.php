<?php
include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/header.php';

$opt_staff = mysqli_query($conn, "SELECT * FROM Staff WHERE staff_role='Cashier' OR staff_role='Admin' ORDER BY staff_username ASC");

if (isset($_POST['mulai_pos'])) {
    $staff_id  = cleanInput($_POST['staff_id']);
    $type      = cleanInput($_POST['order_type']);
    
    $cust_id   = 'CU00000000000'; 
    
    $new_id = generateId('SO', 'SalesOrder', 'sales_id');
    
    $status_order = 'Preparing';
    $status_bayar = 'Waiting for Verification';

    $query = "INSERT INTO SalesOrder (sales_id, customer_id, staff_id, order_type, order_status, payment_status) 
              VALUES ('$new_id', '$cust_id', '$staff_id', '$type', '$status_order', '$status_bayar')";

    if (mysqli_query($conn, $query)) {
        echo "<script>window.location='kasir.php?id=$new_id';</script>";
    } else {
        echo "<script>alert('Gagal: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<div class="card" style="max-width: 400px; margin: 50px auto; text-align: center;">
    <h3><i class="fas fa-cash-register"></i> Point of Sales</h3>
    <p class="text-muted">Mulai transaksi baru</p>
    
    <form action="" method="POST">
        <input type="hidden" name="staff_id" value="<?php echo $_SESSION['staff_id']; ?>">

        <div style="text-align: left; margin-bottom: 20px; background: #e9ecef; padding: 15px; border-radius: 8px; border-left: 5px solid var(--primary-color);">
            <label style="font-size:0.85rem; color:#6c757d; display:block; margin-bottom:5px;">Kasir Bertugas</label>
            <b style="font-size:1.1rem; color:#212529;">
                <i class="fas fa-id-badge" style="margin-right:5px;"></i> 
                <?php echo $_SESSION['username']; ?>
            </b>
        </div>
        
        <label>Tipe Pesanan</label>
        <div style="display: flex; gap: 10px; margin-bottom: 30px;">
            <label style="cursor:pointer; background:#f8f9fa; padding:15px; border:1px solid #ddd; border-radius:8px; flex:1; font-weight:bold;">
                <input type="radio" name="order_type" value="Dine In" checked> 🍽️<br>Dine In
            </label>
            <label style="cursor:pointer; background:#f8f9fa; padding:15px; border:1px solid #ddd; border-radius:8px; flex:1; font-weight:bold;">
                <input type="radio" name="order_type" value="Take Out"> 🥡<br>Take Out
            </label>
        </div>
        
        <button type="submit" name="mulai_pos" class="btn btn-primary" style="width: 100%; padding: 15px; font-size: 1.1rem;">
            BUKA KASIR <i class="fas fa-arrow-right"></i>
        </button>
    </form>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/footer.php'; ?>