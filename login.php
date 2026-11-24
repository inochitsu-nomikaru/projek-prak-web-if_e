<?php
session_start();

if (isset($_SESSION['karyawan'])) {
    header("Location: pesanan.php");
    exit();
}

$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Masuk Sebagai Karyawan - Resto Icikiwir Gejesgejes</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Fredoka:wght@600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --pink-primary: #ff4d88;
            --pink-hover: #ff2a70;
            --pink-light: #fff0f5;
            --pink-soft: #ffe4ee;
            --pink-dark: #b81d52;
            --white: #ffffff;
            --text-dark: #231625;
            --text-muted: #7e6777;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(145deg, #fff0f5 0%, #fde2ec 50%, #ffeaf1 100%);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 16px;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border-radius: 28px;
            border: 2px solid var(--pink-soft);
            padding: 34px 28px;
            box-shadow: 0 16px 40px rgba(255, 77, 136, 0.16);
            text-align: center;
            position: relative;
        }

        .login-icon-badge {
            width: 72px;
            height: 72px;
            background: var(--pink-light);
            border: 2px solid var(--pink-soft);
            border-radius: 24px;
            margin: 0 auto 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            box-shadow: 0 8px 20px rgba(255, 77, 136, 0.2);
        }

        .login-title {
            font-size: 1.45rem;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 6px;
        }

        .login-title span {
            color: var(--pink-primary);
        }

        .login-sub {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-bottom: 24px;
        }

        .error-alert {
            background: #fee2e2;
            border: 1px solid #f87171;
            color: #b91c1c;
            padding: 10px 14px;
            border-radius: 12px;
            font-size: 0.83rem;
            font-weight: 600;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
            text-align: left;
        }

        .form-group {
            margin-bottom: 18px;
            text-align: left;
        }

        .form-group label {
            display: block;
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 6px;
        }

        .form-input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            font-size: 1.1rem;
            color: var(--pink-primary);
            pointer-events: none;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px 14px 44px;
            border-radius: 16px;
            border: 1.5px solid var(--pink-soft);
            font-size: 0.95rem;
            background: #ffffff;
            color: var(--text-dark);
            font-family: inherit;
            transition: all 0.25s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--pink-primary);
            box-shadow: 0 0 0 3px rgba(255, 77, 136, 0.2);
        }

        .btn-login {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #ff4d88 0%, #ff2a70 100%);
            color: #ffffff;
            border: none;
            border-radius: 999px;
            font-size: 1.05rem;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 8px 24px rgba(255, 42, 112, 0.35);
            transition: all 0.2s ease;
            margin-top: 8px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(255, 42, 112, 0.45);
        }

        .btn-login:active {
            transform: scale(0.98);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 22px;
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .back-link:hover {
            color: var(--pink-primary);
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-icon-badge">👩‍🍳</div>
    <h1 class="login-title">Masuk Sebagai <span>Karyawan</span></h1>
    <p class="login-sub">Portal Manajemen Pesanan Dapur & Kasir</p>

    <?php if (!empty($error)): ?>
    <div class="error-alert">
        <span>⚠️</span>
        <span><?= htmlspecialchars($error); ?></span>
    </div>
    <?php endif; ?>

    <form action="backend/auth.php" method="POST">
        <div class="form-group">
            <label for="username">Username</label>
            <div class="form-input-wrap">
                <span class="input-icon">👤</span>
                <input type="text" id="username" name="username" class="form-input" placeholder="Masukkan username" required autocomplete="username">
            </div>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <div class="form-input-wrap">
                <span class="input-icon">🔒</span>
                <input type="password" id="password" name="password" class="form-input" placeholder="Masukkan password" required autocomplete="current-password">
            </div>
        </div>

        <button type="submit" class="btn-login">
            Masuk ke Pesanan ➔
        </button>
    </form>

    <div>
        <a href="index.php" class="back-link">
            <span>←</span> Kembali ke Halaman Utama
        </a>
    </div>
</div>

</body>
</html>

