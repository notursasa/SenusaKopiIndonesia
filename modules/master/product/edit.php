<?php
include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/header.php';

$id = $_GET['id'];
$query = "SELECT * FROM Product WHERE product_id = '$id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

$query_cat = "SELECT * FROM ProductCategory";
$result_cat = mysqli_query($conn, $query_cat);

if (isset($_POST['update'])) {
    $nama   = cleanInput($_POST['product_name']);
    $harga  = cleanInput($_POST['product_price']);
    $cat_id = cleanInput($_POST['category_id']);
    
    $gambar_final = $data['product_image']; 

    if (!empty($_FILES['product_image']['name'])) {
        $file_name = $_FILES['product_image']['name'];
        $file_tmp  = $_FILES['product_image']['tmp_name'];
        $gambar_baru = $id . "_" . $file_name;
        $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/senusa_kopi/uploads/products/";

        if (!empty($data['product_image']) && file_exists($target_dir . $data['product_image'])) {
            unlink($target_dir . $data['product_image']);
        }

        move_uploaded_file($file_tmp, $target_dir . $gambar_baru);
        $gambar_final = $gambar_baru;
    }

    $query_update = "UPDATE Product SET 
                     product_name='$nama', 
                     product_price='$harga', 
                     category_id='$cat_id',
                     product_image='$gambar_final'
                     WHERE product_id='$id'";

    if (mysqli_query($conn, $query_update)) {
        echo "<script>alert('Produk berhasil diupdate!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal update: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<div class="card" style="max-width: 700px; margin: 0 auto;">
    <h3>Edit Produk: <?php echo $data['product_name']; ?></h3>
    
    <form action="" method="POST" enctype="multipart/form-data">
        <label>Nama Produk</label>
        <input type="text" name="product_name" value="<?php echo $data['product_name']; ?>" required>

        <div style="display: flex; gap: 20px;">
            <div style="flex: 1;">
                <label>Harga (Rp)</label>
                <input type="number" name="product_price" value="<?php echo $data['product_price']; ?>" required>
            </div>
            <div style="flex: 1;">
                <label>Kategori</label>
                <select name="category_id" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ccc; margin-top: 8px;" required>
                    <?php while ($cat = mysqli_fetch_assoc($result_cat)) { 
                        $selected = ($cat['category_id'] == $data['category_id']) ? "selected" : "";
                    ?>
                        <option value="<?php echo $cat['category_id']; ?>" <?php echo $selected; ?>>
                            <?php echo $cat['category_name']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <label>Foto Produk</label><br>
        <?php if(!empty($data['product_image'])) { ?>
            <img src="/senusa_kopi/uploads/products/<?php echo $data['product_image']; ?>" width="100" style="margin: 10px 0; border-radius: 8px;">
            <br>
        <?php } ?>
        <input type="file" name="product_image" accept="image/*">
        <small style="display:block; color: #888;">Biarkan kosong jika tidak ingin mengganti foto.</small>
        
        <div style="margin-top: 20px;">
            <button type="submit" name="update" class="btn btn-primary">Update Data</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/footer.php'; ?>