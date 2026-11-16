<?php
require_once __DIR__ . '/session_check.php';
require_once __DIR__ . '/koneksi.php';

// Pastikan hanya karyawan terverifikasi
pastikan_karyawan_login(true);

header('Content-Type: application/json; charset=utf-8');

$filter = $_GET['filter'] ?? 'all'; // all, pending, done

$query = "
    SELECT p.id_custom, p.nama_custom, p.nomor_meja, p.email, p.tanggal, p.selesai,
           d.id_detail, d.jumlah, d.req,
           m.nama AS nama_menu, m.harga, m.id_menu
    FROM pelanggan p
    LEFT JOIN detail_pesanan d ON p.id_custom = d.id_custom
    LEFT JOIN menu m ON d.id_menu = m.id_menu
";

if ($filter === 'pending') {
    $query .= " WHERE p.selesai = 0";
} elseif ($filter === 'done') {
    $query .= " WHERE p.selesai = 1";
}

$query .= " ORDER BY p.id_custom DESC, d.id_detail ASC";

$result = $conn->query($query);
$orders = [];

while ($row = $result->fetch_assoc()) {
    $id = $row['id_custom'];
    if (!isset($orders[$id])) {
        $orders[$id] = [
            'id_custom'   => $row['id_custom'],
            'nama_custom' => $row['nama_custom'],
            'nomor_meja'  => $row['nomor_meja'],
            'email'       => $row['email'],
            'tanggal'     => $row['tanggal'],
            'selesai'     => (int)$row['selesai'],
            'total_harga' => 0,
            'items'       => []
        ];
    }
    if ($row['id_detail']) {
        $subtotal = $row['harga'] * $row['jumlah'];
        $orders[$id]['total_harga'] += $subtotal;
        $orders[$id]['items'][] = [
            'id_detail' => $row['id_detail'],
            'id_menu'   => $row['id_menu'],
            'nama_menu' => $row['nama_menu'],
            'harga'     => $row['harga'],
            'jumlah'    => $row['jumlah'],
            'req'       => $row['req'],
            'subtotal'  => $subtotal
        ];
    }
}

echo json_encode([
    'success' => true,
    'orders'  => array_values($orders)
]);
?>