<?php
include 'koneksi.php'; // Koneksi ke database
require('fpdf/fpdf.php'); // Memanggil library FPDF

// Mengambil order_number dari URL
$order_number = isset($_GET['order_number']) ? $_GET['order_number'] : null;

if ($order_number === null) {
    die("Order number tidak ditemukan.");
}

// Inisialisasi objek FPDF
$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();

// Header
$pdf->SetFont('Arial', 'I', 12);
$pdf->Cell(190, 7, 'Struk Pesanan', 0, 1, 'C');
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(190, 10, 'Bake&Brew', 0, 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(190, 7, 'Alamat: Jl. Kopi No. 123, Kota Yogyakarta, Indonesia', 0, 1, 'C');
$pdf->Cell(190, 7, 'Telp: +62 123 4567 890', 0, 1, 'C');
$pdf->Ln(5); // Spasi ke bawah

// Garis pemisah
$pdf->Line(10, $pdf->GetY(), 200 - 10, $pdf->GetY());
$pdf->Ln(5);

// Detail Pesanan
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(190, 7, 'Detail Pesanan', 0, 1, 'L');
$pdf->Ln(3);

// Mengambil data pesanan dari database
$query = "SELECT * FROM orders WHERE order_number = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $order_number);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Menampilkan detail pesanan tanpa tabel
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(30, 8, 'Order Number:', 0);
        $pdf->Cell(0, 8, $row['order_number'], 0, 1);
        
        $pdf->Cell(30, 8, 'Nama Customer:', 0);
        $pdf->Cell(0, 8, $row['nama_customer'], 0, 1);
        
        $pdf->Cell(30, 8, 'Tanggal Pesanan:', 0);
        $pdf->Cell(0, 8, date('d-m-Y', strtotime($row['order_date'])), 0, 1);
        
        $pdf->Cell(30, 8, 'Total:', 0);
        $pdf->Cell(0, 8, 'Rp ' . number_format($row['total'], 2, ',', '.'), 0, 1);
        
        // Tambahkan garis pemisah setelah setiap pesanan jika perlu
        $pdf->Ln(2);
        $pdf->Line(10,$pdf->GetY(),200-10,$pdf->GetY());
        $pdf->Ln(5);
    }
} else {
    $pdf->Cell(190, 8, 'Tidak ada pesanan ditemukan.', 0, 1);
}

// Footer
$pdf->Ln(10);
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(0, 10, 'Terima kasih telah berbelanja di Bake&Brew!', 0, 1,'C');

// Garis pemisah footer
$pdf->Line(10,$pdf->GetY(),200-10,$pdf->GetY());
$pdf->Ln(5);
$pdf->Cell(0, 10, 'Dicetak pada: ' . date('d-m-Y H:i:s'), 0, 1);

// Output file PDF
$pdf->Output('Resi_Pesanan.pdf', 'I'); // 'I' untuk menampilkan di browser

// Menutup statement dan koneksi
$stmt->close();
$conn->close();
?>
