<?php
require_once __DIR__ . '/backend/session_check.php';
require_once __DIR__ . '/backend/koneksi.php';

pastikan_karyawan_login(false);

$authToken = $_SESSION['auth_token'] ?? '';

$query = "
    SELECT p.id_custom, p.nama_custom, p.nomor_meja, p.email, p.tanggal, p.selesai,
           d.id_detail, d.jumlah, d.req,
           m.nama AS nama_menu, m.harga, m.id_menu
    FROM pelanggan p
    LEFT JOIN detail_pesanan d ON p.id_custom = d.id_custom
    LEFT JOIN menu m ON d.id_menu = m.id_menu
    ORDER BY p.id_custom DESC, d.id_detail ASC
";

$result = $conn->query($query);
$orders = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $id = $row['id_custom'];
        if (!isset($orders[$id])) {
            $orders[$id] = [
                'id_custom'    => $row['id_custom'],
                'nama_custom'  => $row['nama_custom'],
                'nomor_meja'   => $row['nomor_meja'],
                'email'        => $row['email'],
                'tanggal'      => $row['tanggal'],
                'selesai'      => (int)$row['selesai'],
                'total_harga'  => 0,
                'catatan_list' => [],
                'items'        => []
            ];
        }
        if ($row['id_detail']) {
            $subtotal = $row['harga'] * $row['jumlah'];
            $orders[$id]['total_harga'] += $subtotal;

            $reqText = trim((string)($row['req'] ?? ''));
            if ($reqText !== '' && !in_array($reqText, $orders[$id]['catatan_list'], true)) {
                $orders[$id]['catatan_list'][] = $reqText;
            }

            $orders[$id]['items'][] = [
                'id_detail' => $row['id_detail'],
                'id_menu'   => $row['id_menu'],
                'nama_menu' => $row['nama_menu'],
                'harga'     => (int)$row['harga'],
                'jumlah'    => (int)$row['jumlah'],
                'subtotal'  => $subtotal
            ];
        }
    }
}

$total_pesanan = count($orders);
$total_pending = 0;
$total_selesai = 0;
foreach ($orders as $o) {
    if ($o['selesai'] == 1) {
        $total_selesai++;
    } else {
        $total_pending++;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pesanan - Resto Icikiwir Gejesgejes</title>
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
            --bg-body: #faf6f8;
            --text-dark: #231625;
            --text-muted: #735d6e;
            --border: #f8deea;
            --green: #10b981;
            --green-light: #ecfdf5;
            --orange: #f59e0b;
            --orange-light: #fffbeb;
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

        .dashboard-container {
            width: 100%;
            min-height: 100vh;
            min-height: 100dvh;
            background: var(--bg-body);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-bottom: 48px;
        }

        .dash-header {
            position: sticky;
            top: 0;
            z-index: 40;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1.5px solid var(--pink-soft);
            padding: 14px clamp(16px, 3vw, 32px);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            box-shadow: 0 2px 12px rgba(255, 77, 136, 0.06);
        }

        .brand-title h1 {
            font-size: clamp(1.1rem, 2vw, 1.4rem);
            font-weight: 800;
            color: var(--text-dark);
        }

        .brand-title h1 span {
            color: var(--pink-primary);
        }

        .user-tag {
            font-size: clamp(0.75rem, 1.1vw, 0.85rem);
            color: var(--text-muted);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 4px;
            margin-top: 2px;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            flex-shrink: 0;
        }

        .btn-action-icon {
            padding: 8px 14px;
            border-radius: 999px;
            font-size: clamp(0.78rem, 1.1vw, 0.85rem);
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
            white-space: nowrap;
        }

        .btn-home {
            background: #ffffff;
            color: var(--text-dark);
            border: 1px solid var(--pink-soft);
        }

        .btn-home:hover {
            background: var(--pink-light);
            color: var(--pink-primary);
        }

        .btn-refresh {
            background: var(--pink-light);
            color: var(--pink-primary);
            border: 1px solid var(--pink-soft);
        }

        .btn-refresh:hover {
            background: var(--pink-primary);
            color: #ffffff;
        }

        .btn-logout {
            background: #fee2e2;
            color: #ef4444;
            border: 1px solid #fecaca;
        }

        .btn-logout:hover {
            background: #ef4444;
            color: #ffffff;
        }

        .session-timer-bar {
            width: 100%;
            background: #fff0f6;
            border-bottom: 1px solid var(--pink-soft);
            padding: 8px clamp(16px, 3vw, 32px);
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--pink-dark);
        }

        .timer-badge {
            background: #ffffff;
            border: 1px solid var(--pink-soft);
            padding: 2px 10px;
            border-radius: 12px;
            font-family: monospace;
            font-size: 0.84rem;
            color: var(--pink-primary);
        }

        .content-wrapper {
            width: 100%;
            max-width: 1200px;
            padding: 0 clamp(16px, 3vw, 32px);
            display: flex;
            flex-direction: column;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: clamp(10px, 2vw, 18px);
            padding: 20px 0 10px;
        }

        .stat-card {
            background: #ffffff;
            border: 1.5px solid var(--pink-soft);
            border-radius: 18px;
            padding: clamp(12px, 2vw, 18px);
            text-align: center;
            box-shadow: 0 4px 12px rgba(255, 77, 136, 0.05);
            transition: transform 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .stat-card.total { border-color: var(--pink-soft); }
        .stat-card.pending { border-color: #fed7aa; }
        .stat-card.done { border-color: #a7f3d0; }

        .stat-num {
            font-size: clamp(1.4rem, 2.5vw, 1.9rem);
            font-weight: 800;
            line-height: 1.2;
        }

        .stat-card.total .stat-num { color: var(--pink-primary); }
        .stat-card.pending .stat-num { color: #f97316; }
        .stat-card.done .stat-num { color: #10b981; }

        .stat-label {
            font-size: clamp(0.72rem, 1.1vw, 0.85rem);
            font-weight: 700;
            color: var(--text-muted);
            margin-top: 3px;
        }

        .filter-nav {
            display: flex;
            gap: 10px;
            padding: 10px 0 18px;
            overflow-x: auto;
            scrollbar-width: none;
        }

        .filter-nav::-webkit-scrollbar {
            display: none;
        }

        @media (min-width: 640px) {
            .filter-nav {
                flex-wrap: wrap;
                overflow-x: visible;
            }
        }

        .filter-pill {
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

        .filter-pill:hover {
            border-color: var(--pink-primary);
            color: var(--pink-primary);
        }

        .filter-pill.active {
            background: var(--pink-primary);
            color: #ffffff;
            border-color: var(--pink-primary);
            box-shadow: 0 4px 10px rgba(255, 77, 136, 0.3);
        }

        .orders-feed {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(min(100%, 340px), 1fr));
            gap: clamp(16px, 2vw, 22px);
            width: 100%;
            align-items: stretch;
        }

        .order-card {
            background: #ffffff;
            border-radius: 20px;
            border: 1.5px solid var(--border);
            box-shadow: 0 4px 16px rgba(255, 77, 136, 0.07);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .order-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 26px rgba(255, 77, 136, 0.13);
        }

        .order-card.done-state {
            border-color: #a7f3d0;
            background: #fafdfc;
        }

        .card-top {
            padding: 14px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px dashed var(--pink-soft);
            background: #fffdfd;
            gap: 8px;
        }

        .order-id-badge {
            font-size: 0.85rem;
            font-weight: 800;
            color: var(--pink-dark);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .table-pill {
            background: var(--pink-primary);
            color: #ffffff;
            font-size: 0.78rem;
            font-weight: 800;
            padding: 4px 12px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap;
        }

        .card-body {
            padding: 16px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .customer-meta {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 8px;
            margin-bottom: 12px;
        }

        .customer-meta > div:first-child {
            min-width: 0;
            flex: 1;
        }

        .cust-name {
            font-size: 1.05rem;
            font-weight: 800;
            color: var(--text-dark);
            word-break: break-word;
        }

        .cust-email {
            font-size: 0.76rem;
            color: var(--text-muted);
            margin-top: 2px;
            display: flex;
            align-items: center;
            gap: 4px;
            word-break: break-all;
        }

        .order-time {
            font-size: 0.72rem;
            color: #9ca3af;
            font-weight: 600;
            text-align: right;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .items-box {
            background: var(--pink-light);
            border-radius: 14px;
            padding: 12px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .items-box.done-bg {
            background: #f0fdf4;
        }

        .item-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
            font-size: 0.84rem;
            padding: 6px 0;
            border-bottom: 1px solid rgba(255, 77, 136, 0.12);
        }

        .item-row:last-child {
            border-bottom: none;
        }

        .item-name-qty {
            font-weight: 700;
            color: var(--text-dark);
            word-break: break-word;
        }

        .item-subtotal {
            font-weight: 800;
            color: var(--pink-dark);
            white-space: nowrap;
            flex-shrink: 0;
        }

        .order-total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 10px;
            margin-top: 8px;
            border-top: 1px dashed rgba(255, 77, 136, 0.25);
            font-size: 0.95rem;
            font-weight: 800;
        }

        .order-total-val {
            font-size: 1.1rem;
            color: var(--pink-primary);
        }

        .order-note-text {
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px dashed rgba(255, 77, 136, 0.22);
            font-size: 0.8rem;
            color: #7c2d12;
            font-weight: 600;
            display: flex;
            align-items: flex-start;
            gap: 6px;
            line-height: 1.4;
            word-break: break-word;
        }

        .order-note-text strong {
            font-weight: 800;
            color: var(--pink-dark);
        }

        .card-footer {
            padding: 12px 16px;
            background: #ffffff;
            border-top: 1.5px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
        }

        .status-badge {
            font-size: 0.75rem;
            font-weight: 800;
            padding: 6px 12px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            white-space: nowrap;
        }

        .status-badge.pending {
            background: var(--orange-light);
            color: var(--orange);
            border: 1px solid #fde68a;
        }

        .status-badge.done {
            background: var(--green-light);
            color: var(--green);
            border: 1px solid #a7f3d0;
        }

        .btn-toggle-status {
            padding: 10px 16px;
            font-size: 0.82rem;
            font-weight: 800;
            border-radius: 999px;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.06);
            white-space: nowrap;
        }

        .btn-toggle-status.mark-done {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff;
        }

        .btn-toggle-status.mark-done:hover {
            filter: brightness(1.05);
            transform: translateY(-1px);
        }

        .btn-toggle-status.mark-done:active {
            transform: scale(0.95);
        }

        .btn-toggle-status.mark-pending {
            background: #f3f4f6;
            color: #4b5563;
            border: 1px solid #d1d5db;
        }

        .btn-toggle-status.mark-pending:hover {
            background: #e5e7eb;
        }

        .btn-toggle-status.mark-pending:active {
            transform: scale(0.95);
        }

        .empty-orders {
            grid-column: 1 / -1;
            text-align: center;
            padding: clamp(48px, 8vw, 80px) 20px;
            background: #ffffff;
            border-radius: 24px;
            border: 2px dashed var(--pink-soft);
            margin-top: 8px;
        }

        .empty-orders-icon {
            font-size: 3.2rem;
            margin-bottom: 12px;
        }

        .empty-orders h3 {
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 6px;
        }

        .empty-orders p {
            font-size: 0.85rem;
            color: var(--text-muted);
            max-width: 400px;
            margin: 0 auto;
            line-height: 1.5;
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <header class="dash-header">
        <div class="brand-title">
            <h1>List Pesanan <span>Icikiwir</span></h1>
            <div class="user-tag">
                <span>👩‍🍳</span> Karyawan: <b><?= htmlspecialchars($_SESSION['karyawan']); ?></b>
            </div>
        </div>

        <div class="header-actions">
            <a href="index.php" class="btn-action-icon btn-home" title="Kembali ke Beranda" onclick="isIntendedNavigation = true;">
                <span>🏠</span> Beranda
            </a>
            <a href="pesanan.php" class="btn-action-icon btn-refresh" title="Perbarui Daftar" onclick="isIntendedNavigation = true;">
                <span>🔄</span> Refresh
            </a>
            <button onclick="logoutManual()" class="btn-action-icon btn-logout" title="Keluar">
                <span>🚪</span> Logout
            </button>
        </div>
    </header>

    <div class="session-timer-bar">
        <span>🛡️ Kunci Otomatis (5 Mnt Idle):</span>
        <span class="timer-badge" id="timerBadge">05:00</span>
    </div>

    <div class="content-wrapper">
        <section class="stats-grid">
            <div class="stat-card total">
                <div class="stat-num" id="statTotal"><?= $total_pesanan; ?></div>
                <div class="stat-label">Total Masuk</div>
            </div>
            <div class="stat-card pending">
                <div class="stat-num" id="statPending"><?= $total_pending; ?></div>
                <div class="stat-label">Belum Selesai</div>
            </div>
            <div class="stat-card done">
                <div class="stat-num" id="statDone"><?= $total_selesai; ?></div>
                <div class="stat-label">Selesai</div>
            </div>
        </section>

        <nav class="filter-nav">
            <button class="filter-pill active" onclick="filterPesanan('all', this)">Semua (<span id="pillTotal"><?= $total_pesanan; ?></span>)</button>
            <button class="filter-pill" onclick="filterPesanan('pending', this)">⏳ Belum Selesai (<span id="pillPending"><?= $total_pending; ?></span>)</button>
            <button class="filter-pill" onclick="filterPesanan('done', this)">✅ Selesai (<span id="pillDone"><?= $total_selesai; ?></span>)</button>
        </nav>

        <main class="orders-feed" id="ordersList">
            <?php if (empty($orders)): ?>
                <div class="empty-orders">
                    <div class="empty-orders-icon">📦</div>
                    <h3>Belum Ada Pesanan</h3>
                    <p>Pesanan yang dibuat oleh customer di menu.php akan langsung tampil di sini secara otomatis.</p>
                </div>
            <?php else: ?>
                <?php foreach ($orders as $order): 
                    $id_custom = (int)$order['id_custom'];
                    $isDone = ($order['selesai'] === 1);
                    $statusClass = $isDone ? 'done' : 'pending';
                    $statusText = $isDone ? 'Selesai (True)' : 'Belum Selesai (False)';
                ?>
                <article class="order-card <?= $isDone ? 'done-state' : ''; ?>" id="orderCard-<?= $id_custom; ?>" data-status="<?= $isDone ? 'done' : 'pending'; ?>">
                    <div class="card-top">
                        <div class="order-id-badge">
                            <span>🧾</span> #ORDER-<?= str_pad($id_custom, 4, '0', STR_PAD_LEFT); ?>
                        </div>
                        <div class="table-pill">
                            <span>🍽️</span> Meja <?= htmlspecialchars($order['nomor_meja']); ?>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="customer-meta">
                            <div>
                                <div class="cust-name">👤 <?= htmlspecialchars($order['nama_custom']); ?></div>
                                <div class="cust-email">✉️ <?= htmlspecialchars($order['email']); ?></div>
                            </div>
                            <div class="order-time">
                                <?= date('d M, H:i', strtotime($order['tanggal'])); ?> WIB
                            </div>
                        </div>

                        <div class="items-box <?= $isDone ? 'done-bg' : ''; ?>">
                            <div>
                                <?php foreach ($order['items'] as $item): ?>
                                <div class="item-row">
                                    <div>
                                        <span class="item-name-qty"><?= htmlspecialchars($item['nama_menu']); ?> &times; <?= $item['jumlah']; ?></span>
                                    </div>
                                    <div class="item-subtotal">Rp <?= number_format($item['subtotal'], 0, ',', '.'); ?></div>
                                </div>
                                <?php endforeach; ?>
                            </div>

                            <div>
                                <div class="order-total-row">
                                    <span>Total Tagihan:</span>
                                    <span class="order-total-val">Rp <?= number_format($order['total_harga'], 0, ',', '.'); ?></span>
                                </div>

                                <?php if (!empty($order['catatan_list'])): ?>
                                <div class="order-note-text">
                                    <div><strong>Catatan:</strong> <?= htmlspecialchars(implode('; ', $order['catatan_list'])); ?></div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <footer class="card-footer">
                        <div class="status-badge <?= $statusClass; ?>" id="badge-<?= $id_custom; ?>">
                            <span><?= $isDone ? '✅' : '⏳'; ?></span>
                            <span id="badgeText-<?= $id_custom; ?>"><?= $statusText; ?></span>
                        </div>

                        <div id="btnContainer-<?= $id_custom; ?>">
                            <?php if (!$isDone): ?>
                            <button class="btn-toggle-status mark-done" onclick="ubahStatus(<?= $id_custom; ?>, 1)">
                                <span>Selesaikan</span> ➔
                            </button>
                            <?php else: ?>
                            <button class="btn-toggle-status mark-pending" onclick="ubahStatus(<?= $id_custom; ?>, 0)">
                                <span>🔄 Batal Selesai</span>
                            </button>
                            <?php endif; ?>
                        </div>
                    </footer>
                </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </main>
    </div>
</div>

<script>
const SERVER_AUTH_TOKEN = "<?= $authToken; ?>";
let isIntendedNavigation = false;

(function checkTabIsolation() {
    const existingTabToken = sessionStorage.getItem('resto_tab_active');
    if (!existingTabToken) {
        sessionStorage.setItem('resto_tab_active', SERVER_AUTH_TOKEN);
    } else if (existingTabToken !== SERVER_AUTH_TOKEN) {
        sessionStorage.setItem('resto_tab_active', SERVER_AUTH_TOKEN);
    }
})();

window.addEventListener('pagehide', function(event) {
    if (!isIntendedNavigation) {
        navigator.sendBeacon('backend/auth.php?action=logout');
    }
});

window.addEventListener('beforeunload', function(event) {
    if (!isIntendedNavigation) {
        navigator.sendBeacon('backend/auth.php?action=logout');
    }
});

const TIMEOUT_LIMIT = 300;
let remainingSeconds = TIMEOUT_LIMIT;
let lastHeartbeatTouch = Date.now();

function updateTimerDisplay() {
    const minutes = Math.floor(remainingSeconds / 60);
    const seconds = remainingSeconds % 60;
    const badge = document.getElementById('timerBadge');
    if (badge) {
        badge.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        if (remainingSeconds <= 30) {
            badge.style.color = '#ef4444';
            badge.style.borderColor = '#ef4444';
        } else {
            badge.style.color = 'var(--pink-primary)';
            badge.style.borderColor = 'var(--pink-soft)';
        }
    }
}

const countdownInterval = setInterval(() => {
    remainingSeconds--;
    if (remainingSeconds <= 0) {
        clearInterval(countdownInterval);
        logoutOtomatis('timeout');
    } else {
        updateTimerDisplay();
    }
}, 1000);

function resetAktivitas() {
    remainingSeconds = TIMEOUT_LIMIT;
    updateTimerDisplay();

    const now = Date.now();
    if (now - lastHeartbeatTouch > 15000) {
        lastHeartbeatTouch = now;
        fetch('backend/heartbeat.php?action=touch', { method: 'GET' })
            .then(res => res.json())
            .then(data => {
                if (!data.authenticated) {
                    logoutOtomatis('timeout');
                }
            })
            .catch(() => {});
    }
}

['mousemove', 'mousedown', 'keydown', 'touchstart', 'scroll', 'click'].forEach(evt => {
    window.addEventListener(evt, resetAktivitas, { passive: true });
});

function logoutOtomatis(reason) {
    isIntendedNavigation = true;
    sessionStorage.removeItem('resto_tab_active');
    window.location.href = `backend/auth.php?action=logout&reason=${encodeURIComponent(reason)}`;
}

function logoutManual() {
    if (confirm('Apakah Anda yakin ingin keluar dari portal pesanan?')) {
        isIntendedNavigation = true;
        sessionStorage.removeItem('resto_tab_active');
        window.location.href = 'backend/auth.php?action=logout';
    }
}

async function ubahStatus(id_custom, statusTarget) {
    resetAktivitas();

    const btnContainer = document.getElementById(`btnContainer-${id_custom}`);
    const badge = document.getElementById(`badge-${id_custom}`);
    const card = document.getElementById(`orderCard-${id_custom}`);

    if (btnContainer) {
        btnContainer.innerHTML = '<span style="font-size:0.75rem; color:#888;">Menyimpan...</span>';
    }

    try {
        const response = await fetch('backend/update_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                id_custom: id_custom,
                selesai: statusTarget
            })
        });

        if (response.status === 401) {
            alert('Sesi Anda telah berakhir karena tidak ada aktivitas selama 5 menit. Silakan login kembali!');
            logoutOtomatis('timeout');
            return;
        }

        const result = await response.json();

        if (result.success) {
            if (statusTarget === 1) {
                card.dataset.status = 'done';
                card.classList.add('done-state');
                badge.className = 'status-badge done';
                badge.innerHTML = '<span>✅</span><span>Selesai (True)</span>';
                btnContainer.innerHTML = `
                    <button class="btn-toggle-status mark-pending" onclick="ubahStatus(${id_custom}, 0)">
                        <span>🔄 Batal Selesai</span>
                    </button>
                `;
            } else {
                card.dataset.status = 'pending';
                card.classList.remove('done-state');
                badge.className = 'status-badge pending';
                badge.innerHTML = '<span>⏳</span><span>Belum Selesai (False)</span>';
                btnContainer.innerHTML = `
                    <button class="btn-toggle-status mark-done" onclick="ubahStatus(${id_custom}, 1)">
                        <span>Selesaikan</span> ➔
                    </button>
                `;
            }
            updateStatistikCount();
        } else {
            alert('Gagal mengubah status: ' + (result.message || 'Terjadi kesalahan sistem.'));
            isIntendedNavigation = true;
            window.location.reload();
        }
    } catch (err) {
        alert('Gagal menghubungi server.');
        isIntendedNavigation = true;
        window.location.reload();
    }
}

function updateStatistikCount() {
    const allCards = document.querySelectorAll('.order-card');
    let pendingCount = 0;
    let doneCount = 0;
    const totalCount = allCards.length;

    allCards.forEach(c => {
        if (c.dataset.status === 'done') {
            doneCount++;
        } else {
            pendingCount++;
        }
    });

    const statTotal = document.getElementById('statTotal');
    const statPending = document.getElementById('statPending');
    const statDone = document.getElementById('statDone');
    if (statTotal) statTotal.textContent = totalCount;
    if (statPending) statPending.textContent = pendingCount;
    if (statDone) statDone.textContent = doneCount;

    const pillTotal = document.getElementById('pillTotal');
    const pillPending = document.getElementById('pillPending');
    const pillDone = document.getElementById('pillDone');
    if (pillTotal) pillTotal.textContent = totalCount;
    if (pillPending) pillPending.textContent = pendingCount;
    if (pillDone) pillDone.textContent = doneCount;
}

function filterPesanan(mode, el) {
    resetAktivitas();
    document.querySelectorAll('.filter-pill').forEach(p => p.classList.remove('active'));
    el.classList.add('active');

    const cards = document.querySelectorAll('.order-card');
    cards.forEach(c => {
        if (mode === 'all' || mode === c.dataset.status) {
            c.style.display = 'flex';
        } else {
            c.style.display = 'none';
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => { isIntendedNavigation = true; });
    });
});

window.addEventListener('keydown', function(e) {
    if ((e.which || e.keyCode) === 116 || (e.ctrlKey && (e.which || e.keyCode) === 82)) {
        isIntendedNavigation = true;
    }
});

let cachedOrderCount = <?= $total_pesanan; ?>;
setInterval(async () => {
    try {
        const res = await fetch('backend/get_pesanan.php?filter=all');
        const data = await res.json();
        if (data.success && data.orders && data.orders.length !== cachedOrderCount) {
            isIntendedNavigation = true;
            window.location.reload();
        }
    } catch (e) {}
}, 10000);
</script>

</body>
</html>