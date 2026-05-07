<?php
include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/header.php';

if (isset($_POST['simpan'])) {
    $nama   = cleanInput($_POST['branch_name']);
    $alamat = cleanInput($_POST['branch_address']);
    
    $new_id = generateId('BR', 'Branch', 'branch_id');

    $query = "INSERT INTO Branch (branch_id, branch_name, branch_address) VALUES ('$new_id', '$nama', '$alamat')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Cabang berhasil ditambahkan!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h3>Tambah Cabang Baru</h3>
    <form action="" method="POST">
        <label>Nama Cabang</label>
        <input type="text" name="branch_name" placeholder="Contoh: Senusa Palmerah, Senusa Slipi" required>

        <label>Alamat Lengkap</label>
        <textarea name="branch_address" rows="3" required></textarea>
        
        <div style="margin-top: 20px;">
            <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/footer.php'; ?>