<?php
// Tentukan halaman aktif
$current_page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
?>

<nav class="sidebar">
    <div class="sidebar-brand">
        <h3><i class="fas fa-boxes"></i> Inventaris</h3>
        <p>Manajemen Barang</p>
    </div>

    <ul class="sidebar-menu">
        <li>
            <a href="index.php" class="<?php echo $current_page == 'dashboard' ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="index.php?page=data_barang" class="<?php echo $current_page == 'data_barang' ? 'active' : ''; ?>">
                <i class="fas fa-box"></i> Data Barang
            </a>
        </li>
    </ul>
</nav>
