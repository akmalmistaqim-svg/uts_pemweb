<?php
// prosesAdmin.php
session_start();

if (!isset($_SESSION['nama']) || $_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}

$koneksi = mysqli_connect("localhost", "root", "", "cuacaku");
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

$aksi = $_GET['aksi'] ?? '';
$id   = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    header("Location: ../admin.php?error=ID tidak valid.");
    exit();
}

if ($aksi === 'jadikan_admin') {
    $query = "UPDATE pengguna SET role = 'admin' WHERE id = $id";
    if (mysqli_query($koneksi, $query)) {
        header("Location: ../admin.php?sukses=Pengguna berhasil dijadikan admin.");
    } else {
        header("Location: ../admin.php?error=Gagal mengubah role.");
    }

} elseif ($aksi === 'jadikan_pengguna') {
    $query = "UPDATE pengguna SET role = 'pengguna' WHERE id = $id";
    if (mysqli_query($koneksi, $query)) {
        header("Location: ../admin.php?sukses=Admin berhasil dijadikan pengguna biasa.");
    } else {
        header("Location: ../admin.php?error=Gagal mengubah role.");
    }

} elseif ($aksi === 'hapus') {
    $query = "DELETE FROM pengguna WHERE id = $id";
    if (mysqli_query($koneksi, $query)) {
        header("Location: ../admin.php?sukses=Pengguna berhasil dihapus.");
    } else {
        header("Location: ../admin.php?error=Gagal menghapus pengguna.");
    }

} else {
    header("Location: ../admin.php?error=Aksi tidak dikenali.");
}

mysqli_close($koneksi);
exit();
?>