<?php
session_start(); // Mulai session

include 'koneksi.php';

// Ambil data dari form
$nama_customer = $_POST['nama_customer'];
$order_type = $_POST['order_type'];
$order_date = $_POST['order_date'];
$subtotal = $_POST['subtotal'];
$tax = $_POST['tax'];
$total = $_POST['total'];

// Query untuk mendapatkan nomor order terakhir
$query_last_order_number = "SELECT order_number FROM orders ORDER BY order_number DESC LIMIT 1";
$result_last_order_number = $conn->query($query_last_order_number);

if ($result_last_order_number->num_rows > 0) {
    $row = $result_last_order_number->fetch_assoc();
    $last_order_number = $row['order_number']; // Ambil nomor order terakhir
    $last_number = intval(substr($last_order_number, 3)); // Ambil angka setelah "ORD"
    $new_number = $last_number + 1; // Tambahkan 1
} else {
    $new_number = 1; // Jika tabel masih kosong, mulai dari 1
}

// Format nomor order baru
$order_number = "ORD" . str_pad($new_number, 4, "0", STR_PAD_LEFT); // Format: ORD0001

// Siapkan query untuk menyimpan order
$sql = "INSERT INTO orders (order_number, nama_customer, order_type, order_date, subtotal, tax, total) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssiii", $order_number, $nama_customer, $order_type, $order_date, $subtotal, $tax, $total);

// Eksekusi query dan cek hasilnya
if ($stmt->execute()) {
    header("Location: cetak_Struk.php?order_number=" . urlencode($order_number));
} else {
    // Set pesan error ke session
    $_SESSION['message'] = "Error: " . $stmt->error;
}

// Tutup statement dan koneksi
$stmt->close();
$conn->close();

exit();
?>