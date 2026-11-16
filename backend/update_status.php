<?php
require_once __DIR__ . '/session_check.php';
require_once __DIR__ . '/koneksi.php';

// Pastikan hanya karyawan login yang aktif (belum timeout) yang bisa mengubah status
pastikan_karyawan_login(true);

header('Content-Type: application/json; charset=utf-8');

$rawInput = file_get_contents('php://input');
$jsonData = json_decode($rawInput, true);

$id_custom = 0;
$status = null;

if (is_array($jsonData)) {
    $id_custom = intval($jsonData['id_custom'] ?? 0);
    if (isset($jsonData['selesai'])) {
        $status = intval($jsonData['selesai']);
    }
} else {
    $id_custom = intval($_POST['id_custom'] ?? $_GET['id_custom'] ?? 0);
    if (isset($_POST['selesai'])) {
        $status = intval($_POST['selesai']);
    } elseif (isset($_GET['selesai'])) {
        $status = intval($_GET['selesai']);
    }
}

if ($id_custom <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID Pelanggan tidak valid!']);
    exit();
}

// Jika status tidak dikirimkan secara eksplisit, toggle status yang ada
if ($status === null) {
    $cek = $conn->prepare("SELECT selesai FROM pelanggan WHERE id_custom = ?");
    $cek->bind_param("i", $id_custom);
    $cek->execute();
    $res = $cek->get_result();
    if ($row = $res->fetch_assoc()) {
        $status = ($row['selesai'] == 1) ? 0 : 1;
    } else {
        echo json_encode(['success' => false, 'message' => 'Pesanan tidak ditemukan!']);
        exit();
    }
    $cek->close();
} else {
    $status = ($status == 1) ? 1 : 0;
}

// Update status pesanan dengan prepared statement (Anti SQL Injection)
$stmt = $conn->prepare("UPDATE pelanggan SET selesai = ? WHERE id_custom = ?");
$stmt->bind_param("ii", $status, $id_custom);

if ($stmt->execute()) {
    $stmt->close();
    // Jika diakses via link GET konvensional (non-AJAX)
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
        header("Location: ../pesanan.php");
        exit();
    }
    echo json_encode([
        'success'   => true,
        'message'   => 'Status pesanan berhasil diperbarui!',
        'id_custom' => $id_custom,
        'selesai'   => $status
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal memperbarui status di database.']);
}
?>