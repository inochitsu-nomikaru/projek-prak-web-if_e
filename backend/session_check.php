<?php
// Modul Pengaman Sesi & Validasi Karyawan Resto Icikiwir Gejesgejes

// 1. Konfigurasi keamanan Cookie & Sesi PHP
if (session_status() === PHP_SESSION_NONE) {
    if (!headers_sent()) {
        // Cookie akan hangus begitu browser / tab ditutup (lifetime = 0)
        session_set_cookie_params([
            'lifetime' => 0,
            'path'     => '/',
            'domain'   => '',
            'secure'   => false, // true jika menggunakan HTTPS
            'httponly' => true,  // Mencegah akses cookie lewat script XSS
            'samesite' => 'Lax'
        ]);
    }
    session_start();
}

// Konst waktu timeout: 5 Menit (300 detik)
define('SESSION_TIMEOUT_SECONDS', 300);

function pastikan_karyawan_login($is_api = false) {
    // A. Cek apakah ada data session karyawan
    if (!isset($_SESSION['karyawan']) || empty($_SESSION['karyawan'])) {
        hancurkan_dan_redirect($is_api, 'unauthorized');
        exit();
    }

    // B. Cek Anti Session Hijacking via User-Agent
    if (isset($_SESSION['user_agent']) && $_SESSION['user_agent'] !== ($_SERVER['HTTP_USER_AGENT'] ?? '')) {
        hancurkan_dan_redirect($is_api, 'invalid_agent');
        exit();
    }

    // C. Cek Timeout 5 Menit Tanpa Aktivitas
    $now = time();
    if (isset($_SESSION['last_activity'])) {
        $inactive_time = $now - $_SESSION['last_activity'];
        if ($inactive_time > SESSION_TIMEOUT_SECONDS) {
            // Sesi sudah kadaluarsa (lebih dari 5 menit tanpa aktivitas)
            hancurkan_dan_redirect($is_api, 'timeout');
            exit();
        }
    }

    // Perbarui waktu aktivitas terakhir
    $_SESSION['last_activity'] = $now;
}

function hancurkan_dan_redirect($is_api, $reason) {
    // Bersihkan semua data sesi
    $_SESSION = [];

    // Hapus cookie sesi dari browser
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    session_destroy();

    if ($is_api) {
        http_response_code(401);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success'   => false,
            'logged_out'=> true,
            'reason'    => $reason,
            'message'   => ($reason === 'timeout') 
                ? 'Sesi telah berakhir karena 5 menit tanpa aktivitas. Silakan login kembali.'
                : 'Sesi tidak valid atau telah berakhir.'
        ]);
        exit();
    } else {
        $redirectUrl = 'login.php?error=' . urlencode($reason);
        header("Location: " . $redirectUrl);
        exit();
    }
}
?>