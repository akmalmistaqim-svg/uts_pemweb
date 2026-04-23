<?php
// prosesLogin.php
session_start();

// koneksi ke database
require_once 'koneksi.php';

$email    = trim($_POST['email']);
$password = $_POST['password'];

if (empty($email) || empty($password)) {
    header("Location: login.php?error=Email dan kata sandi wajib diisi.");
    exit();
}

$query = "SELECT * FROM pengguna WHERE email = '$email'";
$hasil = mysqli_query($koneksi, $query);

if (mysqli_num_rows($hasil) === 1) {
    $user = mysqli_fetch_assoc($hasil);

    if (password_verify($password, $user['password'])) {
        // simpan id, nama, email, dan role ke session
       // Hapus semua $_SESSION
        // Ganti pakai setcookie:
        setcookie('id_pengguna', $user['id'], time() + 3600, '/', '', true, true);
        setcookie('nama', $user['nama'], time() + 3600, '/', '', true, true);
        setcookie('email', $user['email'], time() + 3600, '/', '', true, true);
        setcookie('role', $user['role'], time() + 3600, '/', '', true, true);

        // arahkan berdasarkan role
        if ($user['role'] === 'admin') {
            header("Location: admin.php");
        } else {
            header("Location: dashboard.php");
        }
        exit();
    } else {
        header("Location: login.php?error=Kata sandi salah.");
        exit();
    }
} else {
    header("Location: login.php?error=Email tidak terdaftar.");
    exit();
}

mysqli_close($koneksi);
?>