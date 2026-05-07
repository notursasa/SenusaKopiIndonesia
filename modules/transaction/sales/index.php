<?php
include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/header.php';
?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2><i class="fas fa-cash-register"></i> Transaksi Penjualan (POS)</h2>
        <a href="tambah.php" class="btn btn-primary">+ Order Baru</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Transaksi</th>
                <th>Waktu</th>
                <th>Pelanggan</th>
                <th>Tipe</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT so.*, c.customer_name, s.staff_username 
                      FROM SalesOrder so 
                      JOIN Customer c ON so.customer_id = c.customer_id 
                      JOIN Staff s ON so.staff_id = s.staff_id 
                      ORDER BY so.sales_timestamp DESC";
                      
            $result = mysqli_query($conn, $query);
            $no = 1;

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $badge_color = ($row['order_status'] == 'Done') ? '#d4e9e2' : '#fff3cd';
                    $text_color  = ($row['order_status'] == 'Done') ? '#00704A' : '#856404';
            ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><b><?php echo $row['sales_id']; ?></b></td>
                    <td><?php echo date('d/m/y H:i', strtotime($row['sales_timestamp'])); ?></td>
                    <td>
                        <?php echo $row['customer_name']; ?><br>
                        <small class="text-muted">Kasir: <?php echo $row['staff_username']; ?></small>
                    </td>
                    <td><?php echo $row['order_type']; ?></td>
                    <td>
                        <span style="background: <?php echo $badge_color; ?>; color: <?php echo $text_color; ?>; padding: 2px 8px; border-radius: 4px; font-weight:bold; font-size: 0.85rem;">
                            <?php echo $row['order_status']; ?>
                        </span>
                    </td>
                    <td>
                        <a href="kasir.php?id=<?php echo $row['sales_id']; ?>" class="btn btn-secondary" style="font-size: 0.8rem;">
                            <i class="fas fa-utensils"></i> Menu Kasir
                        </a>
                    </td>
                </tr>
            <?php 
                }
            } else {
                echo "<tr><td colspan='7' style='text-align:center; padding: 20px;'>Belum ada transaksi penjualan.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/footer.php'; ?>