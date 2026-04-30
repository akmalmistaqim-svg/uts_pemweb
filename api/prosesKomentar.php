<?php
/**@var mysqli $koneksi */
require_once 'koneksi.php';
require_once 'session_handler.php';

if (!isset($_SESSION['id_pengguna'])) {
    header("Location: login.php");
    exit();
}

$id_pengguna  = $_SESSION['id_pengguna'];
$nama         = $_SESSION['nama'];
$kota         = trim($_POST['kota']);
$rating       = (int) $_POST['rating'];
$isi_komentar = trim($_POST['isi_komentar']);

if (empty($kota)) {
    header("Location: kabar_sekitar.php?error=Kota tidak ditemukan, cek prediksi cuaca dulu.");
    exit();
}
if ($rating < 1 || $rating > 5) {
    header("Location: kabar_sekitar.php?error=Rating tidak valid.");
    exit();
}
if (empty($isi_komentar)) {
    header("Location: kabar_sekitar.php?error=Komentar tidak boleh kosong.");
    exit();
}

$query = "INSERT INTO komentar (id_pengguna, nama, kota, rating, isi_komentar)
          VALUES ('$id_pengguna', '$nama', '$kota', '$rating', '$isi_komentar')";

if (mysqli_query($koneksi, $query)) {
    header("Location: kabar_sekitar.php");
} else {
    header("Location: kabar_sekitar.php?error=Gagal mengirim kabar, coba lagi.");
}

mysqli_close($koneksi);
exit();
?>