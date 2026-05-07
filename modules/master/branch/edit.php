<?php
include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/header.php';


if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];


$query = "SELECT * FROM Branch WHERE branch_id = '$id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='index.php';</script>";
    exit;
}

if (isset($_POST['update'])) {
    $nama   = cleanInput($_POST['branch_name']);
    $alamat = cleanInput($_POST['branch_address']);

    $query_update = "UPDATE Branch SET branch_name = '$nama', branch_address = '$alamat' WHERE branch_id = '$id'";

    if (mysqli_query($conn, $query_update)) {
        echo "<script>alert('Data Cabang berhasil diupdate!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal update: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h3>Edit Cabang: <?php echo $data['branch_id']; ?></h3>
    <form action="" method="POST">
        <label>Nama Cabang</label>
        <input type="text" name="branch_name" value="<?php echo $data['branch_name']; ?>" required>

        <label>Alamat Lengkap</label>
        <textarea name="branch_address" rows="3" required><?php echo $data['branch_address']; ?></textarea>
        
        <div style="margin-top: 20px;">
            <button type="submit" name="update" class="btn btn-primary">Update Data</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/footer.php'; ?>