<?php
include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/header.php';
?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2><i class="fas fa-boxes"></i> Data Bahan Baku (Ingredients)</h2>
        <a href="tambah.php" class="btn btn-primary">+ Tambah Bahan</a>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">ID Bahan</th>
                <th>Nama Bahan</th>
                <th width="20%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT * FROM ingredient ORDER BY ingredient_id ASC";
            $result = mysqli_query($conn, $query);
            $no = 1;

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><b><?php echo $row['ingredient_id']; ?></b></td>
                    <td><?php echo $row['ingredient_name']; ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $row['ingredient_id']; ?>" class="btn btn-secondary" style="font-size: 0.8rem;">Edit</a>
                        <a href="hapus.php?id=<?php echo $row['ingredient_id']; ?>" class="btn btn-danger" style="font-size: 0.8rem;" onclick="return confirm('Yakin hapus bahan ini?');">Hapus</a>
                    </td>
                </tr>
            <?php 
                }
            } else {
                echo "<tr><td colspan='4' style='text-align:center;'>Belum ada data bahan baku.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/footer.php'; ?>