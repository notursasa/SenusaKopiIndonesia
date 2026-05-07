<?php
include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/header.php';
?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2><i class="fas fa-truck"></i> Data Supplier</h2>
        <a href="tambah.php" class="btn btn-primary">+ Tambah Supplier</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Supplier</th>
                <th>Nama Supplier</th>
                <th>Kontak</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT * FROM Supplier ORDER BY supplier_id ASC";
            $result = mysqli_query($conn, $query);
            $no = 1;

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><b><?php echo $row['supplier_id']; ?></b></td>
                    <td>
                        <?php echo $row['supplier_name']; ?><br>
                        <small class="text-muted"><?php echo $row['supplier_email']; ?></small>
                    </td>
                    <td><?php echo $row['supplier_phone']; ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $row['supplier_id']; ?>" class="btn btn-secondary" style="font-size: 0.8rem;">Edit</a>
                        <a href="hapus.php?id=<?php echo $row['supplier_id']; ?>" class="btn btn-danger" style="font-size: 0.8rem;" onclick="return confirm('Hapus supplier ini?');">Hapus</a>
                    </td>
                </tr>
            <?php 
                }
            } else {
                echo "<tr><td colspan='5' style='text-align:center;'>Belum ada supplier.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/footer.php'; ?>