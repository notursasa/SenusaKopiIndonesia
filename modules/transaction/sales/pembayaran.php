<?php
include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/header.php';

if (!isset($_GET['id'])) { header("Location: index.php"); exit; }
$sales_id = $_GET['id'];

$q_total = mysqli_query($conn, "SELECT SUM(quantity_sold * unit_price) as grand_total FROM SalesOrderDetail WHERE sales_id='$sales_id'");
$d_total = mysqli_fetch_assoc($q_total);
$grand_total = $d_total['grand_total'];

if (isset($_POST['bayar'])) {
    $cust_option = $_POST['cust_option'];
    $final_cust_id = '';

    if ($cust_option == 'existing') {
        $final_cust_id = cleanInput($_POST['customer_id_existing']);
    } else {
        $nama_baru  = cleanInput($_POST['new_name']);
        $hp_baru    = !empty($_POST['new_phone']) ? cleanInput($_POST['new_phone']) : '-';
        $email_baru = !empty($_POST['new_email']) ? cleanInput($_POST['new_email']) : '-';
        
        $new_cust_id = generateId('CU', 'Customer', 'customer_id');
        $q_new_cust = "INSERT INTO Customer (customer_id, customer_name, customer_phone, customer_email) 
                       VALUES ('$new_cust_id', '$nama_baru', '$hp_baru', '$email_baru')";
        
        if (!mysqli_query($conn, $q_new_cust)) {
             echo "<script>alert('Gagal buat customer: " . mysqli_error($conn) . "');</script>"; exit;
        }
        $final_cust_id = $new_cust_id;
    }

    $q_update = "UPDATE SalesOrder SET 
                 customer_id = '$final_cust_id',
                 order_status = 'Done',
                 payment_status = 'Successful' 
                 WHERE sales_id = '$sales_id'";

    if (mysqli_query($conn, $q_update)) {
        $q_branch = mysqli_query($conn, "SELECT s.branch_id FROM SalesOrder so JOIN Staff s ON so.staff_id = s.staff_id WHERE so.sales_id = '$sales_id'");
        $d_branch = mysqli_fetch_assoc($q_branch);
        $branch_id = $d_branch['branch_id'];

        $q_items = mysqli_query($conn, "SELECT product_id, SUM(quantity_sold) as total_qty 
                                        FROM SalesOrderDetail 
                                        WHERE sales_id = '$sales_id' 
                                        AND unit_price > 0  
                                        GROUP BY product_id");

        while ($item = mysqli_fetch_assoc($q_items)) {
            $prod_id  = $item['product_id'];
            $qty_sold = $item['total_qty'];

            $q_recipe = mysqli_query($conn, "SELECT ingredient_id, required_quantity FROM ProductRecipe WHERE product_id = '$prod_id'");

            while ($resep = mysqli_fetch_assoc($q_recipe)) {
                $ing_id      = $resep['ingredient_id'];
                $takaran     = $resep['required_quantity'];
                
                $total_pakai = $qty_sold * $takaran;

                $q_potong = "UPDATE BranchStock SET stock_quantity = stock_quantity - $total_pakai 
                             WHERE branch_id = '$branch_id' AND ingredient_id = '$ing_id'";
                
                mysqli_query($conn, $q_potong);
            }
        }
        
        

        echo "<script>alert('Transaksi Berhasil! Stok bahan baku telah dikurangi otomatis.'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal update transaksi: ".mysqli_error($conn)."');</script>";
    }
}
?>

<div class="card" style="max-width: 600px; margin: 20px auto;">
    <h2 style="text-align:center; color:var(--primary-color);">Checkout & Pembayaran</h2>
    <div style="background:#f8f9fa; padding:20px; border-radius:10px; text-align:center; margin-bottom:20px;">
        <small>Total Tagihan</small>
        <h1 style="margin:0;"><?php echo formatRupiah($grand_total); ?></h1>
    </div>

    <form action="" method="POST">
        <h4>Data Pelanggan</h4>
        
        <div style="display:flex; gap:10px; margin-bottom:15px;">
            <label style="flex:1; cursor:pointer;">
                <input type="radio" name="cust_option" value="new" checked onclick="toggleCust('new')"> Pelanggan Baru / Walk-in
            </label>
            <label style="flex:1; cursor:pointer;">
                <input type="radio" name="cust_option" value="existing" onclick="toggleCust('existing')"> Member Terdaftar
            </label>
        </div>

        <div id="form-new" style="border:1px solid #ddd; padding:15px; border-radius:8px;">
            <label>Nama Pelanggan (Wajib)</label>
            <input type="text" name="new_name" placeholder="Misal: Kak Rara" required>
            
            <label>No. HP (Opsional)</label>
            <input type="text" name="new_phone" placeholder="08...">
            
            <label>Email (Opsional)</label>
            <input type="email" name="new_email" placeholder="@...">
        </div>

        <div id="form-existing" style="display:none; border:1px solid #ddd; padding:15px; border-radius:8px;">
            <label>Cari Member</label>
            <select name="customer_id_existing" style="width:100%; padding:10px;">
                <?php 
                $custs = mysqli_query($conn, "SELECT * FROM Customer WHERE customer_id != 'CU00000000000' ORDER BY customer_name ASC");
                while($c = mysqli_fetch_assoc($custs)) { 
                ?>
                    <option value="<?php echo $c['customer_id']; ?>">
                        <?php echo $c['customer_name']; ?> - <?php echo $c['customer_phone']; ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div style="margin-top:30px;">
            <button type="submit" name="bayar" class="btn btn-primary" style="width:100%; padding:15px; font-size:1.1rem;">
                <i class="fas fa-check-circle"></i> KONFIRMASI PEMBAYARAN
            </button>
            <br><br>
            <a href="kasir.php?id=<?php echo $sales_id; ?>" style="display:block; text-align:center; color:#666;">Kembali ke Menu</a>
        </div>
    </form>
</div>

<script>
function toggleCust(type) {
    if(type == 'new') {
        document.getElementById('form-new').style.display = 'block';
        document.getElementById('form-existing').style.display = 'none';
        
        document.getElementsByName('new_name')[0].required = true;
    } else {
        document.getElementById('form-new').style.display = 'none';
        document.getElementById('form-existing').style.display = 'block';
        
        document.getElementsByName('new_name')[0].required = false;
    }
}
</script>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/footer.php'; ?>