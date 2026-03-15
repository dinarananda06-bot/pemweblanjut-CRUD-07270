<?php
include 'koneksi.php';

// Ambil parameter halaman
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Judul halaman berdasarkan parameter
$page_titles = [
    'dashboard'   => 'Dashboard',
    'data_barang' => 'Data Barang',
];

$page_title = $page_titles[$page] ?? 'Dashboard';
?>

<?php include 'includes/header.php'; ?>

<div class="content-wrapper">
    <?php include 'includes/menu.php'; ?>

    <main class="main-content">

        <!-- Header Halaman -->
        <div class="page-header">
            <h2><?php echo $page_title; ?></h2>
            <div class="breadcrumb">
                <a href="index.php"><i class="fas fa-home"></i> Home</a>
                <i class="fas fa-chevron-right"></i>
                <span><?php echo $page_title; ?></span>
            </div>
        </div>

        <!-- Notifikasi Session -->
        <?php if (isset($_SESSION['pesan'])): ?>
            <div class="alert alert-<?php echo $_SESSION['tipe'] == 'success' ? 'success' : 'error'; ?>">
                <i class="fas fa-<?php echo $_SESSION['tipe'] == 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
                <?php echo $_SESSION['pesan']; unset($_SESSION['pesan']); unset($_SESSION['tipe']); ?>
            </div>
        <?php endif; ?>

        <!-- Konten Halaman -->
        <div class="content">
            <?php
            switch ($page) {
                case 'dashboard':
                    include 'pages/dashboard.php';
                    break;
                case 'data_barang':
                    include 'pages/data_barang.php';
                    break;
                default:
                    include 'pages/dashboard.php';
            }
            ?>
        </div>

    </main>
</div>

<?php include 'includes/footer.php'; ?>
