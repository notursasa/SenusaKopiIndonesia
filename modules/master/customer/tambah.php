<?php
include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/header.php';

if (isset($_POST['simpan'])) {
    $nama  = cleanInput($_POST['customer_name']);
    $hp    = cleanInput($_POST['customer_phone']);
    $email = cleanInput($_POST['customer_email']);
    
    $new_id = generateId('CU', 'Customer', 'customer_id');

    $query = "INSERT INTO Customer (customer_id, customer_name, customer_phone, customer_email) 
              VALUES ('$new_id', '$nama', '$hp', '$email')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Pelanggan berhasil ditambahkan!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h3>Tambah Pelanggan Baru</h3>
    <form action="" method="POST">
        <label>Nama Lengkap</label>
        <input type="text" name="customer_name" required>

        <label>No. HP / WhatsApp</label>
        <input type="text" name="customer_phone" required>

        <label>Email</label>
        <input type="email" name="customer_email" required>
        
        <div style="margin-top: 20px;">
            <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/footer.php'; ?>