<?php
require_once 'koneksi.php';
require_once 'session_handler.php';

if (isset($_GET['kota']) && !empty($_GET['kota'])) {
    $_SESSION['kota_dicek'] = trim($_GET['kota']);
}
?>