<?php
// Endpoint pemeriksaan aktivitas sesi & perpanjangan aktivitas aktif karyawan

require_once __DIR__ . '/session_check.php';

header('Content-Type: application/json; charset=utf-8');

// Cek apakah masih dalam batas waktu 5 menit
if (!isset($_SESSION['karyawan']) || empty($_SESSION['karyawan'])) {
    echo json_encode([
        'authenticated' => false,
        'reason'        => 'unauthorized',
        'message'       => 'Sesi belum login atau sudah keluar.'
    ]);
    exit();
}

$now = time();
$inactive_time = isset($_SESSION['last_activity']) ? ($now - $_SESSION['last_activity']) : 0;

if ($inactive_time > SESSION_TIMEOUT_SECONDS) {
    // Hangus karena 5 menit tanpa interaksi
    hancurkan_dan_redirect(true, 'timeout');
    exit();
}

// Jika request mengirimkan sinyal aktivitas ('action' = 'active'), perbarui timestamp
$action = $_GET['action'] ?? $_POST['action'] ?? '';
if ($action === 'touch') {
    $_SESSION['last_activity'] = $now;
    $remaining = SESSION_TIMEOUT_SECONDS;
} else {
    $remaining = max(0, SESSION_TIMEOUT_SECONDS - $inactive_time);
}

echo json_encode([
    'authenticated'     => true,
    'user'              => $_SESSION['karyawan'],
    'remaining_seconds' => $remaining,
    'token'             => $_SESSION['auth_token'] ?? ''
]);
?>