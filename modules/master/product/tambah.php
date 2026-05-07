<?php
include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/header.php';

$query_cat = "SELECT * FROM ProductCategory";
$result_cat = mysqli_query($conn, $query_cat);

if (isset($_POST['simpan'])) {
    $nama   = cleanInput($_POST['product_name']);
    $harga  = cleanInput($_POST['product_price']);
    $cat_id = cleanInput($_POST['category_id']);
    
    $new_id = generateId('PR', 'Product', 'product_id');

    $nama_file_gambar = "";
    
    if (!empty($_FILES['product_image']['name'])) {
        $file_name = $_FILES['product_image']['name'];
        $file_tmp  = $_FILES['product_image']['tmp_name'];
        
        $nama_file_gambar = $new_id . "_" . $file_name;
        
        $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/senusa_kopi/uploads/products/";
        
        move_uploaded_file($file_tmp, $target_dir . $nama_file_gambar);
    }

    $query = "INSERT INTO Product (product_id, product_name, product_price, product_image, category_id) 
              VALUES ('$new_id', '$nama', '$harga', '$nama_file_gambar', '$cat_id')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Produk berhasil ditambahkan!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<div class="card" style="max-width: 700px; margin: 0 auto;">
    <h3>Tambah Menu Baru</h3>
    
    <form action="" method="POST" enctype="multipart/form-data">
        
        <label>Nama Produk / Menu</label>
        <input type="text" name="product_name" placeholder="Contoh: Kopi Susu Senusa" required>

        <div style="display: flex; gap: 20px;">
            <div style="flex: 1;">
                <label>Harga (Rp)</label>
                <input type="number" name="product_price" placeholder="18000" required>
            </div>
            <div style="flex: 1;">
                <label>Kategori</label>
                <select name="category_id" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ccc; margin-top: 8px;" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php while ($cat = mysqli_fetch_assoc($result_cat)) { ?>
                        <option value="<?php echo $cat['category_id']; ?>">
                            <?php echo $cat['category_name']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <label>Foto Produk (Opsional)</label>
        <input type="file" name="product_image" accept="image/*" style="margin-top: 10px; margin-bottom: 20px;">
        
        <div style="margin-top: 20px;">
            <button type="submit" name="simpan" class="btn btn-primary">Simpan Produk</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/footer.php'; ?>