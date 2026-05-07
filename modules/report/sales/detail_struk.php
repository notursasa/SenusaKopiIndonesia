<?php
include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/config/database.php';
include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/helpers/functions.php';

if (!isset($_GET['id'])) { echo "ID Transaksi tidak ditemukan"; exit; }
$sales_id = $_GET['id'];

$q_header = "SELECT so.*, c.customer_name, s.staff_username, b.branch_name, b.branch_address 
             FROM SalesOrder so
             JOIN Customer c ON so.customer_id = c.customer_id
             JOIN Staff s ON so.staff_id = s.staff_id
             JOIN Branch b ON s.branch_id = b.branch_id
             WHERE so.sales_id = '$sales_id'";
$header = mysqli_fetch_assoc(mysqli_query($conn, $q_header));

if(!$header) { echo "Data tidak ditemukan"; exit; }

$q_items = "SELECT sod.*, p.product_name 
            FROM SalesOrderDetail sod
            JOIN Product p ON sod.product_id = p.product_id
            WHERE sod.sales_id = '$sales_id'";
$res_items = mysqli_query($conn, $q_items);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Struk #<?php echo $sales_id; ?></title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            background: #eee;
            display: flex;
            justify-content: center;
            padding: 20px;
        }
        .struk-container {
            background: white;
            width: 300px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        
        .divider { border-top: 1px dashed #333; margin: 10px 0; }
        .divider-double { border-top: 3px double #333; margin: 10px 0; }
        
        .item-row { display: flex; justify-content: space-between; margin-bottom: 5px; }
        .item-name { flex: 1; }
        .item-price { white-space: nowrap; }

        @media print {
            body { background: white; padding: 0; }
            .struk-container { box-shadow: none; width: 100%; }
            .no-print { display: none; }
        }

        .btn-print {
            background: #333; color: white; border: none; padding: 10px 20px; 
            cursor: pointer; width: 100%; margin-top: 20px;
        }
    </style>
</head>
<body>

    <div class="struk-container">
        <div class="text-center">
            <h3 style="margin:0;">SENUSA KOPI</h3>
            <small><?php echo $header['branch_name']; ?></small><br>
            <small style="font-size: 0.8rem;"><?php echo $header['branch_address']; ?></small>
        </div>
        
        <div class="divider-double"></div>
        
        <div style="font-size: 0.85rem;">
            <div>No: <?php echo $sales_id; ?></div>
            <div>Tgl: <?php echo date('d/m/Y H:i', strtotime($header['sales_timestamp'])); ?></div>
            <div>Kasir: <?php echo $header['staff_username']; ?></div>
            <div>Cust: <?php echo $header['customer_name']; ?></div>
            <div>Type: <?php echo $header['order_type']; ?></div>
        </div>

        <div class="divider"></div>

        <div style="font-size: 0.9rem;">
            <?php 
            $total = 0;
            while($item = mysqli_fetch_assoc($res_items)) {
                $subtotal = $item['quantity_sold'] * $item['unit_price'];
                $total += $subtotal;
                
                $is_promo = ($item['unit_price'] < 0);
            ?>
                <div class="item-row" style="<?php echo $is_promo ? 'color:red; font-style:italic;' : ''; ?>">
                    <div class="item-name">
                        <?php echo $is_promo ? "(PROMO) " : ""; ?>
                        <?php echo $item['product_name']; ?> 
                        <span style="font-size:0.8rem;">x<?php echo $item['quantity_sold']; ?></span>
                    </div>
                    <div class="item-price">
                        <?php echo number_format($subtotal, 0, ',', '.'); ?>
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="divider"></div>

        <div class="item-row bold" style="font-size: 1.1rem;">
            <div>TOTAL</div>
            <div>Rp <?php echo number_format($total, 0, ',', '.'); ?></div>
        </div>
        
        <div class="item-row" style="font-size: 0.9rem;">
            <div>Status</div>
            <div><?php echo strtoupper($header['payment_status']); ?></div>
        </div>

        <div class="divider-double"></div>

        <div class="text-center" style="font-size: 0.85rem;">
            <p>Terima Kasih<br>#SatuNusaSatuRasa</p>
            <small>Follow us @senusakopi</small>
        </div>

        <button onclick="window.print()" class="no-print btn-print">🖨️ CETAK STRUK</button>
        <button onclick="window.close()" class="no-print btn-print" style="background:#ddd; color:#333; margin-top:10px;">TUTUP</button>
    </div>

</body>
</html>