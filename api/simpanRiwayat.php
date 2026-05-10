<?php
/**@var mysqli $koneksi */
require_once 'koneksi.php';
require_once 'session_handler.php';

if (!isset($_SESSION['id_pengguna'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'pesan' => 'Belum login']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

$id_pengguna     = $_SESSION['id_pengguna'];
$nama            = $_SESSION['nama'];
$kota            = $data['kota'] ?? '';
$tanggal_prediksi = $data['tanggal'] ?? '';
$kondisi         = $data['kondisi'] ?? '';
$suhu            = $data['suhu'] ?? 0;

if ($kota === '' || $tanggal_prediksi === '') {
    echo json_encode(['status' => 'error', 'pesan' => 'Data tidak lengkap']);
    exit();
}

$stmt = $koneksi->prepare(
    "INSERT INTO riwayat_prediksi (id_pengguna, nama, kota, tanggal_prediksi, kondisi, suhu) VALUES (?, ?, ?, ?, ?, ?)"
);
$stmt->bind_param("issssd", $id_pengguna, $nama, $kota, $tanggal_prediksi, $kondisi, $suhu);

if ($stmt->execute()) {
    echo json_encode(['status' => 'ok']);
} else {
    echo json_encode(['status' => 'error', 'pesan' => $stmt->error]);
}

$stmt->close();
?>