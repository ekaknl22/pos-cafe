<?php 
include 'koneksi.php';

// Ambil menu_id dari URL
if (!isset($_GET['id_menu']) || !is_numeric($_GET['id_menu'])) {
    die("ID Menu tidak valid.");
}

// Query untuk menghapus data dari tabel menu
$id_menu = $_GET['id_menu'];
$query_hapusdata = "DELETE FROM tbl_cafe WHERE id_menu = ?";
$stmt = mysqli_prepare($conn, $query_hapusdata);
mysqli_stmt_bind_param($stmt, "i", $id_menu);
$hapusdata = mysqli_stmt_execute($stmt);

// Periksa apakah penghapusan berhasil
if (!$hapusdata) {
    die("Query error: " . mysqli_error($conn));
}

// Redirect ke halaman tampil_menu.php dengan pesan
header("Location: tampil_menu.php?");
?>
