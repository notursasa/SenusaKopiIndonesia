<?php
include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/header.php';
?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2><i class="fas fa-scroll"></i> Kelola Resep Produk</h2>
    </div>
    
    <p class="text-muted">Pilih produk untuk mengatur bahan baku (Ingredients) yang digunakan.</p>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Jumlah Bahan</th>
                <th width="15%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT p.product_id, p.product_name, c.category_name, 
                     (SELECT COUNT(*) FROM ProductRecipe pr WHERE pr.product_id = p.product_id) as total_bahan
                      FROM Product p 
                      JOIN ProductCategory c ON p.category_id = c.category_id 
                      ORDER BY p.product_name ASC";
                      
            $result = mysqli_query($conn, $query);
            $no = 1;

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><b><?php echo $row['product_name']; ?></b></td>
                    <td><?php echo $row['category_name']; ?></td>
                    <td>
                        <?php if($row['total_bahan'] > 0) { ?>
                            <span style="background: #d4e9e2; color: #00704A; padding: 2px 8px; border-radius: 4px; font-weight:bold;">
                                <?php echo $row['total_bahan']; ?> Item
                            </span>
                        <?php } else { ?>
                            <span style="color: #aaa;">Belum diset</span>
                        <?php } ?>
                    </td>
                    <td>
                        <a href="kelola.php?id=<?php echo $row['product_id']; ?>" class="btn btn-primary" style="font-size: 0.8rem;">
                            <i class="fas fa-flask"></i> Racik Resep
                        </a>
                    </td>
                </tr>
            <?php 
                }
            } else {
                echo "<tr><td colspan='5' style='text-align:center;'>Belum ada produk. Tambahkan produk dulu.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/footer.php'; ?>