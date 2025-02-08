<?php
require('fpdf/fpdf.php');
$pdf = new FPDF('P', 'mm', 'A4');
$pdf->SetMargins(20, 20, 20);
include 'koneksi.php';

// Membuat objek PDF
class PDF extends FPDF {
    function Header() {
        // Judul Laporan
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, 'LAPORAN RIWAYAT TRANSAKSI', 0, 1, 'C');
        $this->Ln(5);
        
        // Header Tabel
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(30, 10, 'Nomor Order', 1, 0, 'C');
        $this->Cell(40, 10, 'Nama Customer', 1, 0, 'C');
        $this->Cell(40, 10, 'Tipe Orderan', 1, 0, 'C');
        $this->Cell(40, 10, 'Tanggal', 1, 0, 'C');
        $this->Cell(30, 10, 'Total', 1, 1, 'C'); // Menggunakan 1 untuk baris baru
        
        // Garis pemisah
        $this->SetDrawColor(200);
        $this->Line(10, $this->GetY(), 200 - 10, $this->GetY());
        $this->Ln(2);
    }

    function Footer() {
        // Menampilkan nomor halaman
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Halaman ' . $this->PageNo(), 0, 0, 'C');
    }
}

// Membuat halaman baru
$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);

// Mengambil data dari tabel orders
$query = "SELECT order_number, nama_customer, order_type, order_date, total FROM orders WHERE deleted_at IS NULL";
$result = mysqli_query($conn, $query);

// Menampilkan data dalam tabel
while ($row = mysqli_fetch_assoc($result)) {
    $pdf->Cell(30, 10, $row['order_number'], 1);
    $pdf->Cell(40, 10, $row['nama_customer'], 1);
    $pdf->Cell(40, 10, $row['order_type'], 1);
    // Format tanggal menjadi lebih rapi
    $formattedDate = date('d-m-Y', strtotime($row['order_date']));
    $pdf->Cell(40, 10, $formattedDate, 1);
    // Format total dengan pemisah ribuan
    $pdf->Cell(30, 10, 'Rp ' . number_format($row['total'], 0, ',', '.'), 1);
    $pdf->Ln();
}

// Menampilkan output PDF
$pdf->Output('D', 'riwayat_transaksi.pdf');
?>
