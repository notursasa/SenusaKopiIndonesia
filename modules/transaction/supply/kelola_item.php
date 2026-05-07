<?php
include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/header.php';

if (!isset($_GET['id'])) { header("Location: index.php"); exit; }
$id_order = $_GET['id'];

$query_header = "SELECT so.*, s.supplier_name 
                 FROM SupplyOrder so 
                 JOIN Supplier s ON so.supplier_id = s.supplier_id 
                 WHERE so.supply_order_id = '$id_order'";
$res_header = mysqli_query($conn, $query_header);
$header = mysqli_fetch_assoc($res_header);

$ingredients = [];
$q_ing = mysqli_query($conn, "SELECT * FROM Ingredient ORDER BY ingredient_name ASC");
while ($row = mysqli_fetch_assoc($q_ing)) {
    $ingredients[] = $row;
}

$opt_cabang = mysqli_query($conn, "SELECT * FROM Branch ORDER BY branch_name ASC");

if (isset($_POST['tambah_item'])) {
    $ingredient_id = $_POST['ingredient_id'];
    $branch_id     = $_POST['branch_id'];
    $qty           = $_POST['quantity_bought'];
    
    $input_price   = $_POST['input_price'];    
    $price_per     = $_POST['price_basis'];    
    
    $final_unit_price = $input_price / $price_per;

    $cek = mysqli_query($conn, "SELECT * FROM SupplyOrderDetail WHERE supply_order_id='$id_order' AND ingredient_id='$ingredient_id' AND branch_id='$branch_id'");

    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Item ini untuk cabang tersebut sudah ada. Hapus dulu jika ingin ubah.');</script>";
    } else {
        $query_insert = "INSERT INTO SupplyOrderDetail (supply_order_id, branch_id, ingredient_id, quantity_bought, unit_price) 
                         VALUES ('$id_order', '$branch_id', '$ingredient_id', '$qty', '$final_unit_price')";
        
        if (mysqli_query($conn, $query_insert)) {
            echo "<script>window.location='kelola_item.php?id=$id_order';</script>";
        } else {
            echo "<script>alert('Gagal: " . mysqli_error($conn) . "');</script>";
        }
    }
}

if (isset($_GET['hapus_item']) && isset($_GET['cabang'])) {
    $ing_id = $_GET['hapus_item'];
    $br_id  = $_GET['cabang'];
    mysqli_query($conn, "DELETE FROM SupplyOrderDetail WHERE supply_order_id='$id_order' AND ingredient_id='$ing_id' AND branch_id='$br_id'");
    echo "<script>window.location='kelola_item.php?id=$id_order';</script>";
}

if (isset($_POST['finalisasi'])) {
    mysqli_query($conn, "UPDATE SupplyOrder SET order_status='Preparing' WHERE supply_order_id='$id_order'");
    echo "<script>alert('Order berhasil dikirim ke Supplier!'); window.location='index.php';</script>";
}

if (isset($_POST['batalkan_transaksi'])) {
    mysqli_query($conn, "UPDATE SupplyOrder SET order_status='Canceled' WHERE supply_order_id='$id_order'");
    echo "<script>alert('Transaksi berhasil dibatalkan.'); window.location='index.php';</script>";
}
?>

<div class="card" style="border-left: 5px solid var(--primary-color);">
    <div style="display:flex; justify-content:space-between;">
        <div>
            <h4 style="margin:0;">Order #<?php echo $header['supply_order_id']; ?></h4>
            <small class="text-muted"><?php echo date('d M Y, H:i', strtotime($header['supply_timestamp'])); ?></small>
        </div>
        <div style="text-align:right;">
            <h4 style="margin:0;"><?php echo $header['supplier_name']; ?></h4>
            <span style="background:#eee; padding:2px 8px; border-radius:4px; font-size:0.8rem;">Status: <?php echo $header['order_status']; ?></span>
        </div>
    </div>
</div>

<?php if ($header['order_status'] == 'Waiting For Payment') { ?>
<div class="card">
    <h4 style="margin-top:0;"><i class="fas fa-plus-circle"></i> Tambah Barang Belanjaan</h4>
    <form action="" method="POST" style="display: flex; gap: 15px; flex-wrap: wrap; align-items: flex-end;">
        
        <div style="flex: 2; min-width: 200px;">
            <label>Bahan Baku</label>
            <select name="ingredient_id" id="ingredientSelect" required style="margin-bottom:0;" onchange="updateUnitLabel()">
                <option value="" data-unit="-">-- Pilih Bahan --</option>
                <?php foreach ($ingredients as $ing) { ?>
                    <option value="<?php echo $ing['ingredient_id']; ?>" data-unit="<?php echo $ing['unit']; ?>">
                        <?php echo $ing['ingredient_name']; ?> (<?php echo $ing['unit']; ?>)
                    </option>
                <?php } ?>
            </select>
        </div>

        <div style="flex: 2; min-width: 200px;">
            <label>Tujuan Cabang</label>
            <select name="branch_id" required style="margin-bottom:0;">
                <option value="">-- Pilih Cabang --</option>
                <?php mysqli_data_seek($opt_cabang, 0); while($c = mysqli_fetch_assoc($opt_cabang)) { ?>
                    <option value="<?php echo $c['branch_id']; ?>"><?php echo $c['branch_name']; ?></option>
                <?php } ?>
            </select>
        </div>

        <div style="flex: 1; min-width: 120px;">
            <label>Qty Masuk Gudang</label>
            <div style="display:flex; align-items:center;">
                <input type="number" name="quantity_bought" placeholder="1000" required style="margin-bottom:0;">
                <span id="qtyUnitLabel" style="background:#eee; padding:10px; border:1px solid #ced4da; border-left:0; border-radius:0 8px 8px 0;">-</span>
            </div>
        </div>

        <div style="flex-basis: 100%; height: 1px; background: #eee; margin: 10px 0;"></div>

        <div style="flex: 3; display: flex; gap: 10px; align-items: flex-end; background: #f8f9fa; padding: 15px; border-radius: 8px;">
            <div style="flex: 2;">
                <label>Nominal Harga Beli</label>
                <input type="number" name="input_price" placeholder="Contoh: 17000" required style="margin-bottom:0;">
            </div>
            
            <div style="padding-bottom: 10px; font-weight: bold;">
                per
            </div>

            <div style="flex: 1;">
                <label>Setiap...</label>
                <input type="number" name="price_basis" value="1" required style="margin-bottom:0;">
            </div>
            
            <div style="padding-bottom: 10px;">
                <span id="priceUnitLabel" style="font-weight:bold;">Unit</span>
            </div>
        </div>

        <div style="flex: 1;">
            <button type="submit" name="tambah_item" class="btn btn-primary" style="width:100%; height: 50px;">+ Simpan</button>
        </div>
    </form>
    
    <small class="text-muted" style="display:block; margin-top:10px;">
        <i class="fas fa-info-circle"></i> <b>Contoh Susu:</b> Qty Masuk Gudang = <b>1000</b>. Harga Beli = <b>17000</b> per <b>1000</b> ml.
        <br>
        <i class="fas fa-info-circle"></i> <b>Contoh Telur:</b> Qty Masuk Gudang = <b>10</b>. Harga Beli = <b>2000</b> per <b>1</b> pcs.
    </small>
</div>
<?php } ?>

<div class="card">
    <h3>Daftar Item Belanja</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Bahan (Satuan)</th>
                <th>Tujuan</th>
                <th>Qty Masuk</th>
                <th>Harga Dasar</th>
                <th>Total Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query_detail = "SELECT sod.*, i.ingredient_name, i.unit, b.branch_name 
                             FROM SupplyOrderDetail sod
                             JOIN Ingredient i ON sod.ingredient_id = i.ingredient_id
                             JOIN Branch b ON sod.branch_id = b.branch_id
                             WHERE sod.supply_order_id = '$id_order'";
            $res_detail = mysqli_query($conn, $query_detail);
            $no = 1;
            $grand_total = 0;

            if (mysqli_num_rows($res_detail) > 0) {
                while ($row = mysqli_fetch_assoc($res_detail)) {
                    $subtotal = $row['quantity_bought'] * $row['unit_price'];
                    $grand_total += $subtotal;
            ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><b><?php echo $row['ingredient_name']; ?></b></td>
                    <td><?php echo $row['branch_name']; ?></td>
                    <td>
                        <?php echo number_format($row['quantity_bought']); ?> 
                        <small><?php echo $row['unit']; ?></small>
                    </td>
                    <td>
                        <?php echo formatRupiah($row['unit_price']); ?> 
                        <small class="text-muted">/ <?php echo $row['unit']; ?></small>
                    </td>
                    <td style="font-weight:bold; color:var(--primary-color);"><?php echo formatRupiah($subtotal); ?></td>
                    <td>
                        <?php if ($header['order_status'] == 'Waiting For Payment') { ?>
                        <a href="kelola_item.php?id=<?php echo $id_order; ?>&hapus_item=<?php echo $row['ingredient_id']; ?>&cabang=<?php echo $row['branch_id']; ?>" 
                           class="btn btn-danger" style="font-size:0.7rem; padding: 4px 8px;"
                           onclick="return confirm('Hapus?');">X</a>
                        <?php } else { echo "-"; } ?>
                    </td>
                </tr>
            <?php 
                } 
            } else {
                echo "<tr><td colspan='7' style='text-align:center;'>Keranjang masih kosong.</td></tr>";
            }
            ?>
        </tbody>
        <tfoot>
            <tr style="background-color: #f8f9fa; font-weight:bold;">
                <td colspan="5" style="text-align:right;">Grand Total:</td>
                <td colspan="2" style="font-size: 1.1rem;"><?php echo formatRupiah($grand_total); ?></td>
            </tr>
        </tfoot>
    </table>

    <div style="margin-top: 30px; text-align: right;">
        <a href="index.php" class="btn btn-secondary">Kembali</a>
        
        <?php if ($header['order_status'] == 'Waiting For Payment') { ?>
          
            <form action="" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan order ini? Status akan berubah menjadi Canceled.');">
                <button type="submit" name="batalkan_transaksi" class="btn btn-danger" style="margin-left: 10px; background-color: #dc3545; color: white;">
                    <i class="fas fa-times-circle"></i> Batalkan Transaksi
                </button>
            </form>

            <?php if (mysqli_num_rows($res_detail) > 0) { ?>
                <form action="" method="POST" style="display:inline;" onsubmit="return confirm('Selesaikan order ini?');">
                    <button type="submit" name="finalisasi" class="btn btn-primary" style="margin-left: 10px;">
                        <i class="fas fa-paper-plane"></i> Finalisasi Order
                    </button>
                </form>
            <?php } ?>
        <?php } ?>
    </div>
</div>

<script>
function updateUnitLabel() {
    var select = document.getElementById("ingredientSelect");
    var selectedOption = select.options[select.selectedIndex];
    var unit = selectedOption.getAttribute("data-unit");
    

    document.getElementById("qtyUnitLabel").innerText = unit;
    document.getElementById("priceUnitLabel").innerText = unit;
}
</script>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/footer.php'; ?>