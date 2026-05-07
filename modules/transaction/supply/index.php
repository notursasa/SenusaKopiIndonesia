<?php
include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/header.php';
?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2><i class="fas fa-shopping-cart"></i> Riwayat Pembelian (Supply Order)</h2>
        <a href="tambah.php" class="btn btn-primary">+ Order Baru</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Order</th>
                <th>Tanggal</th>
                <th>Supplier</th>
                <th>Status Order</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
          
            $query = "SELECT so.*, s.supplier_name 
                      FROM SupplyOrder so 
                      JOIN Supplier s ON so.supplier_id = s.supplier_id 
                      ORDER BY so.supply_timestamp DESC";
                      
            $result = mysqli_query($conn, $query);
            $no = 1;

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    
                    $status_color = '#eee'; 
                    $text_color = '#333';

                    if($row['order_status'] == 'Done') {
                        $status_color = '#d4e9e2'; $text_color = '#00704A';
                    } elseif ($row['order_status'] == 'Preparing') {
                        $status_color = '#fff3cd'; $text_color = '#856404';
                    } elseif ($row['order_status'] == 'Waiting For Payment') {
                        $status_color = '#f8d7da'; $text_color = '#721c24';
                    }
            ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><b><?php echo $row['supply_order_id']; ?></b></td>
                    <td><?php echo date('d-M-Y H:i', strtotime($row['supply_timestamp'])); ?></td>
                    <td><?php echo $row['supplier_name']; ?></td>
                    <td>
                        <span style="background: <?php echo $status_color; ?>; color: <?php echo $text_color; ?>; padding: 2px 8px; border-radius: 4px; font-weight:bold; font-size: 0.85rem;">
                            <?php echo $row['order_status']; ?>
                        </span>
                    </td>
                    <td>
          
                        
                        <a href="kelola_item.php?id=<?php echo $row['supply_order_id']; ?>" class="btn btn-secondary" style="font-size: 0.8rem; padding: 5px 10px;">
                            <i class="fas fa-list"></i> Detail
                        </a>

                        <?php if ($row['order_status'] == 'Preparing') { ?>
                            <a href="terima.php?id=<?php echo $row['supply_order_id']; ?>" 
                               class="btn btn-primary" 
                               style="font-size: 0.8rem; padding: 5px 10px;"
                               onclick="return confirm('Apakah barang fisik sudah diterima lengkap? Stok cabang akan bertambah otomatis.');">
                                <i class="fas fa-check-double"></i> Terima
                            </a>
                        <?php } ?>
                    </td>
                </tr>
            <?php 
                }
            } else {
                echo "<tr><td colspan='7' style='text-align:center; padding: 20px;'>Belum ada riwayat pembelian.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/footer.php'; ?>