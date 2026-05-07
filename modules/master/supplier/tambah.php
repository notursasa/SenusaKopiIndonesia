<?php
include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/header.php';

if (isset($_POST['simpan'])) {
    $nama  = cleanInput($_POST['supplier_name']);
    $hp    = cleanInput($_POST['supplier_phone']);
    $email = cleanInput($_POST['supplier_email']);
    
    $new_id = generateId('SP', 'Supplier', 'supplier_id');

    $query = "INSERT INTO Supplier (supplier_id, supplier_name, supplier_phone, supplier_email) VALUES ('$new_id', '$nama', '$hp', '$email')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Supplier berhasil ditambahkan!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h3>Tambah Supplier Baru</h3>
    <form action="" method="POST">
        <label>Nama Perusahaan / Supplier</label>
        <input type="text" name="supplier_name" placeholder="PT. Susu Segar Jaya" required>

        <label>No. Telepon / WhatsApp</label>
        <input type="text" name="supplier_phone" placeholder="0812..." required>

        <label>Email</label>
        <input type="email" name="supplier_email" placeholder="sales@supplier.com" required>
        
        <div style="margin-top: 20px;">
            <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/footer.php'; ?>