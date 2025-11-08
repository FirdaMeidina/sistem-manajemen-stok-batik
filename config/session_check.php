<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: /sistem-manajemen-stok-batik/views/auth/login.php");
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");
    exit;
}
?>
