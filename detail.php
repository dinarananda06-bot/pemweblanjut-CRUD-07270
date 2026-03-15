<?php
include 'koneksi.php';

$page_title = "Detail Barang";

// Ambil ID dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Validasi ID
if ($id <= 0) {
    $_SESSION['pesan'] = "ID barang tidak valid!";
    $_SESSION['tipe']  = "error";
    header("Location: index.php?page=data_barang");
    exit();
}

// Ambil data barang
$query  = "SELECT * FROM barang WHERE id = $id";
$result = mysqli_query($koneksi, $query);
$barang = mysqli_fetch_assoc($result);

// Jika tidak ditemukan
if (!$barang) {
    $_SESSION['pesan'] = "Barang tidak ditemukan!";
    $_SESSION['tipe']  = "error";
    header("Location: index.php?page=data_barang");
    exit();
}
?>

<?php include 'includes/header.php'; ?>

<div class="content-wrapper">
    <?php include 'includes/menu.php'; ?>

    <main class="main-content">

        <div class="page-header">
            <h2>Detail Barang</h2>
            <div class="breadcrumb">
                <a href="index.php"><i class="fas fa-home"></i> Home</a>
                <i class="fas fa-chevron-right"></i>
                <a href="index.php?page=data_barang">Data Barang</a>
                <i class="fas fa-chevron-right"></i>
                <span>Detail Barang</span>
            </div>
        </div>

        <div class="content">
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-info-circle"></i> Informasi Barang</h3>
                    <div style="display:flex; gap:8px;">
                        <a href="edit.php?id=<?php echo $barang['id']; ?>" class="btn btn-success btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="index.php?page=data_barang" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="detail-grid">

                        <div class="detail-item">
                            <label>Kode Barang</label>
                            <p><code style="font-size:15px;"><?php echo htmlspecialchars($barang['kode_barang']); ?></code></p>
                        </div>

                        <div class="detail-item">
                            <label>Nama Barang</label>
                            <p><?php echo htmlspecialchars($barang['nama_barang']); ?></p>
                        </div>

                        <div class="detail-item">
                            <label>Kategori</label>
                            <p><span class="badge badge-info"><?php echo htmlspecialchars($barang['kategori']); ?></span></p>
                        </div>

                        <div class="detail-item">
                            <label>Status</label>
                            <p>
                                <?php if ($barang['status'] == 'aktif'): ?>
                                    <span class="badge badge-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Nonaktif</span>
                                <?php endif; ?>
                            </p>
                        </div>

                        <div class="detail-item">
                            <label>Stok</label>
                            <p>
                                <?php if ($barang['stok'] <= 10): ?>
                                    <span style="color:#c62828; font-weight:700;"><?php echo $barang['stok']; ?> unit</span>
                                    <span class="badge badge-danger" style="margin-left:6px;">Stok Menipis</span>
                                <?php else: ?>
                                    <?php echo $barang['stok']; ?> unit
                                <?php endif; ?>
                            </p>
                        </div>

                        <div class="detail-item">
                            <label>Harga</label>
                            <p style="font-size:16px; font-weight:700; color:#1a237e;">
                                Rp <?php echo number_format($barang['harga'], 0, ',', '.'); ?>
                            </p>
                        </div>

                        <div class="detail-item">
                            <label>Tanggal Dibuat</label>
                            <p><?php echo date('d F Y, H:i', strtotime($barang['created_at'])); ?></p>
                        </div>

                        <div class="detail-item">
                            <label>Terakhir Diperbarui</label>
                            <p><?php echo $barang['updated_at'] ? date('d F Y, H:i', strtotime($barang['updated_at'])) : '-'; ?></p>
                        </div>

                    </div>

                    <!-- Deskripsi -->
                    <div class="detail-item" style="margin-top:20px; padding-top:16px; border-top:1px solid #eee;">
                        <label>Deskripsi</label>
                        <p style="line-height:1.7; color:#555; margin-top:6px;">
                            <?php echo !empty($barang['deskripsi']) ? nl2br(htmlspecialchars($barang['deskripsi'])) : '<em style="color:#aaa;">Tidak ada deskripsi.</em>'; ?>
                        </p>
                    </div>

                    <!-- Aksi -->
                    <div style="margin-top:24px; padding-top:16px; border-top:1px solid #eee; display:flex; gap:10px;">
                        <a href="edit.php?id=<?php echo $barang['id']; ?>" class="btn btn-success">
                            <i class="fas fa-edit"></i> Edit Barang
                        </a>
                        <a href="hapus.php?id=<?php echo $barang['id']; ?>" class="btn btn-danger"
                           onclick="return confirm('Yakin ingin menghapus barang ini? Data tidak dapat dikembalikan.')">
                            <i class="fas fa-trash"></i> Hapus Barang
                        </a>
                        <a href="index.php?page=data_barang" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>

                </div>
            </div>
        </div>

    </main>
</div>

<?php include 'includes/footer.php'; ?>
