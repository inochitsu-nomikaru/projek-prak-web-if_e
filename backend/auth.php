<?php
// Autentikasi Karyawan & Pengelolaan Sesi Aman (Anti SQL Injection & Anti Hijacking)

require_once __DIR__ . '/session_check.php';
require_once __DIR__ . '/koneksi.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$reason = $_GET['reason'] ?? $_POST['reason'] ?? '';

// 1. PROSES LOGOUT (Manual, Timeout, atau Tab Closed)
if ($action === 'logout') {
    $_SESSION = [];

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

    // Jika request dikirim melalui navigator.sendBeacon atau fetch background
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) || (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false)) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => true, 'message' => 'Sesi berhasil diakhiri.']);
        exit();
    }

    $redirectUrl = '../login.php';
    if (!empty($reason)) {
        $redirectUrl .= '?error=' . urlencode($reason);
    }
    header("Location: " . $redirectUrl);
    exit();
}

// 2. PROSES LOGIN KARYAWAN (ANTI SQL INJECTION)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $raw_username = $_POST['username'] ?? '';
    $raw_password = $_POST['password'] ?? '';

    // Sanitasi dasar: hapus whitespace depan/belakang dan karakter null byte berbahaya
    $username = str_replace(chr(0), '', trim($raw_username));
    $password = str_replace(chr(0), '', trim($raw_password));

    // Validasi panjang input (mencegah buffer overflow / payload alomani)
    if (empty($username) || empty($password)) {
        header("Location: ../login.php?error=" . urlencode("Username dan password wajib diisi!"));
        exit();
    }

    if (mb_strlen($username) > 20 || mb_strlen($password) > 32) {
        header("Location: ../login.php?error=" . urlencode("Kombinasi login tidak valid!"));
        exit();
    }

    // PENCEGAHAN SQL INJECTION:
    $query = "SELECT username, password FROM account WHERE BINARY username = ? LIMIT 1";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        // Jangan pernah menampilkan error MySQL mentah ke user
        error_log("Database error prepare: " . $conn->error);
        header("Location: ../login.php?error=" . urlencode("Terjadi gangguan sistem. Silakan coba lagi."));
        exit();
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    $login_success = false;
    $db_username = '';

    if ($row = $result->fetch_assoc()) {
        $db_password = $row['password'];

        // Verifikasi password secara aman
        if (password_verify($password, $db_password) || hash_equals((string)$db_password, (string)$password)) {
            $login_success = true;
            $db_username = $row['username'];
        }
    }
    $stmt->close();

    if ($login_success) {
        // Regenerasi ID sesi untuk mencegah Session Fixation attack
        session_regenerate_id(true);

        // Buat token unik khusus untuk sesi dan tab ini
        $auth_token = bin2hex(random_bytes(32));

        $_SESSION['karyawan']      = $db_username;
        $_SESSION['auth_token']    = $auth_token;
        $_SESSION['user_agent']    = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $_SESSION['last_activity'] = time();
        $_SESSION['login_time']    = time();

        header("Location: ../pesanan.php");
        exit();
    } else {
        // Gagal login: berikan pesan generik tanpa membocorkan apakah username atau password yang salah
        header("Location: ../login.php?error=" . urlencode("Username atau password salah!"));
        exit();
    }
} else {
    header("Location: ../login.php");
    exit();
}
?>