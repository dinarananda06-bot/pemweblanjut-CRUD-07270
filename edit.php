<?php
include 'koneksi.php';

$page_title = "Edit Barang";

// Ambil ID dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Validasi ID
if ($id <= 0) {
    $_SESSION['pesan'] = "ID barang tidak valid!";
    $_SESSION['tipe']  = "error";
    header("Location: index.php?page=data_barang");
    exit();
}

// Ambil data barang yang akan diedit
$query  = "SELECT * FROM barang WHERE id = $id";
$result = mysqli_query($koneksi, $query);
$barang = mysqli_fetch_assoc($result);

// Jika data tidak ditemukan
if (!$barang) {
    $_SESSION['pesan'] = "Barang tidak ditemukan!";
    $_SESSION['tipe']  = "error";
    header("Location: index.php?page=data_barang");
    exit();
}

// Proses form POST (Update)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $kode_barang = clean_input($_POST['kode_barang']);
    $nama_barang = clean_input($_POST['nama_barang']);
    $kategori    = clean_input($_POST['kategori']);
    $stok        = clean_input($_POST['stok']);
    $harga       = clean_input($_POST['harga']);
    $deskripsi   = clean_input($_POST['deskripsi']);
    $status      = clean_input($_POST['status']);

    // Cek kode barang apakah sudah dipakai oleh data lain
    $cek = mysqli_query($koneksi, "SELECT id FROM barang WHERE kode_barang = '$kode_barang' AND id != $id");
    if (mysqli_num_rows($cek) > 0) {
        $_SESSION['pesan'] = "Kode barang <strong>$kode_barang</strong> sudah digunakan oleh barang lain!";
        $_SESSION['tipe']  = "error";
    } else {
        $sql = "UPDATE barang SET
                    kode_barang = '$kode_barang',
                    nama_barang = '$nama_barang',
                    kategori    = '$kategori',
                    stok        = '$stok',
                    harga       = '$harga',
                    deskripsi   = '$deskripsi',
                    status      = '$status',
                    updated_at  = NOW()
                WHERE id = $id";

        if (mysqli_query($koneksi, $sql)) {
            $_SESSION['pesan'] = "Data barang <strong>$nama_barang</strong> berhasil diperbarui!";
            $_SESSION['tipe']  = "success";
            header("Location: index.php?page=data_barang");
            exit();
        } else {
            $_SESSION['pesan'] = "Gagal memperbarui barang: " . mysqli_error($koneksi);
            $_SESSION['tipe']  = "error";
        }
    }

    // Refresh data setelah error agar form menampilkan nilai yang baru diketik
    $barang['kode_barang'] = $_POST['kode_barang'];
    $barang['nama_barang'] = $_POST['nama_barang'];
    $barang['kategori']    = $_POST['kategori'];
    $barang['stok']        = $_POST['stok'];
    $barang['harga']       = $_POST['harga'];
    $barang['deskripsi']   = $_POST['deskripsi'];
    $barang['status']      = $_POST['status'];
}
?>

<?php include 'includes/header.php'; ?>

<div class="content-wrapper">
    <?php include 'includes/menu.php'; ?>

    <main class="main-content">

        <div class="page-header">
            <h2>Edit Barang</h2>
            <div class="breadcrumb">
                <a href="index.php"><i class="fas fa-home"></i> Home</a>
                <i class="fas fa-chevron-right"></i>
                <a href="index.php?page=data_barang">Data Barang</a>
                <i class="fas fa-chevron-right"></i>
                <span>Edit Barang</span>
            </div>
        </div>

        <!-- Notifikasi -->
        <?php if (isset($_SESSION['pesan'])): ?>
            <div class="alert alert-<?php echo $_SESSION['tipe'] == 'success' ? 'success' : 'error'; ?>">
                <i class="fas fa-<?php echo $_SESSION['tipe'] == 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
                <?php echo $_SESSION['pesan']; unset($_SESSION['pesan']); unset($_SESSION['tipe']); ?>
            </div>
        <?php endif; ?>

        <div class="content">
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-edit"></i> Form Edit Barang</h3>
                    <a href="index.php?page=data_barang" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>

                <div class="card-body">
                    <form method="POST" class="form-vertical">

                        <div class="form-row">
                            <div class="form-group">
                                <label for="kode_barang">
                                    <i class="fas fa-barcode"></i> Kode Barang <span style="color:red">*</span>
                                </label>
                                <input type="text" id="kode_barang" name="kode_barang" required
                                       value="<?php echo htmlspecialchars($barang['kode_barang']); ?>">
                            </div>

                            <div class="form-group">
                                <label for="nama_barang">
                                    <i class="fas fa-box"></i> Nama Barang <span style="color:red">*</span>
                                </label>
                                <input type="text" id="nama_barang" name="nama_barang" required
                                       value="<?php echo htmlspecialchars($barang['nama_barang']); ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="kategori">
                                    <i class="fas fa-tags"></i> Kategori <span style="color:red">*</span>
                                </label>
                                <select id="kategori" name="kategori" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <?php
                                    $kategori_list = ['Elektronik','Pakaian','Makanan','Minuman','Alat Tulis','Olahraga','Lainnya'];
                                    foreach ($kategori_list as $kat):
                                        $sel = ($barang['kategori'] == $kat) ? 'selected' : '';
                                    ?>
                                    <option value="<?php echo $kat; ?>" <?php echo $sel; ?>><?php echo $kat; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="stok">
                                    <i class="fas fa-cubes"></i> Stok <span style="color:red">*</span>
                                </label>
                                <input type="number" id="stok" name="stok" min="0" required
                                       value="<?php echo htmlspecialchars($barang['stok']); ?>">
                            </div>

                            <div class="form-group">
                                <label for="harga">
                                    <i class="fas fa-money-bill-wave"></i> Harga (Rp) <span style="color:red">*</span>
                                </label>
                                <input type="number" id="harga" name="harga" min="0" required
                                       value="<?php echo htmlspecialchars($barang['harga']); ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="status">
                                    <i class="fas fa-toggle-on"></i> Status <span style="color:red">*</span>
                                </label>
                                <select id="status" name="status" required>
                                    <option value="aktif"    <?php echo $barang['status'] == 'aktif'    ? 'selected' : ''; ?>>Aktif</option>
                                    <option value="nonaktif" <?php echo $barang['status'] == 'nonaktif' ? 'selected' : ''; ?>>Nonaktif</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="deskripsi">
                                <i class="fas fa-align-left"></i> Deskripsi
                            </label>
                            <textarea id="deskripsi" name="deskripsi" rows="4"><?php echo htmlspecialchars($barang['deskripsi']); ?></textarea>
                        </div>

                        <div class="form-actions">
                            <a href="index.php?page=data_barang" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

    </main>
</div>

<?php include 'includes/footer.php'; ?>
