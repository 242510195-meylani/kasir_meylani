<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Madu Jaya - Home</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/adminlte/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/adminlte/dist/css/adminlte.min.css?v=3.2.0">
  <style>
    /* Menyesuaikan warna tombol menu aktif agar persis oranye */
    .nav-sidebar .nav-item .nav-link.active {
      background-color: #fd7e14 !important;
      color: #fff !important;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar Atas -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Dropdown Profil Administrator -->
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false" style="color: #343a40;">
          Administrator
        </a>
        <div class="dropdown-menu dropdown-menu-right shadow-sm border-0">
          <form action="/logout" method="POST" class="px-2 py-1">
            @csrf
            <button type="submit" class="btn btn-danger btn-sm btn-block">
              <i class="fas fa-sign-out-alt mr-1"></i> Logout
            </button>
          </form>
        </div>
      </li>

      <!-- Tombol Fullscreen -->
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo (Menggunakan ikon logo AdminLTE yang sama seperti halaman transaksi) -->
    <a href="/home" class="brand-link d-flex align-items-center" style="padding-left: 15px;">
      <img src="/adminlte/dist/img/AdminLTELogo.png" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-bold text-white ml-2">Madu Jaya</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          
          <li class="nav-item">
            <a href="/home" class="nav-link active">
              <i class="nav-icon fas fa-home"></i>
              <p>Home</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="/transaksi" class="nav-link">
              <i class="nav-icon fas fa-cash-register"></i>
              <p>Transaksi</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="/produk" class="nav-link">
              <i class="nav-icon fas fa-box"></i>
              <p>Produk</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="/stok" class="nav-link">
              <i class="nav-icon fas fa-warehouse"></i>
              <p>Stok</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="/kategori" class="nav-link">
              <i class="nav-icon fas fa-tags"></i>
              <p>Kategori</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="/pelanggan" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>Pelanggan</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="/user" class="nav-link">
              <i class="nav-icon fas fa-user-cog"></i>
              <p>User</p>
            </a>
          </li>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="background-color: #f4f6f9;">
    
    <!-- Content Header -->
    <div class="content-header" style="padding-bottom: 5px;">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12">
            <h1 class="m-0" style="font-size: 1.6rem; font-weight: 500;">
              <i class="fas fa-home"></i> Home
            </h1>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Garis pembatas oranye -->
    <div style="border-top: 3px solid #fd7e14; width: 100%;"></div>

    <!-- Main content -->
    <section class="content mt-3">
      <div class="container-fluid">
        
        <div class="card" style="border: 1px solid #dee2e6; box-shadow: none;">
          <div class="card-body" style="padding: 20px;">
            <p class="mb-0 text-dark" style="font-size: 1rem;">Content</p>
          </div>
        </div>

      </div>
    </section>
  </div>

  <!-- Main Footer -->
  <footer class="main-footer text-muted" style="font-size: 0.9rem;">
    <strong>Copyright &copy; 24 <a href="#" class="text-info">Madu Jaya</a>.</strong> All right reserved.
  </footer>

</div>

<!-- REQUIRED SCRIPTS -->
<script src="/adminlte/plugins/jquery/jquery.min.js"></script>
<script src="/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/adminlte/dist/js/adminlte.min.js?v=3.2.0"></script>
</body>
</html>