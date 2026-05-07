<?php
include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/header.php';

$today = date('Y-m-d');

$q_sales = "SELECT 
                COUNT(DISTINCT so.sales_id) as total_trx,
                SUM(sod.quantity_sold * sod.unit_price) as omset_hari_ini,
                SUM(CASE WHEN sod.unit_price > 0 THEN sod.quantity_sold ELSE 0 END) as cup_hari_ini
            FROM SalesOrder so
            JOIN SalesOrderDetail sod ON so.sales_id = sod.sales_id
            WHERE so.order_status = 'Done' AND DATE(so.sales_timestamp) = '$today'";
$sales = mysqli_fetch_assoc(mysqli_query($conn, $q_sales));

$q_low_stock = "SELECT COUNT(*) as total_low FROM BranchStock WHERE stock_quantity <= 1000";
$low_stock = mysqli_fetch_assoc(mysqli_query($conn, $q_low_stock));

$q_recent = "SELECT so.*, c.customer_name 
             FROM SalesOrder so
             JOIN Customer c ON so.customer_id = c.customer_id
             ORDER BY so.sales_timestamp DESC LIMIT 5";
$res_recent = mysqli_query($conn, $q_recent);
?>

<div style="margin-bottom: 20px;">
    <h2>👋 Halo, Semangat Pagi!</h2>
    <p class="text-muted">Berikut adalah ringkasan operasional Senusa Kopi hari ini (<?php echo date('d M Y'); ?>).</p>
</div>

<div style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 30px;">
    
    <div class="card" style="flex: 1; min-width: 250px; background: linear-gradient(135deg, #00704A 0%, #004d33 100%); color: white; margin-bottom: 0;">
        <div style="display:flex; justify-content:space-between; align-items:start;">
            <div>
                <h5 style="margin:0; opacity:0.8;">Omset Hari Ini</h5>
                <h1 style="margin: 10px 0; font-size: 2.2rem; color: white;"><?php echo formatRupiah($sales['omset_hari_ini'] ?? 0); ?></h1>
            </div>
            <i class="fas fa-coins" style="font-size: 3rem; opacity: 0.2;"></i>
        </div>
        <small style="opacity: 0.8;"><?php echo number_format($sales['total_trx']); ?> Transaksi berhasil</small>
    </div>

    <div class="card" style="flex: 1; min-width: 250px; border-left: 5px solid #d4e9e2; margin-bottom: 0;">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <div>
                <h5 style="margin:0; color: #666;">Kopi Terjual</h5>
                <h1 style="margin: 10px 0; color: #333;"><?php echo number_format($sales['cup_hari_ini'] ?? 0); ?> <span style="font-size:1rem;">Cup</span></h1>
            </div>
            <i class="fas fa-mug-hot" style="font-size: 2.5rem; color: #d4e9e2;"></i>
        </div>
    </div>

    <div class="card" style="flex: 1; min-width: 250px; border-left: 5px solid #dc3545; margin-bottom: 0;">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <div>
                <h5 style="margin:0; color: #666;">Stok Menipis</h5>
                <h1 style="margin: 10px 0; color: #dc3545;"><?php echo number_format($low_stock['total_low']); ?> <span style="font-size:1rem;">Item</span></h1>
            </div>
            <i class="fas fa-exclamation-triangle" style="font-size: 2.5rem; color: #f8d7da;"></i>
        </div>
        <?php if($low_stock['total_low'] > 0) { ?>
            <a href="/senusa_kopi/modules/report/stock/index.php" style="font-size: 0.8rem; color: #dc3545; text-decoration: none;">Lihat Laporan Stok &rarr;</a>
        <?php } else { ?>
            <small style="color: green;">Stok aman terkendali.</small>
        <?php } ?>
    </div>
</div>

<div style="display: flex; gap: 20px; flex-wrap: wrap;">
    <div class="card" style="flex: 2; min-width: 300px;">
        <h3><i class="fas fa-history"></i> Transaksi Terakhir</h3>
        <table>
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>No. Order</th>
                    <th>Pelanggan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($res_recent)) { 
                     $status_color = ($row['order_status'] == 'Done') ? '#d4e9e2' : '#fff3cd';
                     $text_color  = ($row['order_status'] == 'Done') ? '#00704A' : '#856404';
                ?>
                    <tr>
                        <td><?php echo date('H:i', strtotime($row['sales_timestamp'])); ?></td>
                        <td><b><?php echo $row['sales_id']; ?></b></td>
                        <td><?php echo $row['customer_name']; ?></td>
                        <td>
                            <span style="background: <?php echo $status_color; ?>; color: <?php echo $text_color; ?>; padding: 2px 8px; border-radius: 4px; font-weight:bold; font-size: 0.75rem;">
                                <?php echo $row['order_status']; ?>
                            </span>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <div style="margin-top: 15px; text-align: right;">
            <a href="/senusa_kopi/modules/transaction/sales/index.php" class="btn btn-secondary" style="font-size: 0.8rem;">Lihat Semua</a>
        </div>
    </div>

    <div class="card" style="flex: 1; min-width: 250px;">
        <h3><i class="fas fa-rocket"></i> Akses Cepat</h3>
        <p class="text-muted">Menu yang sering digunakan:</p>
        
        <div style="display: flex; flex-direction: column; gap: 10px;">
            <a href="/senusa_kopi/modules/transaction/sales/tambah.php" class="btn btn-primary" style="text-align: left; padding: 15px;">
                <i class="fas fa-cash-register" style="margin-right: 10px;"></i> Buka Kasir (POS)
            </a>
            
            <a href="/senusa_kopi/modules/transaction/supply/tambah.php" class="btn btn-secondary" style="text-align: left; padding: 15px;">
                <i class="fas fa-truck" style="margin-right: 10px;"></i> Order Bahan Baku
            </a>
            
            <a href="/senusa_kopi/modules/master/product/index.php" class="btn btn-secondary" style="text-align: left; padding: 15px;">
                <i class="fas fa-coffee" style="margin-right: 10px;"></i> Kelola Menu
            </a>
        </div>
    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/footer.php'; ?>