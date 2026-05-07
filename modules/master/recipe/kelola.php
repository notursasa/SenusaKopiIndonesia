<?php
include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/header.php';

if (!isset($_GET['id'])) { header("Location: index.php"); exit; }
$product_id = $_GET['id'];

$query_prod = "SELECT * FROM Product WHERE product_id = '$product_id'";
$res_prod = mysqli_query($conn, $query_prod);
$produk = mysqli_fetch_assoc($res_prod);

$query_ing = "SELECT * FROM Ingredient ORDER BY ingredient_name ASC";
$res_ing = mysqli_query($conn, $query_ing);

if (isset($_POST['tambah_bahan'])) {
    $ingredient_id = $_POST['ingredient_id'];
    $qty           = $_POST['required_quantity'];
    $satuan        = $_POST['unit'];
    
    $cek = mysqli_query($conn, "SELECT * FROM ProductRecipe WHERE product_id='$product_id' AND ingredient_id='$ingredient_id'");
    
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Bahan ini sudah ada di resep! Hapus dulu jika ingin mengubah.');</script>";
    } else {
        $query_insert = "INSERT INTO ProductRecipe (product_id, ingredient_id, required_quantity, unit) 
                         VALUES ('$product_id', '$ingredient_id', '$qty', '$satuan')";
        
        if (mysqli_query($conn, $query_insert)) {
            echo "<script>alert('Bahan berhasil ditambahkan!'); window.location='kelola.php?id=$product_id';</script>";
        } else {
            echo "<script>alert('Gagal: " . mysqli_error($conn) . "');</script>";
        }
    }
}

if (isset($_GET['hapus_ing'])) {
    $ing_id_hapus = $_GET['hapus_ing'];
    $query_del = "DELETE FROM ProductRecipe WHERE product_id='$product_id' AND ingredient_id='$ing_id_hapus'";
    if (mysqli_query($conn, $query_del)) {
        echo "<script>window.location='kelola.php?id=$product_id';</script>";
    }
}
?>

<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h3>Resep: <?php echo $produk['product_name']; ?></h3>
        <a href="index.php" class="btn btn-secondary">Kembali</a>
    </div>
    
    <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; border: 1px solid #ddd; margin: 20px 0;">
        <h4 style="margin-top:0; color: var(--primary-color);">Tambah Komposisi</h4>
        
        <form action="" method="POST" style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
            <div style="flex: 3; min-width: 200px;">
                <label>Pilih Bahan Baku</label>
                <select name="ingredient_id" required style="margin-bottom:0;">
                    <option value="">-- Cari Bahan --</option>
                    <?php while($ing = mysqli_fetch_assoc($res_ing)) { ?>
                        <option value="<?php echo $ing['ingredient_id']; ?>"><?php echo $ing['ingredient_name']; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div style="flex: 1; min-width: 100px;">
                <label>Jumlah</label>
                <input type="number" name="required_quantity" placeholder="15" required style="margin-bottom:0;">
            </div>

            <div style="flex: 1; min-width: 120px;">
                <label>Satuan</label>
                <select name="unit" required style="margin-bottom:0;">
                    <option value="gr">Gram (gr)</option>
                    <option value="ml">Milliliter (ml)</option>
                    <option value="pcs">Pcs</option>
                    <option value="sdt">Sendok Teh</option>
                    <option value="sdm">Sendok Makan</option>
                    <option value="shot">Shot (Espresso)</option>
                    <option value="cup">Cup</option>
                </select>
            </div>

            <div style="flex: 1; min-width: 100px;">
                <button type="submit" name="tambah_bahan" class="btn btn-primary" style="width:100%;">
                    <i class="fas fa-plus"></i> Tambah
                </button>
            </div>
        </form>
    </div>

    <h4>Komposisi Saat Ini:</h4>
    <table>
        <thead>
            <tr>
                <th width="10%">No</th>
                <th>Nama Bahan Baku</th>
                <th>Dosis / Takaran</th>
                <th width="15%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query_resep = "SELECT pr.*, i.ingredient_name 
                            FROM ProductRecipe pr 
                            JOIN Ingredient i ON pr.ingredient_id = i.ingredient_id 
                            WHERE pr.product_id = '$product_id'";
            $res_resep = mysqli_query($conn, $query_resep);
            $no = 1;
            
            if (mysqli_num_rows($res_resep) > 0) {
                while($row = mysqli_fetch_assoc($res_resep)) {
            ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><b><?php echo $row['ingredient_name']; ?></b></td>
                    <td>
                        <span style="background: #e9ecef; padding: 5px 10px; border-radius: 20px; font-weight: bold; color: #333;">
                            <?php echo $row['required_quantity'] . " " . $row['unit']; ?>
                        </span>
                    </td>
                    <td>
                        <a href="kelola.php?id=<?php echo $product_id; ?>&hapus_ing=<?php echo $row['ingredient_id']; ?>" 
                           class="btn btn-danger" style="font-size:0.8rem; padding: 5px 10px;"
                           onclick="return confirm('Hapus bahan ini dari resep?');">
                           <i class="fas fa-trash"></i> Hapus
                        </a>
                    </td>
                </tr>
            <?php 
                } 
            } else {
                echo "<tr><td colspan='4' style='text-align:center; color: #888; padding: 30px;'>Belum ada komposisi resep. Silakan racik di atas.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/footer.php'; ?>