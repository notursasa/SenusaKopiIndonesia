<?php
include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/header.php';

if (isset($_POST['simpan'])) {
    $nama_kategori = cleanInput($_POST['category_name']);
    $deskripsi     = cleanInput($_POST['category_description']);
    
    $new_id = generateId('CT', 'ProductCategory', 'category_id');

    $query = "INSERT INTO ProductCategory (category_id, category_name, category_description) VALUES ('$new_id', '$nama_kategori', '$deskripsi')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Kategori berhasil ditambahkan!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h3>Tambah Kategori Baru</h3>
    <form action="" method="POST">
        <label>Nama Kategori</label>
        <input type="text" name="category_name" placeholder="Contoh: Coffee Based, Non-Coffee, Pastry, Main Course" required>

        <label>Deskripsi Singkat</label>
        <textarea name="category_description" rows="3" placeholder="Contoh: Aneka minuman berbasis espresso..." required></textarea>
        
        <div style="margin-top: 20px;">
            <button type="submit" name="simpan" class="btn btn-primary">Simpan Data</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/footer.php'; ?>