<?php
include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/header.php';

$opt_cabang = mysqli_query($conn, "SELECT * FROM Branch ORDER BY branch_name ASC");

if (isset($_POST['simpan'])) {
    $username = cleanInput($_POST['staff_username']);
    $password = cleanInput($_POST['staff_password']);
    $role     = cleanInput($_POST['staff_role']);
    $branch   = cleanInput($_POST['branch_id']);
    
    $new_id = generateId('ST', 'Staff', 'staff_id');

    $query = "INSERT INTO Staff (staff_id, staff_username, staff_password, staff_role, branch_id) 
              VALUES ('$new_id', '$username', '$password', '$role', '$branch')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Staff berhasil ditambahkan!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h3>Tambah Staff Baru</h3>
    <form action="" method="POST">
        <label>Username / Nama</label>
        <input type="text" name="staff_username" required>

        <label>Password</label>
        <input type="text" name="staff_password" required>

        <label>Role</label>
        <select name="staff_role" required style="width: 100%; padding: 10px; margin-bottom: 20px; border-radius: 8px; border: 1px solid #ccc;">
            <option value="Cashier">Cashier</option>
            <option value="Manager">Manager</option>
            <option value="Admin">Admin</option>
        </select>

        <label>Penempatan Cabang</label>
        <select name="branch_id" required style="width: 100%; padding: 10px; margin-bottom: 20px; border-radius: 8px; border: 1px solid #ccc;">
            <option value="">-- Pilih Cabang --</option>
            <?php while ($c = mysqli_fetch_assoc($opt_cabang)) { ?>
                <option value="<?php echo $c['branch_id']; ?>"><?php echo $c['branch_name']; ?></option>
            <?php } ?>
        </select>
        
        <div style="margin-top: 20px;">
            <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/footer.php'; ?>