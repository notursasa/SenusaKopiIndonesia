<?php
session_start();

if (!isset($_SESSION['staff_id'])) {
    header("Location: /senusa_kopi/modules/auth/login.php");
    exit;
}

if (!isset($conn)) {
    include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/config/database.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/senusa_kopi/helpers/functions.php';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senusa Kopi - Sistem POS</title>
    <link rel="stylesheet" href="/senusa_kopi/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <nav class="navbar-senusa">
        <a href="/senusa_kopi/index.php" class="brand-logo">
            <i class="fas fa-coffee"></i> SENUSA KOPI
        </a>
        
        <div class="nav-links">
            <a href="/senusa_kopi/index.php" class="btn btn-primary" style="background:none; border:none;">Dashboard</a>
            
            <div style="display:inline-block; margin-left: 10px;">
                <span style="color:white; font-weight:600;">Master:</span>
                <a href="/senusa_kopi/modules/master/staff/index.php" class="btn btn-secondary" style="font-size:0.8rem; padding: 5px 10px;">Staff</a>
                <a href="/senusa_kopi/modules/master/customer/index.php" class="btn btn-secondary" style="font-size:0.8rem; padding: 5px 10px;">Customer</a>
                <a href="/senusa_kopi/modules/master/branch/index.php" class="btn btn-secondary" style="font-size:0.8rem; padding: 5px 10px;">Cabang</a>
                <a href="/senusa_kopi/modules/master/supplier/index.php" class="btn btn-secondary" style="font-size:0.8rem; padding: 5px 10px;">Supplier</a>
                <a href="/senusa_kopi/modules/master/ingredient/index.php" class="btn btn-secondary" style="font-size:0.8rem; padding: 5px 10px;">Bahan</a>
                <a href="/senusa_kopi/modules/master/category/index.php" class="btn btn-secondary" style="font-size:0.8rem; padding: 5px 15px;">Kategori</a>
                <a href="/senusa_kopi/modules/master/product/index.php" class="btn btn-secondary" style="font-size:0.8rem; padding: 5px 10px;">Produk</a>
                <a href="/senusa_kopi/modules/master/recipe/index.php" class="btn btn-secondary" style="font-size:0.8rem; padding: 5px 10px;">Resep</a>
            </div>
            
             <div style="display:inline-block; margin-left: 10px; border-left: 1px solid rgba(255,255,255,0.3); padding-left: 10px;">
                <span style="color:white; font-weight:600;">Trx:</span>
                <a href="/senusa_kopi/modules/transaction/supply/index.php" class="btn btn-secondary" style="font-size:0.8rem; padding: 5px 10px;">Supply</a>
                <a href="/senusa_kopi/modules/transaction/sales/index.php" class="btn btn-secondary" style="font-size:0.8rem; padding: 5px 10px;">POS (Kasir)</a>
             </div>

             <div style="display:inline-block; margin-left: 10px; border-left: 1px solid rgba(255,255,255,0.3); padding-left: 10px;">
                <span style="color:white; font-weight:600;">Laporan:</span>
                <a href="/senusa_kopi/modules/report/stock/index.php" class="btn btn-secondary" style="font-size:0.8rem; padding: 5px 10px;">Stok</a>
                <a href="/senusa_kopi/modules/report/sales/index.php" class="btn btn-secondary" style="font-size:0.8rem; padding: 5px 10px;">Penjualan</a>
            </div>
        </div>

        <div style="margin-left: 20px; border-left: 1px solid rgba(255,255,255,0.3); padding-left: 15px; display: flex; align-items: center; gap: 10px;">
            <div style="text-align: right; line-height: 1.2;">
                <div style="font-weight: bold; font-size: 0.9rem;"><?php echo $_SESSION['username']; ?></div>
                <div style="font-size: 0.75rem; opacity: 0.8;"><?php echo $_SESSION['role']; ?></div>
            </div>
            <a href="/senusa_kopi/modules/auth/logout.php" class="btn btn-danger" style="font-size: 0.8rem; padding: 5px 10px;" onclick="return confirm('Yakin ingin keluar?');">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
        
    </nav>

    <div class="main-content">