<?php
// Variable untuk koneksi ke MySQL
$host = "localhost";
$username = "root";
$password = "";
$databasename = "db_cafe";

// Syntax untuk koneksi ke MySQL
$conn = mysqli_connect($host, $username, $password, $databasename);

// Perkondisian jika gagal konek ke MySQL
if (!$conn) {
    echo "Error: " . mysqli_connect_error();
    exit();
}
?>