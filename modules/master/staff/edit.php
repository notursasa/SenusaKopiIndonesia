<?php
include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/header.php';

if (!isset($_GET['id'])) { header("Location: index.php"); exit; }
$id = $_GET['id'];

$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM Staff WHERE staff_id = '$id'"));
$opt_cabang = mysqli_query($conn, "SELECT * FROM Branch ORDER BY branch_name ASC");

if (isset($_POST['update'])) {
    $username = cleanInput($_POST['staff_username']);
    $password = cleanInput($_POST['staff_password']);
    $role     = cleanInput($_POST['staff_role']);
    $branch   = cleanInput($_POST['branch_id']);

    $query = "UPDATE Staff SET staff_username='$username', staff_password='$password', staff_role='$role', branch_id='$branch' WHERE staff_id='$id'";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Staff berhasil diupdate!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h3>Edit Staff: <?php echo $id; ?></h3>
    <form action="" method="POST">
        <label>Username / Nama</label>
        <input type="text" name="staff_username" value="<?php echo $data['staff_username']; ?>" required>

        <label>Password</label>
        <input type="text" name="staff_password" value="<?php echo $data['staff_password']; ?>" required>

        <label>Role</label>
        <select name="staff_role" required style="width: 100%; padding: 10px; margin-bottom: 20px; border-radius: 8px; border: 1px solid #ccc;">
            <option value="Cashier" <?php if($data['staff_role']=='Cashier') echo 'selected'; ?>>Cashier</option>
            <option value="Manager" <?php if($data['staff_role']=='Manager') echo 'selected'; ?>>Manager</option>
            <option value="Admin" <?php if($data['staff_role']=='Admin') echo 'selected'; ?>>Admin</option>
        </select>

        <label>Penempatan Cabang</label>
        <select name="branch_id" required style="width: 100%; padding: 10px; margin-bottom: 20px; border-radius: 8px; border: 1px solid #ccc;">
            <?php while ($c = mysqli_fetch_assoc($opt_cabang)) { 
                $sel = ($c['branch_id'] == $data['branch_id']) ? 'selected' : '';
            ?>
                <option value="<?php echo $c['branch_id']; ?>" <?php echo $sel; ?>><?php echo $c['branch_name']; ?></option>
            <?php } ?>
        </select>
        
        <button type="submit" name="update" class="btn btn-primary">Update</button>
        <a href="index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/footer.php'; ?>