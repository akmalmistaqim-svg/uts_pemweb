<?php
require_once 'koneksi.php';
require_once 'session_handler.php';

session_destroy();

header("Location: login.php");
exit();
?>