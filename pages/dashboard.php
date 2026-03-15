<?php
// Statistik total barang
$total_barang   = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM barang"))['total'];
$barang_aktif   = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM barang WHERE status='aktif'"))['total'];
$barang_nonaktif = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM barang WHERE status='nonaktif'"))['total'];
$stok_menipis   = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM barang WHERE stok <= 10"))['total'];
?>

<!-- Kartu Statistik -->
<div class="stats-grid">
    <div class="stat-card blue">
        <div class="stat-icon"><i class="fas fa-boxes"></i></div>
        <div class="stat-info">
            <h4><?php echo $total_barang; ?></h4>
            <p>Total Barang</p>
        </div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
        <div class="stat-info">
            <h4><?php echo $barang_aktif; ?></h4>
            <p>Barang Aktif</p>
        </div>
    </div>
    <div class="stat-card orange">
        <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="stat-info">
            <h4><?php echo $stok_menipis; ?></h4>
            <p>Stok Menipis (&le;10)</p>
        </div>
    </div>
    <div class="stat-card red">
        <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
        <div class="stat-info">
            <h4><?php echo $barang_nonaktif; ?></h4>
            <p>Barang Nonaktif</p>
        </div>
    </div>
</div>

<!-- Tabel Data Terbaru -->
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-clock"></i> Barang Terbaru</h3>
        <a href="index.php?page=data_barang" class="btn btn-primary btn-sm">
            <i class="fas fa-list"></i> Lihat Semua
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Harga</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query  = "SELECT * FROM barang ORDER BY created_at DESC LIMIT 5";
                    $result = mysqli_query($koneksi, $query);
                    if (mysqli_num_rows($result) > 0):
                        while ($row = mysqli_fetch_assoc($result)):
                    ?>
                    <tr>
                        <td><code><?php echo htmlspecialchars($row['kode_barang']); ?></code></td>
                        <td><?php echo htmlspecialchars($row['nama_barang']); ?></td>
                        <td><span class="badge badge-info"><?php echo htmlspecialchars($row['kategori']); ?></span></td>
                        <td><?php echo $row['stok']; ?></td>
                        <td>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                        <td>
                            <?php if ($row['status'] == 'aktif'): ?>
                                <span class="badge badge-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr>
                        <td colspan="6" style="text-align:center; color:#888; padding:20px;">
                            Belum ada data barang.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
