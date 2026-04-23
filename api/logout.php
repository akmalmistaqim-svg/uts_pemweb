<?php
// proses/logout.php
// menghapus semua session dan redirect ke halaman login

session_start();
session_destroy(); // hapus semua session

header("Location: login.php");
exit();
?>