<?php
include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/header.php';
?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2><i class="fas fa-tags"></i> Data Kategori Menu</h2>
        <a href="tambah.php" class="btn btn-primary">+ Tambah Kategori</a>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">ID Kategori</th>
                <th width="25%">Nama Kategori</th>
                <th>Deskripsi</th>
                <th width="20%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT * FROM ProductCategory ORDER BY category_id ASC";
            $result = mysqli_query($conn, $query);
            $no = 1;

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><b><?php echo $row['category_id']; ?></b></td>
                    <td><?php echo $row['category_name']; ?></td>
                    <td><?php echo $row['category_description']; ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $row['category_id']; ?>" class="btn btn-secondary" style="font-size: 0.8rem;">Edit</a>
                        <a href="hapus.php?id=<?php echo $row['category_id']; ?>" class="btn btn-danger" style="font-size: 0.8rem;" onclick="return confirm('Hati-hati! Menghapus kategori ini akan menghapus semua PRODUK yang ada di dalamnya. Yakin?');">Hapus</a>
                    </td>
                </tr>
            <?php 
                }
            } else {
                echo "<tr><td colspan='5' style='text-align:center;'>Belum ada data kategori.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/footer.php'; ?>