<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resto Icikiwir Gejesgejes - Selamat Datang!</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Fredoka:wght@600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --pink-primary: #ff4d88;
            --pink-hover: #ff2a70;
            --pink-light: #fff0f5;
            --pink-soft: #ffe4ee;
            --pink-dark: #b81d52;
            --white: #ffffff;
            --text-dark: #2d1e2f;
            --text-muted: #755566;
            --shadow: 0 12px 32px rgba(255, 77, 136, 0.28);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--pink-light);
            color: var(--text-dark);
            min-height: 100vh;
            min-height: 100dvh;
            overflow-x: hidden;
        }

        .page-container {
            position: relative;
            width: 100%;
            min-height: 100vh;
            min-height: 100dvh;
            background: #fff;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
        }

        .bg-blur-image {
            position: fixed;
            inset: -20px;
            background-image: url('assets/images.jpg');
            background-size: cover;
            background-position: center;
            filter: blur(2px) brightness(0.9);
            transform: scale(1.06);
            z-index: 1;
        }

        .bg-overlay {
            position: fixed;
            inset: 0;
            background: linear-gradient(
                180deg,
                rgba(255, 240, 245, 0.72) 0%,
                rgba(255, 255, 255, 0.84) 55%,
                rgba(255, 228, 238, 0.94) 100%
            );
            z-index: 2;
        }

        .top-nav {
            position: relative;
            z-index: 10;
            width: 100%;
            padding: clamp(18px, 3vw, 24px) clamp(20px, 5vw, 24px);
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        .login-link {
            font-size: clamp(0.85rem, 1.1vw, 0.95rem);
            font-weight: 700;
            color: var(--pink-dark);
            text-decoration: none;
            background: rgba(255, 255, 255, 0.92);
            padding: clamp(8px, 1vw, 10px) clamp(16px, 2vw, 22px);
            border-radius: 999px;
            border: 1.5px solid var(--pink-soft);
            box-shadow: 0 4px 12px rgba(255, 77, 136, 0.15);
            transition: all 0.25s ease;
            backdrop-filter: blur(6px);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .login-link:hover, .login-link:active {
            background: var(--pink-primary);
            color: #fff;
            border-color: var(--pink-primary);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(255, 77, 136, 0.3);
        }

        .hero-content {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 680px;
            text-align: center;
            padding: clamp(20px, 4vw, 48px) clamp(24px, 5vw, 56px);
            margin: auto 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        @keyframes bounceSoft {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        .brand-badge {
            background: rgba(255, 255, 255, 0.95);
            color: var(--pink-primary);
            font-size: clamp(0.75rem, 1.1vw, 0.88rem);
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 6px 18px;
            border-radius: 30px;
            margin-bottom: clamp(14px, 2vw, 18px);
            box-shadow: 0 4px 14px rgba(255, 77, 136, 0.2);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .restaurant-name {
            font-family: 'Fredoka', 'Plus Jakarta Sans', sans-serif;
            font-size: clamp(2.1rem, 5vw, 3.6rem);
            line-height: 1.15;
            color: var(--text-dark);
            margin-bottom: clamp(10px, 1.5vw, 16px);
            text-shadow: 0 2px 10px rgba(255, 255, 255, 0.8);
        }

        .restaurant-name span {
            color: var(--pink-primary);
        }

        .tagline {
            font-size: clamp(0.95rem, 1.5vw, 1.15rem);
            color: var(--text-muted);
            line-height: 1.5;
            max-width: clamp(290px, 45vw, 480px);
            margin-bottom: clamp(28px, 4vw, 38px);
            font-weight: 500;
        }

        .btn-pesan-big {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            max-width: clamp(270px, 32vw, 340px);
            padding: clamp(16px, 2vw, 20px) 28px;
            background: linear-gradient(135deg, #ff5e98 0%, #ff2a70 100%);
            color: var(--white);
            font-size: clamp(1.2rem, 1.8vw, 1.4rem);
            font-weight: 800;
            text-decoration: none;
            border-radius: 999px;
            box-shadow: 0 14px 30px rgba(255, 42, 112, 0.42);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 2px solid rgba(255, 255, 255, 0.4);
            letter-spacing: 0.5px;
        }

        .btn-pesan-big:hover {
            transform: scale(1.04) translateY(-3px);
            box-shadow: 0 18px 36px rgba(255, 42, 112, 0.52);
        }

        .btn-pesan-big:active {
            transform: scale(0.97);
        }

        .btn-pesan-big .icon-bag {
            font-size: clamp(1.35rem, 2vw, 1.6rem);
        }

        .footer-note {
            position: relative;
            z-index: 10;
            padding: 20px 16px clamp(20px, 3vw, 28px);
            font-size: clamp(0.8rem, 1.1vw, 0.9rem);
            color: var(--text-muted);
            text-align: center;
        }

        @media (min-width: 768px) {
            .hero-content {
                background: rgba(255, 255, 255, 0.52);
                backdrop-filter: blur(14px);
                -webkit-backdrop-filter: blur(14px);
                border: 1.5px solid rgba(255, 255, 255, 0.85);
                border-radius: 36px;
                box-shadow: 0 20px 50px rgba(255, 77, 136, 0.14);
            }
        }

        .toast-popup {
            position: fixed;
            top: 24px;
            left: 50%;
            transform: translateX(-50%);
            background: #ffffff;
            color: var(--text-dark);
            padding: 14px 22px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.18);
            border-left: 5px solid #10b981;
            z-index: 100;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            animation: slideDown 0.4s ease forwards;
            width: max-content;
            max-width: 90vw;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translate(-50%, -20px); }
            to { opacity: 1; transform: translate(-50%, 0); }
        }
    </style>
</head>
<body>

<div class="page-container">
    <div class="bg-blur-image"></div>
    <div class="bg-overlay"></div>

    <?php if (isset($_GET['status']) && $_GET['status'] === 'sukses'): ?>
    <div class="toast-popup" id="toast">
        <span style="font-size: 1.4rem;">🎉</span>
        <div>
            <div>Pesanan Berhasil Dikirim!</div>
            <div style="font-size: 0.75rem; color: #6b7280; font-weight: 400;">
                Koki kami siap memasak hidangan lezatmu.
            </div>
        </div>
    </div>
    <script>
        setTimeout(() => {
            const t = document.getElementById('toast');
            if (t) {
                t.style.opacity = '0';
                t.style.transition = 'opacity 0.5s ease';
                setTimeout(() => t.remove(), 500);
            }
        }, 4500);
    </script>
    <?php endif; ?>

    <header class="top-nav">
        <a href="login.php" class="login-link">
            <span>Masuk</span>
        </a>
    </header>

    <main class="hero-content">
        <div class="brand-badge">✨ Sensasi Gurih & Pedas ✨</div>
        <h1 class="restaurant-name">
            Resto <span>Icikiwir</span> Gejesgejes
        </h1>
        <p class="tagline">
            Kuliner viral legendaris! Pesan langsung dari genggamanmu tanpa ribet.
        </p>

        <!-- Tombol "Pesan" Besar di Tengah -->
        <a href="menu.php" class="btn-pesan-big">
            <span>PESAN</span>
        </a>
    </main>

    <footer class="footer-note">
        Available at UPN Veteran "Yogyakarta" &bull; Campus 2
    </footer>
</div>

</body>
</html>