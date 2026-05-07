<?php
include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/header.php';

// Ambil ID dari URL
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];
$query = "SELECT * FROM ProductCategory WHERE category_id = '$id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='index.php';</script>";
    exit;
}

if (isset($_POST['update'])) {
    $nama_kategori = cleanInput($_POST['category_name']);
    $deskripsi     = cleanInput($_POST['category_description']);

    $query_update = "UPDATE ProductCategory SET category_name = '$nama_kategori', category_description = '$deskripsi' WHERE category_id = '$id'";

    if (mysqli_query($conn, $query_update)) {
        echo "<script>alert('Kategori berhasil diupdate!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal update: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h3>Edit Kategori: <?php echo $data['category_id']; ?></h3>
    <form action="" method="POST">
        <label>Nama Kategori</label>
        <input type="text" name="category_name" value="<?php echo $data['category_name']; ?>" required>

        <label>Deskripsi Singkat</label>
        <textarea name="category_description" rows="3" required><?php echo $data['category_description']; ?></textarea>
        
        <div style="margin-top: 20px;">
            <button type="submit" name="update" class="btn btn-primary">Update Data</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/footer.php'; ?>