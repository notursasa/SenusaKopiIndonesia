<?php
include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/layout/header.php';

if (!isset($_GET['id'])) { header("Location: index.php"); exit; }
$sales_id = $_GET['id'];

$order = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM SalesOrder WHERE sales_id='$sales_id'"));

if (isset($_POST['add_item'])) {
    $prod_id = $_POST['product_id'];
    $price   = $_POST['price'];
    
    $cek = mysqli_query($conn, "SELECT * FROM SalesOrderDetail WHERE sales_id='$sales_id' AND product_id='$prod_id' AND unit_price='$price'");
    
    if (mysqli_num_rows($cek) > 0) {
        mysqli_query($conn, "UPDATE SalesOrderDetail SET quantity_sold = quantity_sold + 1 WHERE sales_id='$sales_id' AND product_id='$prod_id' AND unit_price='$price'");
    } else {
        mysqli_query($conn, "INSERT INTO SalesOrderDetail (sales_id, product_id, quantity_sold, unit_price) VALUES ('$sales_id', '$prod_id', 1, '$price')");
    }
    echo "<script>window.location='kasir.php?id=$sales_id';</script>";
}

if (isset($_GET['del_prod']) && isset($_GET['price'])) {
    $p_id = $_GET['del_prod'];
    $p_price = $_GET['price'];
    mysqli_query($conn, "DELETE FROM SalesOrderDetail WHERE sales_id='$sales_id' AND product_id='$p_id' AND unit_price='$p_price'");
    echo "<script>window.location='kasir.php?id=$sales_id';</script>";
}

$cats = mysqli_query($conn, "SELECT * FROM ProductCategory");

$cat_filter = isset($_GET['cat']) ? $_GET['cat'] : '';
$where_cat = $cat_filter ? "AND category_id = '$cat_filter'" : "";

$products = mysqli_query($conn, "SELECT * FROM Product WHERE 1 $where_cat ORDER BY product_name ASC");
?>

<style>
    .pos-container { display: flex; gap: 20px; height: 80vh; }
    .pos-left { flex: 2; overflow-y: auto; padding-right: 10px; }
    .pos-right { flex: 1; display: flex; flex-direction: column; background: white; border-radius: 12px; box-shadow: 0 0 15px rgba(0,0,0,0.1); overflow: hidden; height: 100%; }
    
    .menu-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 15px; }
    .menu-card { background: white; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); overflow: hidden; cursor: pointer; transition: 0.2s; border: 1px solid #eee; }
    .menu-card:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-color: var(--primary-color); }
    .menu-img { width: 100%; height: 100px; object-fit: cover; background: #eee; }
    .menu-info { padding: 10px; text-align: center; }
    .menu-name { font-weight: bold; font-size: 0.9rem; margin-bottom: 5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .menu-price { color: var(--primary-color); font-weight: bold; }
    
    .cart-header { background: var(--secondary-color); color: white; padding: 15px; }
    .cart-body { flex: 1; overflow-y: auto; padding: 10px; }
    .cart-item { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; padding: 10px 0; }
    .cart-item-info { flex: 1; }
    .cart-item-qty { font-weight: bold; background: #eee; padding: 2px 8px; border-radius: 50px; font-size: 0.8rem; margin-right: 10px; }
    .cart-footer { padding: 20px; background: #f8f9fa; border-top: 1px solid #ddd; }
    
    .cat-tabs { display: flex; gap: 10px; overflow-x: auto; padding-bottom: 10px; margin-bottom: 15px; }
    .cat-tab { padding: 8px 15px; background: white; border-radius: 20px; font-size: 0.9rem; color: #555; text-decoration: none; white-space: nowrap; border: 1px solid #ddd; }
    .cat-tab.active { background: var(--primary-color); color: white; border-color: var(--primary-color); }
</style>

<div class="pos-container">
    <div class="pos-left">
        <div class="cat-tabs">
            <a href="kasir.php?id=<?php echo $sales_id; ?>" class="cat-tab <?php echo $cat_filter == '' ? 'active' : ''; ?>">Semua</a>
            <?php while($c = mysqli_fetch_assoc($cats)) { ?>
                <a href="kasir.php?id=<?php echo $sales_id; ?>&cat=<?php echo $c['category_id']; ?>" class="cat-tab <?php echo $cat_filter == $c['category_id'] ? 'active' : ''; ?>">
                    <?php echo $c['category_name']; ?>
                </a>
            <?php } ?>
        </div>

        <div class="menu-grid">
            <?php while($p = mysqli_fetch_assoc($products)) { 
                $img = !empty($p['product_image']) ? "/senusa_kopi/uploads/products/".$p['product_image'] : "https://via.placeholder.com/150";
            ?>
                <form method="POST" action="">
                    <input type="hidden" name="product_id" value="<?php echo $p['product_id']; ?>">
                    <input type="hidden" name="price" value="<?php echo $p['product_price']; ?>">
                    
                    <button type="submit" name="add_item" style="border:none; background:none; padding:0; width:100%; text-align:left;">
                        <div class="menu-card">
                            <img src="<?php echo $img; ?>" class="menu-img">
                            <div class="menu-info">
                                <div class="menu-name"><?php echo $p['product_name']; ?></div>
                                <div class="menu-price"><?php echo formatRupiah($p['product_price']); ?></div>
                            </div>
                        </div>
                    </button>
                </form>
            <?php } ?>
        </div>
    </div>

    <div class="pos-right">
        <div class="cart-header">
            <h4 style="margin:0;"><i class="fas fa-shopping-basket"></i> Order #<?php echo substr($sales_id, -4); ?></h4>
            <small><?php echo $order['order_type']; ?> - Guest</small>
        </div>
        
        <div class="cart-body">
            <?php
            $q_cart = "SELECT sod.*, p.product_name 
                       FROM SalesOrderDetail sod 
                       JOIN Product p ON sod.product_id = p.product_id 
                       WHERE sod.sales_id = '$sales_id'";
            $res_cart = mysqli_query($conn, $q_cart);
            $total_belanja = 0;

            if (mysqli_num_rows($res_cart) > 0) {
                while($item = mysqli_fetch_assoc($res_cart)) {
                    $subtotal = $item['quantity_sold'] * $item['unit_price'];
                    $total_belanja += $subtotal;
                    
                    $is_promo = ($item['unit_price'] < 0);
                    $row_class = $is_promo ? "background: #ffebeb;" : "";
                    $text_name = $is_promo ? "<span style='color:red; font-weight:bold;'>[FREE]</span> " . $item['product_name'] : $item['product_name'];
            ?>
                <div class="cart-item" style="<?php echo $row_class; ?>">
                    <div class="cart-item-info">
                        <div style="font-size:0.9rem;"><?php echo $text_name; ?></div>
                        <small class="text-muted">
                            <?php echo $item['quantity_sold']; ?> x <?php echo formatRupiah($item['unit_price']); ?>
                        </small>
                    </div>
                    <div style="text-align:right;">
                        <div style="font-weight:bold;"><?php echo formatRupiah($subtotal); ?></div>
                        
                        <a href="kasir.php?id=<?php echo $sales_id; ?>&del_prod=<?php echo $item['product_id']; ?>&price=<?php echo $item['unit_price']; ?>" 
                           style="color:#999; font-size:0.8rem; margin-left:5px;">
                           <i class="fas fa-trash"></i>
                        </a>

                        
                        <?php if(!$is_promo) { ?>
                            <form method="POST" action="" style="display:inline;">
                                <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                
                                <input type="hidden" name="price" value="<?php echo -1 * $item['unit_price']; ?>">
                                <button type="submit" name="add_item" title="Jadikan Free / Promo" 
                                        style="border:none; background:none; cursor:pointer; color: var(--primary-color); font-size:0.8rem; margin-left:5px;">
                                    <i class="fas fa-gift"></i>
                                </button>
                            </form>
                        <?php } ?>
                    </div>
                </div>
            <?php 
                }
            } else {
                echo "<div style='text-align:center; margin-top:50px; color:#aaa;'>Keranjang Kosong</div>";
            }
            ?>
        </div>

        <div class="cart-footer">
            <div style="display:flex; justify-content:space-between; margin-bottom:15px; font-size:1.2rem; font-weight:bold;">
                <span>Total</span>
                <span><?php echo formatRupiah($total_belanja); ?></span>
            </div>
            
            <a href="pembayaran.php?id=<?php echo $sales_id; ?>" class="btn btn-primary" style="width:100%; display:block; padding:15px; font-size:1.1rem;">
                BAYAR SEKARANG
            </a>
        </div>
    </div>
</div>