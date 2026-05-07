<?php
include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/header.php';
?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2><i class="fas fa-store"></i> Data Cabang (Outlet)</h2>
        <a href="tambah.php" class="btn btn-primary">+ Tambah Cabang</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Cabang</th>
                <th>Nama Cabang</th>
                <th>Alamat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT * FROM Branch ORDER BY branch_id ASC";
            $result = mysqli_query($conn, $query);
            $no = 1;

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><b><?php echo $row['branch_id']; ?></b></td>
                    <td><?php echo $row['branch_name']; ?></td>
                    <td><?php echo $row['branch_address']; ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $row['branch_id']; ?>" class="btn btn-secondary" style="font-size: 0.8rem;">Edit</a>
                        <a href="hapus.php?id=<?php echo $row['branch_id']; ?>" class="btn btn-danger" style="font-size: 0.8rem;" onclick="return confirm('Hapus cabang ini?');">Hapus</a>
                    </td>
                </tr>
            <?php 
                }
            } else {
                echo "<tr><td colspan='5' style='text-align:center;'>Belum ada data cabang.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/footer.php'; ?>