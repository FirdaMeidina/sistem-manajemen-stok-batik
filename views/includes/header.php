<?php include_once __DIR__ . '/../../config/session_check.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= isset($title) ? $title : 'Sistem Manajemen Stok Batik'; ?></title>

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="/sistem-manajemen-stok-batik/assets/css/style.css">
</head>

<body>
  <!-- ====== NAVBAR ====== -->
  <nav class="navbar navbar-expand-lg shadow-sm fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="/sistem-manajemen-stok-batik/index.php">
        <img src="/sistem-manajemen-stok-batik/assets/img/logo.png" alt="Logo">
        Batik Bali Lestari
      </a>

      <div class="ms-auto text-white d-flex align-items-center">
        <i class="fa-solid fa-user-circle me-2 fs-5"></i>
        <span><?= $_SESSION['nama_lengkap']; ?> (<?= $_SESSION['role']; ?>)</span>
        <a href="/sistem-manajemen-stok-batik/views/auth/logout.php" class="btn btn-sm btn-outline-light ms-3 d-flex align-items-center">
          <i class="fa-solid fa-user me-1"></i> Logout
        </a>
      </div>
    </div>
  </nav>

  <!-- ====== PAGE WRAPPER ====== -->
  <div class="layout-wrapper">
    <!-- Sidebar -->
    <aside class="sidebar">
      <?php include 'sidebar.php'; ?>
    </aside>

    <!-- Main Content -->
    <main class="main-content fade-in">
