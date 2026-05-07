<?php
include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/header.php';
?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2><i class="fas fa-users"></i> Data Pelanggan (Member)</h2>
        <a href="tambah.php" class="btn btn-primary">+ Tambah Pelanggan</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Customer</th>
                <th>Nama Lengkap</th>
                <th>No. HP</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT * FROM Customer ORDER BY customer_id ASC";
            $result = mysqli_query($conn, $query);
            $no = 1;

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><b><?php echo $row['customer_id']; ?></b></td>
                    <td><?php echo $row['customer_name']; ?></td>
                    <td><?php echo $row['customer_phone']; ?></td>
                    <td><?php echo $row['customer_email']; ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $row['customer_id']; ?>" class="btn btn-secondary" style="font-size: 0.8rem;">Edit</a>
                        <a href="hapus.php?id=<?php echo $row['customer_id']; ?>" class="btn btn-danger" style="font-size: 0.8rem;" onclick="return confirm('Hapus pelanggan ini?');">Hapus</a>
                    </td>
                </tr>
            <?php 
                }
            } else {
                echo "<tr><td colspan='6' style='text-align:center;'>Belum ada data pelanggan.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/footer.php'; ?>