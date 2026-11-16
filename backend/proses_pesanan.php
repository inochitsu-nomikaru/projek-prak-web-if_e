<?php
session_start();
require_once __DIR__ . '/koneksi.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Metode request tidak diizinkan.']);
    exit();
}

// Baca input baik dari JSON body maupun form POST
$rawInput = file_get_contents('php://input');
$jsonData = json_decode($rawInput, true);

$nama_custom = '';
$nomor_meja = 0;
$email = '';
$items = [];

if (is_array($jsonData)) {
    $nama_custom = trim($jsonData['nama_custom'] ?? '');
    $nomor_meja = intval($jsonData['nomor_meja'] ?? 0);
    $email = trim($jsonData['email'] ?? '');
    $items = $jsonData['items'] ?? [];
} else {
    $nama_custom = trim($_POST['nama_custom'] ?? '');
    $nomor_meja = intval($_POST['nomor_meja'] ?? 0);
    $email = trim($_POST['email'] ?? '');
    $rawItems = $_POST['items'] ?? '[]';
    $items = is_string($rawItems) ? json_decode($rawItems, true) : $rawItems;
}

// Validasi
if (empty($nama_custom)) {
    echo json_encode(['success' => false, 'message' => 'Nama pemesan wajib diisi!']);
    exit();
}

if ($nomor_meja <= 0) {
    echo json_encode(['success' => false, 'message' => 'Nomor meja tidak valid!']);
    exit();
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Alamat email tidak valid!']);
    exit();
}

// Filter item pesanan yang jumlahnya > 0
$validItems = [];
if (is_array($items)) {
    foreach ($items as $item) {
        $id_menu = intval($item['id_menu'] ?? 0);
        $jumlah = intval($item['jumlah'] ?? 0);
        $req = substr(trim($item['req'] ?? ''), 0, 100);
        if ($id_menu > 0 && $jumlah > 0) {
            $validItems[] = [
                'id_menu' => $id_menu,
                'jumlah' => $jumlah,
                'req' => $req
            ];
        }
    }
}

if (empty($validItems)) {
    echo json_encode(['success' => false, 'message' => 'Keranjang pesanan masih kosong!']);
    exit();
}

// Potong nama jika lebih dari 20 karakter sesuai kolom database
$nama_custom = mb_substr($nama_custom, 0, 20);
$email = mb_substr($email, 0, 100);

// Mulai Transaksi Database
$conn->begin_transaction();

try {
    $tanggal = date('Y-m-d H:i:s');
    $selesai = 0; // default false

    $stmtPelanggan = $conn->prepare("INSERT INTO pelanggan (nama_custom, nomor_meja, email, tanggal, selesai) VALUES (?, ?, ?, ?, ?)");
    $stmtPelanggan->bind_param("sissi", $nama_custom, $nomor_meja, $email, $tanggal, $selesai);
    $stmtPelanggan->execute();

    $id_custom = $conn->insert_id;

    $stmtDetail = $conn->prepare("INSERT INTO detail_pesanan (jumlah, req, id_menu, id_custom) VALUES (?, ?, ?, ?)");

    foreach ($validItems as $v) {
        $stmtDetail->bind_param("isii", $v['jumlah'], $v['req'], $v['id_menu'], $id_custom);
        $stmtDetail->execute();
    }

    $conn->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Pesanan berhasil dikirim ke dapur Resto Icikiwir!',
        'id_custom' => $id_custom,
        'nama_custom' => $nama_custom,
        'nomor_meja' => $nomor_meja
    ]);
} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Gagal menyimpan pesanan: ' . $e->getMessage()
    ]);
}
?>
