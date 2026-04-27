<?php
require_once 'koneksi.php';
require_once 'session_handler.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit();
}

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
        $_SESSION['id_pengguna'] = $user['id'];
        $_SESSION['nama']        = $user['nama'];
        $_SESSION['email']       = $user['email'];
        $_SESSION['role']        = $user['role'];

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
?>