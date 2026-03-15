<?php
include 'koneksi.php';

// Ambil ID dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Validasi ID
if ($id <= 0) {
    $_SESSION['pesan'] = "ID barang tidak valid!";
    $_SESSION['tipe']  = "error";
    header("Location: index.php?page=data_barang");
    exit();
}

// Cek apakah barang ada
$cek    = mysqli_query($koneksi, "SELECT nama_barang FROM barang WHERE id = $id");
$barang = mysqli_fetch_assoc($cek);

if (!$barang) {
    $_SESSION['pesan'] = "Barang tidak ditemukan!";
    $_SESSION['tipe']  = "error";
} else {
    $nama = $barang['nama_barang'];

    // Eksekusi hapus
    $sql = "DELETE FROM barang WHERE id = $id";
    if (mysqli_query($koneksi, $sql)) {
        $_SESSION['pesan'] = "Barang <strong>$nama</strong> berhasil dihapus!";
        $_SESSION['tipe']  = "success";
    } else {
        $_SESSION['pesan'] = "Gagal menghapus barang: " . mysqli_error($koneksi);
        $_SESSION['tipe']  = "error";
    }
}

header("Location: index.php?page=data_barang");
exit();
?>
