<?php
// menu.php
require_once __DIR__ . '/backend/koneksi.php';

// Ambil daftar menu dari database
$query = "SELECT * FROM menu ORDER BY id_menu ASC";
$result = $conn->query($query);
$menus = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $menus[] = $row;
    }
}

// Map stiker unik untuk setiap menu
function getMenuSticker($id) {
    $stickers = [
        1 => ['emoji' => '🥤', 'tag' => 'Segar Pol', 'color' => '#fef3c7', 'border' => '#f59e0b'],
        2 => ['emoji' => '🍡', 'tag' => 'Jadul Viral', 'color' => '#ede9fe', 'border' => '#8b5cf6'],
        3 => ['emoji' => '🍹', 'tag' => 'Pinky Soda', 'color' => '#fce7f3', 'border' => '#ec4899'],
        4 => ['emoji' => '🍧', 'tag' => 'Full Buah', 'color' => '#ccfbf1', 'border' => '#14b8a6'],
        5 => ['emoji' => '🍈', 'tag' => 'King Durian', 'color' => '#fef9c3', 'border' => '#eab308'],
        6 => ['emoji' => '🍜', 'tag' => 'Lvl 1 Slebew', 'color' => '#ffedd5', 'border' => '#f97316'],
        7 => ['emoji' => '🌶️', 'tag' => 'Lvl 2 Jebret', 'color' => '#fee2e2', 'border' => '#ef4444'],
        8 => ['emoji' => '🔥', 'tag' => 'Lvl 3 Huhah', 'color' => '#fee2e2', 'border' => '#dc2626'],
        9 => ['emoji' => '😈', 'tag' => 'Lvl 4 Setan', 'color' => '#f3e8ff', 'border' => '#9333ea'],
        10 => ['emoji' => '👹', 'tag' => 'Lvl 5 Iblis Max', 'color' => '#ffe4e6', 'border' => '#e11d48'],
        11 => ['emoji' => '🥟', 'tag' => 'Siomay Gurih', 'color' => '#ecfdf5', 'border' => '#10b981'],
        12 => ['emoji' => '🧆', 'tag' => 'Mendoan Anget', 'color' => '#fef3c7', 'border' => '#d97706'],
        13 => ['emoji' => '🍘', 'tag' => 'Cireng Krispi', 'color' => '#e0f2fe', 'border' => '#0284c7'],
    ];
    return $stickers[$id] ?? ['emoji' => '🍽️', 'tag' => 'Spesial', 'color' => '#fce7f3', 'border' => '#ff4d88'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Lezat - Resto Icikiwir Gejesgejes</title>
    <!-- Google Fonts -->
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
            --bg-body: #faf7f8;
            --text-dark: #231625;
            --text-muted: #7e6777;
            --card-border: #f8e1ec;
            --green: #10b981;
            --radius-md: 18px;
            --radius-lg: 24px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-dark);
            min-height: 100vh;
            min-height: 100dvh;
            overflow-x: hidden;
        }

        /* Responsive Page Wrapper */
        .app-container {
            width: 100%;
            min-height: 100vh;
            min-height: 100dvh;
            background: var(--bg-body);
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            padding-bottom: 120px;
        }

        /* Sticky Full-Width Header (Konsisten di Pojok Kiri & Kanan) */
        .app-header {
            position: sticky;
            top: 0;
            z-index: 40;
            width: 100%;
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1.5px solid var(--pink-soft);
            padding: 14px clamp(16px, 3vw, 32px);
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 12px rgba(255, 77, 136, 0.06);
        }

        .back-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--pink-light);
            border: 1.5px solid var(--pink-soft);
            color: var(--pink-primary);
            font-size: 1.1rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .back-btn:hover {
            background: var(--pink-primary);
            border-color: var(--pink-primary);
            color: #fff;
        }

        .back-btn:active {
            transform: scale(0.92);
        }

        .header-title-box {
            text-align: center;
            padding: 0 10px;
        }

        .header-title-box h1 {
            font-size: clamp(1.1rem, 2vw, 1.4rem);
            font-weight: 800;
            color: var(--text-dark);
            line-height: 1.2;
        }

        .header-title-box span {
            color: var(--pink-primary);
        }

        .header-subtitle {
            font-size: clamp(0.72rem, 1.1vw, 0.85rem);
            color: var(--text-muted);
            font-weight: 500;
        }

        /* Tombol Masuk (Login Karyawan) */
        .login-btn {
            font-size: clamp(0.78rem, 1.1vw, 0.86rem);
            padding: 8px 16px;
            background: var(--pink-light);
            border: 1.5px solid var(--pink-soft);
            color: var(--pink-primary);
            border-radius: 999px;
            font-weight: 800;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(255, 77, 136, 0.08);
            transition: all 0.2s ease;
        }

        .login-btn:hover {
            background: var(--pink-primary);
            border-color: var(--pink-primary);
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(255, 77, 136, 0.28);
        }

        .login-btn:active {
            transform: scale(0.95);
        }

        /* Content Container (Melebar hingga 1140px di Desktop) */
        .content-wrapper {
            width: 100%;
            max-width: 1140px;
            padding: 0 clamp(16px, 3vw, 32px);
            display: flex;
            flex-direction: column;
        }

        /* Welcome Banner & Guidance */
        .menu-banner {
            margin: 18px 0 12px;
            background: linear-gradient(135deg, #fff0f5 0%, #ffe4ee 100%);
            border: 1.5px dashed var(--pink-primary);
            border-radius: var(--radius-md);
            padding: clamp(12px, 2vw, 18px) clamp(16px, 2.5vw, 24px);
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .banner-icon {
            font-size: clamp(2rem, 3vw, 2.5rem);
            animation: pulse 2s infinite;
            flex-shrink: 0;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .banner-text h3 {
            font-size: clamp(0.9rem, 1.4vw, 1.05rem);
            font-weight: 800;
            color: var(--pink-dark);
            margin-bottom: 2px;
        }

        .banner-text p {
            font-size: clamp(0.76rem, 1.2vw, 0.88rem);
            color: var(--text-muted);
            line-height: 1.4;
        }

        /* Filter Tabs */
        .category-tabs {
            display: flex;
            gap: 10px;
            padding: 6px 0 16px;
            overflow-x: auto;
            scrollbar-width: none;
        }

        .category-tabs::-webkit-scrollbar {
            display: none;
        }

        @media (min-width: 640px) {
            .category-tabs {
                flex-wrap: wrap;
                overflow-x: visible;
            }
        }

        .cat-tab {
            padding: 9px 18px;
            border-radius: 999px;
            font-size: clamp(0.8rem, 1.1vw, 0.88rem);
            font-weight: 700;
            background: #ffffff;
            border: 1.5px solid var(--pink-soft);
            color: var(--text-muted);
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.2s ease;
        }

        .cat-tab:hover {
            border-color: var(--pink-primary);
            color: var(--pink-primary);
        }

        .cat-tab.active {
            background: var(--pink-primary);
            color: #ffffff;
            border-color: var(--pink-primary);
            box-shadow: 0 4px 12px rgba(255, 77, 136, 0.35);
        }

        /* Responsive Menu Grid (1 Kolom Mobile -> 2-3 Kolom Desktop) */
        .menu-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(min(100%, 320px), 1fr));
            gap: clamp(14px, 2vw, 20px);
            width: 100%;
        }

        .menu-card {
            background: #ffffff;
            border-radius: var(--radius-md);
            border: 1.5px solid var(--card-border);
            padding: 16px;
            display: flex;
            gap: 14px;
            box-shadow: 0 4px 14px rgba(255, 105, 180, 0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
            position: relative;
        }

        .menu-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 24px rgba(255, 77, 136, 0.12);
            border-color: #ffc2d8;
        }

        .menu-card:active {
            transform: scale(0.99);
        }

        /* Sticker Container */
        .sticker-wrapper {
            width: 80px;
            height: 80px;
            flex-shrink: 0;
            border-radius: 18px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            box-shadow: 0 4px 10px rgba(0,0,0,0.06);
            border: 2px solid #fff;
        }

        .sticker-emoji {
            font-size: 2.3rem;
            line-height: 1;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.12));
        }

        .sticker-tag {
            position: absolute;
            bottom: -6px;
            font-size: 0.62rem;
            font-weight: 800;
            color: #fff;
            background: var(--pink-primary);
            padding: 2px 7px;
            border-radius: 8px;
            white-space: nowrap;
            border: 1px solid #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Menu Details */
        .menu-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-width: 0;
        }

        .menu-name {
            font-size: 1.02rem;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 4px;
        }

        .menu-desc {
            font-size: 0.8rem;
            color: var(--text-muted);
            line-height: 1.4;
            margin-bottom: 10px;
        }

        .menu-bottom-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-top: auto;
        }

        .menu-price {
            font-size: 0.98rem;
            font-weight: 800;
            color: var(--pink-primary);
        }

        /* Action Buttons */
        .btn-add {
            background: var(--pink-light);
            color: var(--pink-primary);
            border: 1.5px solid var(--pink-soft);
            padding: 7px 16px;
            font-size: 0.82rem;
            font-weight: 800;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap;
        }

        .btn-add:hover, .btn-add:active {
            background: var(--pink-primary);
            color: #fff;
            border-color: var(--pink-primary);
        }

        /* Quantity Controller (Plus, Minus, Delete) */
        .qty-controls {
            display: none;
            align-items: center;
            gap: 6px;
            background: var(--pink-light);
            padding: 4px 6px;
            border-radius: 24px;
            border: 1px solid var(--pink-soft);
        }

        .qty-btn {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: none;
            background: #ffffff;
            color: var(--pink-primary);
            font-weight: 800;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
            transition: transform 0.15s ease;
        }

        .qty-btn:active {
            transform: scale(0.9);
            background: var(--pink-soft);
        }

        .qty-val {
            font-size: 0.9rem;
            font-weight: 800;
            min-width: 22px;
            text-align: center;
            color: var(--text-dark);
        }

        .btn-trash-item {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: none;
            background: #fee2e2;
            color: #ef4444;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            margin-left: 2px;
        }

        /* Bottom Floating Order Bar (Full di Mobile, Kapsul Melayang di Desktop) */
        .cart-sticky-bar {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            background: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(10px);
            border-top: 1.5px solid var(--pink-soft);
            padding: 14px 20px;
            z-index: 50;
            display: none;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 -8px 24px rgba(255, 77, 136, 0.16);
        }

        @media (min-width: 640px) {
            .cart-sticky-bar {
                bottom: 20px;
                width: calc(100% - 32px);
                max-width: 680px;
                border-radius: 999px;
                border: 1.5px solid var(--pink-soft);
                padding: 14px 24px;
                box-shadow: 0 12px 32px rgba(255, 77, 136, 0.24);
            }
        }

        .cart-summary {
            display: flex;
            flex-direction: column;
        }

        .cart-count {
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--pink-dark);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .cart-total-price {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--pink-primary);
        }

        .btn-checkout {
            background: linear-gradient(135deg, #ff4d88 0%, #ff2a70 100%);
            color: #ffffff;
            border: none;
            padding: 14px 24px;
            font-size: 0.95rem;
            font-weight: 800;
            border-radius: 999px;
            cursor: pointer;
            box-shadow: 0 6px 18px rgba(255, 42, 112, 0.38);
            display: flex;
            align-items: center;
            gap: 8px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-checkout:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(255, 42, 112, 0.48);
        }

        .btn-checkout:active {
            transform: scale(0.96);
        }

        /* Modal Overlay (Bottom Sheet di Mobile -> Centered Dialog di Desktop) */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.55);
            backdrop-filter: blur(4px);
            z-index: 90;
            display: none;
            align-items: flex-end;
            justify-content: center;
        }

        .modal-content {
            background: #ffffff;
            width: 100%;
            max-width: 520px;
            border-radius: 28px 28px 0 0;
            padding: 24px 22px 32px;
            max-height: 88vh;
            overflow-y: auto;
            position: relative;
            animation: slideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            border-top: 4px solid var(--pink-primary);
        }

        @keyframes slideUp {
            from { transform: translateY(100%); }
            to { transform: translateY(0); }
        }

        @media (min-width: 640px) {
            .modal-overlay {
                align-items: center;
                padding: 20px;
            }
            .modal-content {
                border-radius: 28px;
                border: 1.5px solid var(--pink-soft);
                border-top: 4px solid var(--pink-primary);
                box-shadow: 0 24px 60px rgba(0, 0, 0, 0.25);
                animation: popIn 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            }
            @keyframes popIn {
                from { opacity: 0; transform: scale(0.94); }
                to { opacity: 1; transform: scale(1); }
            }
        }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1.5px solid var(--pink-soft);
        }

        .modal-title {
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .close-modal-btn {
            background: var(--pink-light);
            border: none;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            font-size: 1.1rem;
            color: var(--pink-dark);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Order Review Item List in Modal */
        .review-order-box {
            background: var(--pink-light);
            border-radius: 16px;
            padding: 12px 14px;
            margin-bottom: 18px;
            max-height: 190px;
            overflow-y: auto;
        }

        .review-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 6px 0;
            border-bottom: 1px dashed rgba(255, 77, 136, 0.25);
            font-size: 0.88rem;
        }

        .review-item:last-child {
            border-bottom: none;
        }

        .review-item-name {
            font-weight: 700;
            color: var(--text-dark);
        }

        .review-item-sub {
            font-weight: 800;
            color: var(--pink-primary);
        }

        /* Customer Info Form */
        .form-group {
            margin-bottom: 14px;
            text-align: left;
        }

        .form-group label {
            display: block;
            font-size: 0.84rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 6px;
        }

        .form-group label span {
            color: var(--pink-primary);
        }

        .form-input {
            width: 100%;
            padding: 13px 16px;
            border-radius: 14px;
            border: 1.5px solid #ffd1e1;
            font-size: 0.95rem;
            background: #fff;
            font-family: inherit;
            color: var(--text-dark);
            transition: all 0.2s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--pink-primary);
            box-shadow: 0 0 0 3px rgba(255, 77, 136, 0.18);
        }

        .form-row-grid {
            display: grid;
            grid-template-columns: 1fr 1.6fr;
            gap: 12px;
        }

        .btn-submit-order {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #ff4d88 0%, #ff2a70 100%);
            color: #fff;
            border: none;
            border-radius: 999px;
            font-size: 1.05rem;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 8px 24px rgba(255, 42, 112, 0.4);
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: transform 0.2s ease;
        }

        .btn-submit-order:active {
            transform: scale(0.97);
        }

        .btn-submit-order:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
    </style>
</head>
<body>

<div class="app-container">
    <!-- Header Full-Width -->
    <header class="app-header">
        <a href="index.php" class="back-btn" title="Kembali ke Beranda">←</a>
        <div class="header-title-box">
            <h1>Resto <span>Icikiwir</span></h1>
            <div class="header-subtitle">Pilih menu favoritmu di bawah ini</div>
        </div>
        <a href="login.php" class="login-btn" title="Login Karyawan">
            <span>Masuk</span>
        </a>
    </header>

    <!-- Main Responsive Content Wrapper -->
    <div class="content-wrapper">
        <!-- Welcome & Order Tip Banner -->
        <section class="menu-banner">
            <div class="banner-icon">🍜</div>
            <div class="banner-text">
                <h3>Pesan Mudah & Cepat!</h3>
                <p>Pilih porsi dengan klik tombol <b>+ Tambah</b>, lalu klik <b>Konfirmasi Pesanan</b> di bawah.</p>
            </div>
        </section>

        <!-- Filter Category Tabs -->
        <div class="category-tabs">
            <button class="cat-tab active" onclick="filterMenu('all', this)">Semua Menu</button>
            <button class="cat-tab" onclick="filterMenu('mie', this)">🍜 Aneka Mie</button>
            <button class="cat-tab" onclick="filterMenu('minuman', this)">🥤 Minuman Dingin</button>
            <button class="cat-tab" onclick="filterMenu('camilan', this)">🥟 Camilan Gurih</button>
        </div>

        <!-- Menu List (Responsive Grid) -->
        <main class="menu-list" id="menuContainer">
            <?php foreach ($menus as $m): 
                $id = (int)$m['id_menu'];
                $nama = htmlspecialchars($m['nama']);
                $desk = htmlspecialchars($m['desk']);
                $harga = (int)$m['harga'];
                $stk = getMenuSticker($id);

                // Klasifikasi kategori
                $cat = 'camilan';
                if (stripos($nama, 'Es') !== false || stripos($nama, 'Soda') !== false || stripos($nama, 'Teh') !== false) {
                    $cat = 'minuman';
                } elseif (stripos($nama, 'Mie') !== false) {
                    $cat = 'mie';
                }
            ?>
            <div class="menu-card" data-category="<?= $cat; ?>" data-id="<?= $id; ?>">
                <!-- Sticker Menu -->
                <div class="sticker-wrapper" style="background: <?= $stk['color']; ?>; border-color: <?= $stk['border']; ?>;">
                    <span class="sticker-emoji"><?= $stk['emoji']; ?></span>
                    <span class="sticker-tag" style="background: <?= $stk['border']; ?>;"><?= $stk['tag']; ?></span>
                </div>

                <!-- Detail Menu -->
                <div class="menu-info">
                    <div>
                        <h2 class="menu-name"><?= $nama; ?></h2>
                        <p class="menu-desc"><?= $desk; ?></p>
                    </div>
                    <div class="menu-bottom-row">
                        <div class="menu-price">Rp <?= number_format($harga, 0, ',', '.'); ?></div>
                        
                        <!-- Tombol Tambah Pesanan -->
                        <button class="btn-add" id="btnAdd-<?= $id; ?>" onclick="tambahItem(<?= $id; ?>, '<?= addslashes($nama); ?>', <?= $harga; ?>)">
                            <span>+</span> Tambah
                        </button>

                        <!-- Kontrol Jumlah (Kurang, Tambah, Hapus) -->
                        <div class="qty-controls" id="qtyBox-<?= $id; ?>">
                            <button class="qty-btn" onclick="kurangItem(<?= $id; ?>)">−</button>
                            <span class="qty-val" id="qtyText-<?= $id; ?>">1</span>
                            <button class="qty-btn" onclick="tambahItem(<?= $id; ?>, '<?= addslashes($nama); ?>', <?= $harga; ?>)">+</button>
                            <button class="btn-trash-item" title="Hapus pesanan ini" onclick="hapusItem(<?= $id; ?>)">🗑️</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </main>
    </div>

    <!-- Sticky Floating Bottom Cart Bar -->
    <div class="cart-sticky-bar" id="cartStickyBar">
        <div class="cart-summary">
            <div class="cart-count">
                <span>🛍️</span> <span id="cartTotalItems">0</span> item dipilih
            </div>
            <div class="cart-total-price" id="cartTotalPrice">Rp 0</div>
        </div>
        <button class="btn-checkout" onclick="bukaModalKonfirmasi()">
            <span>Konfirmasi Pesanan</span>
            <span>👉</span>
        </button>
    </div>
</div>

<!-- Modal Konfirmasi Pesanan Solid & Isi Data Meja/Pelanggan -->
<div class="modal-overlay" id="checkoutModal">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title">
                <span>📝</span> Konfirmasi Pesanan
            </div>
            <button class="close-modal-btn" onclick="tutupModalKonfirmasi()">&times;</button>
        </div>

        <!-- Ringkasan Item Pesanan -->
        <div class="review-order-box" id="modalReviewList">
            <!-- Diisi lewat Javascript -->
        </div>

        <!-- Form Masukkan Nomor Meja, Atas Nama, Kontak Email -->
        <form id="orderForm" onsubmit="kirimPesanan(event)">
            <div class="form-row-grid">
                <div class="form-group">
                    <label for="noMeja">No. Meja <span>*</span></label>
                    <input type="number" id="noMeja" class="form-input" placeholder="Misal: 3" min="1" max="99" required>
                </div>
                <div class="form-group">
                    <label for="namaCustom">Atas Nama <span>*</span></label>
                    <input type="text" id="namaCustom" class="form-input" placeholder="Nama Anda" maxlength="20" required>
                </div>
            </div>

            <div class="form-group">
                <label for="emailCustom">Kontak (Email) <span>*</span></label>
                <input type="email" id="emailCustom" class="form-input" placeholder="email@contoh.com" required>
            </div>

            <div class="form-group">
                <label for="catatanKhusus">Catatan Tambahan (Opsional)</label>
                <input type="text" id="catatanKhusus" class="form-input" placeholder="Contoh: Es sedikit, jangan terlalu pedas" maxlength="100">
            </div>

            <button type="submit" class="btn-submit-order" id="btnSubmitOrder">
                <span>🚀</span> KIRIM PESANAN SEKARANG
            </button>
        </form>
    </div>
</div>

<script>
// State keranjang pesanan
let cart = {}; 
// Struktur cart: { [id_menu]: { id_menu, nama, harga, jumlah, req } }

function updateUI(id) {
    const btnAdd = document.getElementById(`btnAdd-${id}`);
    const qtyBox = document.getElementById(`qtyBox-${id}`);
    const qtyText = document.getElementById(`qtyText-${id}`);

    if (cart[id] && cart[id].jumlah > 0) {
        if (btnAdd) btnAdd.style.display = 'none';
        if (qtyBox) qtyBox.style.display = 'inline-flex';
        if (qtyText) qtyText.textContent = cart[id].jumlah;
    } else {
        if (btnAdd) btnAdd.style.display = 'inline-flex';
        if (qtyBox) qtyBox.style.display = 'none';
    }

    renderStickyBar();
}

function tambahItem(id, nama, harga) {
    if (!cart[id]) {
        cart[id] = { id_menu: id, nama: nama, harga: harga, jumlah: 1, req: '' };
    } else {
        cart[id].jumlah += 1;
    }
    updateUI(id);
}

function kurangItem(id) {
    if (cart[id]) {
        cart[id].jumlah -= 1;
        if (cart[id].jumlah <= 0) {
            delete cart[id];
        }
    }
    updateUI(id);
}

function hapusItem(id) {
    if (cart[id]) {
        delete cart[id];
    }
    updateUI(id);
}

function formatRupiah(num) {
    return 'Rp ' + Number(num).toLocaleString('id-ID');
}

function renderStickyBar() {
    let totalItems = 0;
    let totalPrice = 0;

    for (const key in cart) {
        totalItems += cart[key].jumlah;
        totalPrice += (cart[key].harga * cart[key].jumlah);
    }

    const stickyBar = document.getElementById('cartStickyBar');
    const txtTotalItems = document.getElementById('cartTotalItems');
    const txtTotalPrice = document.getElementById('cartTotalPrice');

    if (totalItems > 0) {
        stickyBar.style.display = 'flex';
        txtTotalItems.textContent = totalItems;
        txtTotalPrice.textContent = formatRupiah(totalPrice);
    } else {
        stickyBar.style.display = 'none';
    }
}

function bukaModalKonfirmasi() {
    const reviewList = document.getElementById('modalReviewList');
    reviewList.innerHTML = '';

    let total = 0;
    for (const key in cart) {
        const item = cart[key];
        const subtotal = item.harga * item.jumlah;
        total += subtotal;

        const row = document.createElement('div');
        row.className = 'review-item';
        row.innerHTML = `
            <div>
                <span class="review-item-name">${item.nama}</span>
                <span style="font-size:0.75rem; color:#888;"> &times; ${item.jumlah}</span>
            </div>
            <div style="display:flex; align-items:center; gap:8px;">
                <span class="review-item-sub">${formatRupiah(subtotal)}</span>
                <span style="cursor:pointer; color:#ef4444;" onclick="hapusDariModal(${item.id_menu})" title="Hapus">✖</span>
            </div>
        `;
        reviewList.appendChild(row);
    }

    const totalRow = document.createElement('div');
    totalRow.style.paddingTop = '8px';
    totalRow.style.marginTop = '8px';
    totalRow.style.borderTop = '1.5px solid #ff4d88';
    totalRow.style.display = 'flex';
    totalRow.style.justifyContent = 'space-between';
    totalRow.style.fontWeight = '800';
    totalRow.innerHTML = `<span>Total Tagihan:</span><span style="color:#ff4d88; font-size:1.05rem;">${formatRupiah(total)}</span>`;
    reviewList.appendChild(totalRow);

    document.getElementById('checkoutModal').style.display = 'flex';
}

function hapusDariModal(id) {
    hapusItem(id);
    if (Object.keys(cart).length === 0) {
        tutupModalKonfirmasi();
    } else {
        bukaModalKonfirmasi();
    }
}

function tutupModalKonfirmasi() {
    document.getElementById('checkoutModal').style.display = 'none';
}

// Filter kategori
function filterMenu(cat, el) {
    document.querySelectorAll('.cat-tab').forEach(b => b.classList.remove('active'));
    el.classList.add('active');

    const cards = document.querySelectorAll('.menu-card');
    cards.forEach(card => {
        if (cat === 'all' || card.dataset.category === cat) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
}

// Kirim pesanan ke backend
async function kirimPesanan(e) {
    e.preventDefault();

    const noMeja = document.getElementById('noMeja').value.trim();
    const namaCustom = document.getElementById('namaCustom').value.trim();
    const emailCustom = document.getElementById('emailCustom').value.trim();
    const catatan = document.getElementById('catatanKhusus').value.trim();
    const btnSubmit = document.getElementById('btnSubmitOrder');

    if (Object.keys(cart).length === 0) {
        alert('Keranjang pesanan masih kosong!');
        return;
    }

    btnSubmit.disabled = true;
    btnSubmit.innerHTML = '<span>⏳</span> Memproses Pesanan...';

    // Format array items
    const items = Object.values(cart).map(item => ({
        id_menu: item.id_menu,
        jumlah: item.jumlah,
        req: catatan
    }));

    const payload = {
        nomor_meja: parseInt(noMeja, 10),
        nama_custom: namaCustom,
        email: emailCustom,
        items: items
    };

    try {
        const response = await fetch('backend/proses_pesanan.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(payload)
        });

        const result = await response.json();

        if (result.success) {
            // Berhasil: arahkan kembali ke index.php
            window.location.href = 'index.php?status=sukses';
        } else {
            alert('Gagal mengirim pesanan: ' + (result.message || 'Terjadi kesalahan sistem.'));
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = '<span>🚀</span> KIRIM PESANAN SEKARANG';
        }
    } catch (err) {
        alert('Koneksi terganggu. Silakan periksa jaringan dan coba lagi.');
        btnSubmit.disabled = false;
        btnSubmit.innerHTML = '<span>🚀</span> KIRIM PESANAN SEKARANG';
    }
}
</script>

</body>
</html>