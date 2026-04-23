<?php
// proses/prosesRegister.php
// file ini menerima data dari form register.php dan menyimpan ke database

session_start();

// koneksi ke database
require_once 'koneksi.php';

// ambil data dari form
$nama            = trim($_POST['nama']);
$tanggal_lahir   = $_POST['tanggal_lahir'];
$email           = trim($_POST['email']);
$password        = $_POST['password'];
$konfirm_password = $_POST['konfirm_password'];

// validasi nama
if (empty($nama)) {
    header("Location: register.php?error=Nama lengkap tidak boleh kosong.");
    exit();
}

// validasi tanggal lahir
if (empty($tanggal_lahir)) {
    header("Location: register.php?error=Tanggal lahir tidak boleh kosong.");
    exit();
}

// validasi email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: register.php?error=Format email tidak valid.");
    exit();
}

// validasi panjang password
if (strlen($password) < 8) {
    header("Location: register.php?error=Kata sandi minimal 8 karakter.");
    exit();
}

// validasi konfirmasi password
if ($password !== $konfirm_password) {
    header("Location: register.php?error=Konfirmasi kata sandi tidak cocok.");
    exit();
}

// cek apakah email sudah terdaftar
$cekEmail = mysqli_query($koneksi, "SELECT id FROM pengguna WHERE email = '$email'");
if (mysqli_num_rows($cekEmail) > 0) {
    header("Location: register.php?error=Email sudah terdaftar, gunakan email lain.");
    exit();
}

// enkripsi password sebelum disimpan (password_hash = tidak bisa dibaca di database)
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

// simpan data ke database
$query = "INSERT INTO pengguna (nama, tanggal_lahir, email, password, role) 
          VALUES ('$nama', '$tanggal_lahir', '$email', '$passwordHash', 'pengguna')";

if (mysqli_query($koneksi, $query)) {
    // berhasil → redirect ke login dengan pesan sukses
    header("Location: login.php?sukses=Akun berhasil dibuat! Silakan masuk.");
    exit();
} else {
    header("Location: register.php?error=Gagal menyimpan data, coba lagi.");
    exit();
}

mysqli_close($koneksi);
?>