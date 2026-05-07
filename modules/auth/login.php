<?php
session_start();
if (isset($_SESSION['staff_id'])) {
    header("Location: /senusa_kopi/modules/dashboard/index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Senusa Kopi</title>
    <link rel="stylesheet" href="/senusa_kopi/assets/css/style.css">
    <style>
        body {
            background-color: var(--secondary-color);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-card {
            background: white;
            padding: 40px;
            border-radius: 15px;
            width: 100%;
            max-width: 350px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        .login-logo {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 10px;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <div class="login-card">
        <div class="login-logo">
            <i class="fas fa-coffee"></i>
        </div>
        <h2 style="color: var(--primary-color);">SENUSA KOPI</h2>
        <p class="text-muted">Silakan login untuk akses sistem</p>

        <?php if(isset($_GET['error'])) { ?>
            <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px; font-size: 0.9rem;">
                <?php echo $_GET['error']; ?>
            </div>
        <?php } ?>

        <form action="proses_login.php" method="POST">
            <div style="text-align: left;">
                <label>Username</label>
                <input type="text" name="username" placeholder="Masukkan username" required>
            </div>
            
            <div style="text-align: left;">
                <label>Password</label>
                <input type="password" name="password" placeholder="Masukkan password" required>
            </div>

            <button type="submit" name="login" class="btn btn-primary" style="width: 100%; margin-top: 10px; padding: 12px;">
                MASUK <i class="fas fa-sign-in-alt"></i>
            </button>
        </form>
        
        <p style="margin-top: 20px; font-size: 0.8rem; color: #aaa;">
            &copy; <?php echo date('Y'); ?> Senusa Kopi System
        </p>
    </div>

</body>
</html>