<?php
include 'koneksi.php'; 

if (isset($_GET['order_number'])) {
    $order_number = $_GET['order_number'];

    $stmt = $conn->prepare("UPDATE orders SET deleted_at = NOW() WHERE order_number = ?");
    $stmt->bind_param("s", $order_number);
    
    if ($stmt->execute()) {
        header("Location: riwayat_transaksi.php?message=Order+deleted+successfully");
        exit();
    } else {
        header("Location: riwayat_transaksi.php?message=Failed+to+delete+order");
        exit();
    }

    $stmt->close();
}

$conn->close();
?>
