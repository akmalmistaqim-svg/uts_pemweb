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
        $_SESSION['id_pengguna'] = $user['id'];
        $_SESSION['nama']        = $user['nama'];
        $_SESSION['email']       = $user['email'];
        $_SESSION['role']        = $user['role'];

        // arahkan berdasarkan role
        if ($user['role'] === 'admin') {
            session_write_close();
            header("Location: /api/admin.php");
        } else {
            session_write_close();
            header("Location: /api/dashboard.php");
        }
        exit();
    } else {
        header("Location: /api/login.php?error=Kata sandi salah.");
        exit();
    }
} else {
    header("Location: /api/login.php?error=Email tidak terdaftar.");
    exit();
}

mysqli_close($koneksi);
?>