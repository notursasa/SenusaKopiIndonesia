<?php
include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/header.php';

$tgl_awal = isset($_GET['tgl_awal']) ? $_GET['tgl_awal'] : date('Y-m-d');
$tgl_akhir = isset($_GET['tgl_akhir']) ? $_GET['tgl_akhir'] : date('Y-m-d');

$q_summary = "SELECT 
                COUNT(DISTINCT so.sales_id) as total_transaksi,
                SUM(sod.quantity_sold * sod.unit_price) as total_omset,
                SUM(CASE WHEN sod.unit_price > 0 THEN sod.quantity_sold ELSE 0 END) as total_cup
              FROM SalesOrder so
              JOIN SalesOrderDetail sod ON so.sales_id = sod.sales_id
              WHERE so.order_status = 'Done' 
              AND DATE(so.sales_timestamp) BETWEEN '$tgl_awal' AND '$tgl_akhir'";

$summary = mysqli_fetch_assoc(mysqli_query($conn, $q_summary));


$q_detail = "SELECT so.*, c.customer_name, s.staff_username, 
             (SELECT SUM(quantity_sold * unit_price) FROM SalesOrderDetail WHERE sales_id = so.sales_id) as subtotal
             FROM SalesOrder so
             JOIN Customer c ON so.customer_id = c.customer_id
             JOIN Staff s ON so.staff_id = s.staff_id
             WHERE so.order_status = 'Done'
             AND DATE(so.sales_timestamp) BETWEEN '$tgl_awal' AND '$tgl_akhir'
             ORDER BY so.sales_timestamp DESC";

$res_detail = mysqli_query($conn, $q_detail);
?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2><i class="fas fa-chart-line"></i> Laporan Penjualan</h2>
        
        <form action="" method="GET" style="display: flex; gap: 10px; align-items: center; background: #f8f9fa; padding: 10px; border-radius: 8px;">
            <label style="margin:0;">Dari:</label>
            <input type="date" name="tgl_awal" value="<?php echo $tgl_awal; ?>" style="margin:0; padding: 5px; width: auto;">
            <label style="margin:0;">S/d:</label>
            <input type="date" name="tgl_akhir" value="<?php echo $tgl_akhir; ?>" style="margin:0; padding: 5px; width: auto;">
            <button type="submit" class="btn btn-primary" style="padding: 6px 15px; font-size: 0.9rem;">Filter</button>
        </form>
    </div>

    <div style="display: flex; gap: 20px; margin-bottom: 30px;">
        <div style="flex: 1; background: var(--primary-color); color: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <h4 style="margin:0; opacity: 0.9;">Total Omset</h4>
            <h1 style="margin: 10px 0; font-size: 2rem; color: white;"><?php echo formatRupiah($summary['total_omset'] ?? 0); ?></h1>
            <small>Periode: <?php echo date('d M', strtotime($tgl_awal)); ?> - <?php echo date('d M', strtotime($tgl_akhir)); ?></small>
        </div>

        <div style="flex: 1; background: white; border: 1px solid #ddd; padding: 20px; border-radius: 12px;">
            <h4 style="margin:0; color: #666;">Total Transaksi</h4>
            <h1 style="margin: 10px 0; color: var(--text-color);"><?php echo number_format($summary['total_transaksi'] ?? 0); ?></h1>
            <small class="text-muted">Struk Terbit</small>
        </div>

        <div style="flex: 1; background: white; border: 1px solid #ddd; padding: 20px; border-radius: 12px;">
            <h4 style="margin:0; color: #666;">Produk Terjual</h4>
            <h1 style="margin: 10px 0; color: var(--text-color);"><?php echo number_format($summary['total_cup'] ?? 0); ?></h1>
            <small class="text-muted">Item / Cup (Exclude Promo)</small>
        </div>
    </div>

    <h3 style="border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 15px;">Rincian Transaksi</h3>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Waktu</th>
                <th>No. Struk</th>
                <th>Pelanggan</th>
                <th>Kasir</th>
                <th>Total Belanja</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            if (mysqli_num_rows($res_detail) > 0) {
                while ($row = mysqli_fetch_assoc($res_detail)) {
            ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($row['sales_timestamp'])); ?></td>
                    <td><b><?php echo $row['sales_id']; ?></b></td>
                    <td><?php echo $row['customer_name']; ?></td>
                    <td><?php echo $row['staff_username']; ?></td>
                    <td style="font-weight: bold;"><?php echo formatRupiah($row['subtotal']); ?></td>
                    <td>
                        <a href="detail_struk.php?id=<?php echo $row['sales_id']; ?>" target="_blank" class="btn btn-secondary" style="font-size: 0.8rem;">
                            <i class="fas fa-receipt"></i> Struk
                        </a>
                    </td>
                </tr>
            <?php 
                }
            } else {
                echo "<tr><td colspan='7' style='text-align:center; padding: 20px;'>Tidak ada data penjualan.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/footer.php'; ?>