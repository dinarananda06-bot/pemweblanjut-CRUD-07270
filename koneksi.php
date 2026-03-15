<?php
session_start();

// Koneksi ke database
$host     = "localhost";
$username = "root";
$password = "";
$database = "db_inventaris";

$koneksi = mysqli_connect($host, $username, $password, $database);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

mysqli_set_charset($koneksi, "utf8");

// Fungsi untuk membersihkan input
function clean_input($data) {
    global $koneksi;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return mysqli_real_escape_string($koneksi, $data);
}
?>
