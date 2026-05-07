<?php
include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/header.php';

if (isset($_POST['simpan'])) {
    $nama_bahan = cleanInput($_POST['ingredient_name']);
    $satuan     = cleanInput($_POST['unit']); 
    
    $new_id = generateId('IN', 'ingredient', 'ingredient_id');

    $query = "INSERT INTO ingredient (ingredient_id, ingredient_name, unit) VALUES ('$new_id', '$nama_bahan', '$satuan')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Bahan berhasil ditambahkan!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h3>Tambah Bahan Baku Baru</h3>
    <form action="" method="POST">
        <label>Nama Bahan Baku</label>
        <input type="text" name="ingredient_name" placeholder="Contoh: Biji Kopi Arabika" required>
        
        <label>Satuan Stok (Base Unit)</label>
        <select name="unit" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ccc; margin-bottom: 20px;">
            <option value="gr">Gram (gr)</option>
            <option value="ml">Milliliter (ml)</option>
            <option value="pcs">Pcs / Buah</option>
            <option value="kg">Kilogram (kg)</option>
            <option value="ltr">Liter (ltr)</option>
        </select>

        <div style="margin-top: 20px;">
            <button type="submit" name="simpan" class="btn btn-primary">Simpan Data</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/footer.php'; ?>