<?php
/**@var mysqli $koneksi */
// buatAdmin.php
// jalankan file ini SEKALI di browser: localhost/webcuaca/buatAdmin.php
// setelah admin terbuat, HAPUS file ini dari folder!

require_once 'koneksi.php';

// data akun admin — bisa diganti sesuai kebutuhan
$nama          = "Administrator";
$tanggal_lahir = "2000-01-01";
$email         = "admin@cuacaku.com";
$password      = "admin12345";
$role          = "admin";

// cek apakah email admin sudah ada
$cek = mysqli_query($koneksi, "SELECT id FROM pengguna WHERE email = '$email'");
if (mysqli_num_rows($cek) > 0) {
    echo "⚠️ Akun admin dengan email <b>$email</b> sudah ada!";
    exit();
}

// enkripsi password
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

// simpan ke database
$query = "INSERT INTO pengguna (nama, tanggal_lahir, email, password, role)
          VALUES ('$nama', '$tanggal_lahir', '$email', '$passwordHash', '$role')";

if (mysqli_query($koneksi, $query)) {
    echo "✅ Akun admin berhasil dibuat!<br><br>";
    echo "<b>Email:</b> $email<br>";
    echo "<b>Password:</b> $password<br><br>";
    echo "<b style='color:red'>⚠️ PENTING: Hapus file buatAdmin.php dari folder sekarang!</b><br><br>";
    echo "<a href='login.php'>→ Ke halaman login</a>";
} else {
    echo "❌ Gagal membuat akun admin: " . mysqli_error($koneksi);
}

mysqli_close($koneksi);
?>