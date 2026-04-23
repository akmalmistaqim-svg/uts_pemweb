<?php
// simpanKota.php
// menyimpan kota yang dipilih pengguna ke session
// dipanggil lewat fetch dari cuaca.js

session_start();

if (isset($_GET['kota']) && !empty($_GET['kota'])) {
    $_SESSION['kota_dicek'] = trim($_GET['kota']);
}
?>