<?php
include 'koneksi.php';

$page_title = "Tambah Barang";

// Proses form POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $kode_barang = clean_input($_POST['kode_barang']);
    $nama_barang = clean_input($_POST['nama_barang']);
    $kategori    = clean_input($_POST['kategori']);
    $stok        = clean_input($_POST['stok']);
    $harga       = clean_input($_POST['harga']);
    $deskripsi   = clean_input($_POST['deskripsi']);

    // Generate kode otomatis jika dikosongkan
    if (empty($kode_barang)) {
        $prefix   = "BRG";
        $q_max    = "SELECT MAX(CAST(SUBSTRING(kode_barang, 4) AS UNSIGNED)) AS max_kode 
                     FROM barang WHERE kode_barang LIKE '$prefix%'";
        $r_max    = mysqli_query($koneksi, $q_max);
        $row_max  = mysqli_fetch_assoc($r_max);
        $next_num = ($row_max['max_kode'] ?? 0) + 1;
        $kode_barang = $prefix . str_pad($next_num, 3, '0', STR_PAD_LEFT);
    }

    // Cek apakah kode sudah dipakai
    $cek = mysqli_query($koneksi, "SELECT id FROM barang WHERE kode_barang = '$kode_barang'");
    if (mysqli_num_rows($cek) > 0) {
        $_SESSION['pesan'] = "Kode barang <strong>$kode_barang</strong> sudah digunakan!";
        $_SESSION['tipe']  = "error";
    } else {
        $sql = "INSERT INTO barang (kode_barang, nama_barang, kategori, stok, harga, deskripsi, status)
                VALUES ('$kode_barang', '$nama_barang', '$kategori', '$stok', '$harga', '$deskripsi', 'aktif')";

        if (mysqli_query($koneksi, $sql)) {
            $_SESSION['pesan'] = "Barang <strong>$nama_barang</strong> berhasil ditambahkan!";
            $_SESSION['tipe']  = "success";
            header("Location: index.php?page=data_barang");
            exit();
        } else {
            $_SESSION['pesan'] = "Gagal menambahkan barang: " . mysqli_error($koneksi);
            $_SESSION['tipe']  = "error";
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="content-wrapper">
    <?php include 'includes/menu.php'; ?>

    <main class="main-content">

        <div class="page-header">
            <h2>Tambah Barang Baru</h2>
            <div class="breadcrumb">
                <a href="index.php"><i class="fas fa-home"></i> Home</a>
                <i class="fas fa-chevron-right"></i>
                <a href="index.php?page=data_barang">Data Barang</a>
                <i class="fas fa-chevron-right"></i>
                <span>Tambah Barang</span>
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
                    <h3><i class="fas fa-plus-circle"></i> Form Tambah Barang</h3>
                    <a href="index.php?page=data_barang" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>

                <div class="card-body">
                    <form method="POST" class="form-vertical">

                        <div class="form-row">
                            <div class="form-group">
                                <label for="kode_barang">
                                    <i class="fas fa-barcode"></i> Kode Barang
                                </label>
                                <input type="text" id="kode_barang" name="kode_barang"
                                       placeholder="Kosongkan untuk generate otomatis"
                                       value="<?php echo isset($_POST['kode_barang']) ? htmlspecialchars($_POST['kode_barang']) : ''; ?>">
                                <small class="form-hint">Contoh: BRG006 &mdash; biarkan kosong untuk otomatis</small>
                            </div>

                            <div class="form-group">
                                <label for="nama_barang">
                                    <i class="fas fa-box"></i> Nama Barang <span style="color:red">*</span>
                                </label>
                                <input type="text" id="nama_barang" name="nama_barang" required
                                       value="<?php echo isset($_POST['nama_barang']) ? htmlspecialchars($_POST['nama_barang']) : ''; ?>">
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
                                        $sel = (isset($_POST['kategori']) && $_POST['kategori'] == $kat) ? 'selected' : '';
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
                                       value="<?php echo isset($_POST['stok']) ? htmlspecialchars($_POST['stok']) : ''; ?>">
                            </div>

                            <div class="form-group">
                                <label for="harga">
                                    <i class="fas fa-money-bill-wave"></i> Harga (Rp) <span style="color:red">*</span>
                                </label>
                                <input type="number" id="harga" name="harga" min="0" required
                                       value="<?php echo isset($_POST['harga']) ? htmlspecialchars($_POST['harga']) : ''; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="deskripsi">
                                <i class="fas fa-align-left"></i> Deskripsi
                            </label>
                            <textarea id="deskripsi" name="deskripsi" rows="4"
                                      placeholder="Deskripsi barang (opsional)..."><?php echo isset($_POST['deskripsi']) ? htmlspecialchars($_POST['deskripsi']) : ''; ?></textarea>
                        </div>

                        <div class="form-actions">
                            <button type="reset" class="btn btn-secondary">
                                <i class="fas fa-redo"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Barang
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

    </main>
</div>

<?php include 'includes/footer.php'; ?>
