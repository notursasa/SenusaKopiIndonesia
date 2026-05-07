<?php
include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/header.php';

if (!isset($_GET['id'])) { header("Location: index.php"); exit; }
$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM Ingredient WHERE ingredient_id = '$id'"));

if (isset($_POST['update'])) {
    $nama_bahan = cleanInput($_POST['ingredient_name']);
    $satuan     = cleanInput($_POST['unit']);

    $query_update = "UPDATE Ingredient SET ingredient_name = '$nama_bahan', unit = '$satuan' WHERE ingredient_id = '$id'";

    if (mysqli_query($conn, $query_update)) {
        echo "<script>alert('Bahan baku berhasil diupdate!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal update: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h3>Edit Bahan Baku: <?php echo $data['ingredient_id']; ?></h3>
    <form action="" method="POST">
        <label>Nama Bahan Baku</label>
        <input type="text" name="ingredient_name" value="<?php echo $data['ingredient_name']; ?>" required>
        
        <label>Satuan Stok</label>
        <select name="unit" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ccc; margin-bottom: 20px;">
            <option value="gr" <?php if($data['unit']=='gr') echo 'selected'; ?>>Gram (gr)</option>
            <option value="ml" <?php if($data['unit']=='ml') echo 'selected'; ?>>Milliliter (ml)</option>
            <option value="pcs" <?php if($data['unit']=='pcs') echo 'selected'; ?>>Pcs / Buah</option>
            <option value="kg" <?php if($data['unit']=='kg') echo 'selected'; ?>>Kilogram (kg)</option>
            <option value="ltr" <?php if($data['unit']=='ltr') echo 'selected'; ?>>Liter (ltr)</option>
        </select>
        
        <div style="margin-top: 20px;">
            <button type="submit" name="update" class="btn btn-primary">Update Data</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/footer.php'; ?>