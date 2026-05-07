<?php
include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/header.php';
?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2><i class="fas fa-id-badge"></i> Data Staff / Karyawan</h2>
        <a href="tambah.php" class="btn btn-primary">+ Tambah Staff</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Staff</th>
                <th>Nama / Username</th>
                <th>Role</th>
                <th>Penempatan (Cabang)</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT s.*, b.branch_name 
                      FROM Staff s 
                      JOIN Branch b ON s.branch_id = b.branch_id 
                      ORDER BY s.staff_id ASC";
            $result = mysqli_query($conn, $query);
            $no = 1;

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><b><?php echo $row['staff_id']; ?></b></td>
                    <td><?php echo $row['staff_username']; ?></td>
                    <td>
                        <span style="background: #e9ecef; padding: 2px 8px; border-radius: 4px; font-weight:bold;">
                            <?php echo $row['staff_role']; ?>
                        </span>
                    </td>
                    <td><?php echo $row['branch_name']; ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $row['staff_id']; ?>" class="btn btn-secondary" style="font-size: 0.8rem;">Edit</a>
                        <a href="hapus.php?id=<?php echo $row['staff_id']; ?>" class="btn btn-danger" style="font-size: 0.8rem;" onclick="return confirm('Hapus staff ini?');">Hapus</a>
                    </td>
                </tr>
            <?php 
                }
            } else {
                echo "<tr><td colspan='6' style='text-align:center;'>Belum ada data staff.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/footer.php'; ?>