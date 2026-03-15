<?php
// Pencarian
$search = isset($_GET['search']) ? clean_input($_GET['search']) : '';

if (!empty($search)) {
    $query = "SELECT * FROM barang 
              WHERE nama_barang LIKE '%$search%' 
                 OR kode_barang LIKE '%$search%' 
                 OR kategori   LIKE '%$search%'
              ORDER BY id DESC";
} else {
    $query = "SELECT * FROM barang ORDER BY id DESC";
}

$result = mysqli_query($koneksi, $query);
$total  = mysqli_num_rows($result);
?>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-box"></i> Daftar Barang</h3>
        <a href="tambah.php" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Barang
        </a>
    </div>

    <div class="card-body">

        <!-- Search & Info -->
        <div class="table-toolbar">
            <span style="font-size:13px; color:#666;">
                Menampilkan <strong><?php echo $total; ?></strong> data barang
            </span>
            <form method="GET" class="search-box">
                <input type="hidden" name="page" value="data_barang">
                <input type="text" name="search" placeholder="Cari nama / kode / kategori..."
                       value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-search"></i>
                </button>
                <?php if (!empty($search)): ?>
                <a href="index.php?page=data_barang" class="btn btn-secondary btn-sm">
                    <i class="fas fa-times"></i>
                </a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Tabel -->
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th width="40">No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th width="160">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($total > 0):
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($result)):
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><code><?php echo htmlspecialchars($row['kode_barang']); ?></code></td>
                        <td><?php echo htmlspecialchars($row['nama_barang']); ?></td>
                        <td><span class="badge badge-info"><?php echo htmlspecialchars($row['kategori']); ?></span></td>
                        <td>
                            <?php if ($row['stok'] <= 10): ?>
                                <span style="color:#c62828; font-weight:600;"><?php echo $row['stok']; ?></span>
                                <small style="color:#c62828;">(menipis)</small>
                            <?php else: ?>
                                <?php echo $row['stok']; ?>
                            <?php endif; ?>
                        </td>
                        <td>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                        <td>
                            <?php if ($row['status'] == 'aktif'): ?>
                                <span class="badge badge-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="detail.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="hapus.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" title="Hapus"
                               onclick="return confirm('Yakin ingin menghapus barang ini?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr>
                        <td colspan="8" style="text-align:center; color:#888; padding:24px;">
                            <i class="fas fa-inbox" style="font-size:28px; display:block; margin-bottom:8px;"></i>
                            <?php echo !empty($search) ? "Tidak ada hasil untuk pencarian \"$search\"." : "Belum ada data barang."; ?>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>
