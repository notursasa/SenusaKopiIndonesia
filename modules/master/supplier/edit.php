<?php
include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/header.php';

// Ambil ID dari URL
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

// Ambil data lama
$query = "SELECT * FROM Supplier WHERE supplier_id = '$id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='index.php';</script>";
    exit;
}

if (isset($_POST['update'])) {
    $nama  = cleanInput($_POST['supplier_name']);
    $hp    = cleanInput($_POST['supplier_phone']);
    $email = cleanInput($_POST['supplier_email']);

    $query_update = "UPDATE Supplier SET supplier_name = '$nama', supplier_phone = '$hp', supplier_email = '$email' WHERE supplier_id = '$id'";

    if (mysqli_query($conn, $query_update)) {
        echo "<script>alert('Data Supplier berhasil diupdate!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal update: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h3>Edit Supplier: <?php echo $data['supplier_id']; ?></h3>
    <form action="" method="POST">
        <label>Nama Perusahaan / Supplier</label>
        <input type="text" name="supplier_name" value="<?php echo $data['supplier_name']; ?>" required>

        <label>No. Telepon / WhatsApp</label>
        <input type="text" name="supplier_phone" value="<?php echo $data['supplier_phone']; ?>" required>

        <label>Email</label>
        <input type="email" name="supplier_email" value="<?php echo $data['supplier_email']; ?>" required>
        
        <div style="margin-top: 20px;">
            <button type="submit" name="update" class="btn btn-primary">Update Data</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/footer.php'; ?>