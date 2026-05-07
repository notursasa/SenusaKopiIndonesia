<?php
include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/header.php';

if (!isset($_GET['id'])) { header("Location: index.php"); exit; }
$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM Customer WHERE customer_id = '$id'"));

if (isset($_POST['update'])) {
    $nama  = cleanInput($_POST['customer_name']);
    $hp    = cleanInput($_POST['customer_phone']);
    $email = cleanInput($_POST['customer_email']);

    $query = "UPDATE Customer SET customer_name='$nama', customer_phone='$hp', customer_email='$email' WHERE customer_id='$id'";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Data berhasil diupdate!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h3>Edit Pelanggan: <?php echo $id; ?></h3>
    <form action="" method="POST">
        <label>Nama Lengkap</label>
        <input type="text" name="customer_name" value="<?php echo $data['customer_name']; ?>" required>

        <label>No. HP</label>
        <input type="text" name="customer_phone" value="<?php echo $data['customer_phone']; ?>" required>

        <label>Email</label>
        <input type="email" name="customer_email" value="<?php echo $data['customer_email']; ?>" required>
        
        <button type="submit" name="update" class="btn btn-primary">Update</button>
        <a href="index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/footer.php'; ?>