<?php
include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/header.php';
?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2><i class="fas fa-coffee"></i> Data Produk & Menu</h2>
        <a href="tambah.php" class="btn btn-primary">+ Tambah Menu Baru</a>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="10%">Gambar</th>
                <th width="15%">ID Produk</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th width="20%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT p.*, c.category_name 
                      FROM Product p 
                      JOIN ProductCategory c ON p.category_id = c.category_id 
                      ORDER BY p.product_id DESC";
                      
            $result = mysqli_query($conn, $query);
            $no = 1;

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $gambar = $row['product_image'];
                    $img_src = (!empty($gambar)) ? "/senusa_kopi/uploads/products/$gambar" : "https://via.placeholder.com/50";
            ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td>
                        <img src="<?php echo $img_src; ?>" alt="Foto" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                    </td>
                    <td><b><?php echo $row['product_id']; ?></b></td>
                    <td><?php echo $row['product_name']; ?></td>
                    <td><span style="background: #e9ecef; padding: 2px 8px; border-radius: 4px; font-size: 12px;"><?php echo $row['category_name']; ?></span></td>
                    <td><?php echo formatRupiah($row['product_price']); ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $row['product_id']; ?>" class="btn btn-secondary" style="font-size: 0.8rem;">Edit</a>
                        <a href="hapus.php?id=<?php echo $row['product_id']; ?>" class="btn btn-danger" style="font-size: 0.8rem;" onclick="return confirm('Yakin hapus menu ini?');">Hapus</a>
                    </td>
                </tr>
            <?php 
                }
            } else {
                echo "<tr><td colspan='7' style='text-align:center;'>Belum ada data produk. Silakan tambah menu.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/footer.php'; ?>