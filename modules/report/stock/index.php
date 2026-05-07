<?php
include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/header.php';

$q_branches = mysqli_query($conn, "SELECT * FROM Branch ORDER BY branch_name ASC");
$filter_branch = isset($_GET['branch_id']) ? $_GET['branch_id'] : '';

$where_clause = "";
if (!empty($filter_branch)) {
    $where_clause = "WHERE bs.branch_id = '$filter_branch'";
}

$query = "SELECT bs.*, i.ingredient_name, i.unit, b.branch_name 
          FROM BranchStock bs 
          JOIN Ingredient i ON bs.ingredient_id = i.ingredient_id 
          JOIN Branch b ON bs.branch_id = b.branch_id 
          $where_clause
          ORDER BY b.branch_name ASC, i.ingredient_name ASC";

$result = mysqli_query($conn, $query);
?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2><i class="fas fa-boxes"></i> Laporan Stok Cabang</h2>
        
        <form action="" method="GET" style="display: flex; gap: 10px;">
            <select name="branch_id" onchange="this.form.submit()" style="padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
                <option value="">-- Semua Cabang --</option>
                <?php while($br = mysqli_fetch_assoc($q_branches)) { 
                    $sel = ($filter_branch == $br['branch_id']) ? 'selected' : '';
                ?>
                    <option value="<?php echo $br['branch_id']; ?>" <?php echo $sel; ?>>
                        <?php echo $br['branch_name']; ?>
                    </option>
                <?php } ?>
            </select>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama Bahan Baku</th>
                <th>Lokasi Cabang</th>
                <th width="20%">Sisa Stok</th>
                <th width="15%">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $stok = $row['stock_quantity'];
                    
                    $status = "Aman";
                    $badge = "background: #d4e9e2; color: #00704A;"; 

                    if ($stok <= 1000) {
                        $status = "KRITIS";
                        $badge = "background: #f8d7da; color: #721c24; font-weight:bold;";
                    } elseif ($stok <= 3000) {
                        $status = "Menipis";
                        $badge = "background: #fff3cd; color: #856404;";
                    }
            ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><b><?php echo $row['ingredient_name']; ?></b></td>
                    <td><?php echo $row['branch_name']; ?></td>
                    
                    <td style="font-size: 1.1rem; font-weight: 500;">
                        <?php echo number_format($stok, 0, ',', '.'); ?> 
                        <span style="color: #666; font-size: 0.9rem;"><?php echo $row['unit']; ?></span>
                    </td>
                    
                    <td>
                        <span style="<?php echo $badge; ?> padding: 4px 10px; border-radius: 20px; font-size: 0.8rem;">
                            <?php echo $status; ?>
                        </span>
                    </td>
                </tr>
            <?php 
                }
            } else {
                echo "<tr><td colspan='5' style='text-align:center; padding: 20px;'>Belum ada data stok.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/footer.php'; ?>