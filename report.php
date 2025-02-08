<?php
// Koneksi ke database
include_once 'koneksi.php';

// Ambil tanggal hari ini
$tanggal_hari_ini = date('Y-m-d');

// Query untuk menghitung jumlah transaksi harian
$sql = "SELECT COUNT(*) AS jumlah_transaksi FROM orders WHERE DATE(order_date) = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $tanggal_hari_ini);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$jumlah_transaksi = $data['jumlah_transaksi'] ?? 0; 

// Query untuk menghitung jumlah transaksi seluruhnya
$sql_transaksi = "SELECT COUNT(*) AS riwayat_transaksi FROM orders ";
$stmt_transaksi = $conn->prepare($sql_transaksi);
$stmt_transaksi->execute();
$result_transaksi = $stmt_transaksi->get_result();
$data_transaksi = $result_transaksi->fetch_assoc();
$riwayat_transaksi = $data_transaksi['riwayat_transaksi'] ?? 0;

// Query untuk menghitung total pemasukan
$sql_pemasukan = "SELECT SUM(total) AS total_pemasukan FROM orders WHERE DATE(order_date) = ?";
$stmt_pemasukan = $conn->prepare($sql_pemasukan);
$stmt_pemasukan->bind_param("s", $tanggal_hari_ini);
$stmt_pemasukan->execute();
$result_pemasukan = $stmt_pemasukan->get_result();
$data_pemasukan = $result_pemasukan->fetch_assoc();
$total_pemasukan = $data_pemasukan['total_pemasukan'] ?? 0; 

// Query untuk mendapatkan data 7 hari terakhir
$sql_harian = "SELECT DATE(order_date) AS tanggal, SUM(total) AS total_harian 
                FROM orders 
                WHERE order_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) 
                GROUP BY DATE(order_date)";
$result_harian = $conn->query($sql_harian);

// Siapkan array untuk label (tanggal) dan data (pemasukan harian)
$labels = [];
$data_harian = [];

if ($result_harian->num_rows > 0) {
    while ($row = $result_harian->fetch_assoc()) {
        $labels[] = $row['tanggal'];
        $data_harian[] = $row['total_harian'] ?? 0; 
    }
}

$conn->close();

// Ubah data ke format JSON untuk digunakan di JavaScript
$labels_json = json_encode($labels);
$data_json = json_encode($data_harian);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Transaksi</title>
    <link rel="stylesheet" href="Assets/css/style.css">
    <link rel="stylesheet" href="Assets/css/report.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body class="all">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-fluid navbar-dark bg-dark fixed-top">
        <div class="container-fluid d-flex align-items-center">
            <a class="navbar-brand dynapuff-unique" style="font-size:2rem" href="#"><img class="img-logo" src="Assets/logocute.jpg" alt="logo Bake&Brew">Bake&Brew</a>
            <div id="date-time" class="text-white">
                <input type="hidden" id="order_date" name="order_date">
            </div>
            <button class="navbar-toggler shadow-none border-0 me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Sidebar -->
            <div class="sidebar offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="sidebar offcanvas-header text-white border-bottom">
                    <h5 class="offcanvas-title dynapuff-unique" id="offcanvasNavbarLabel"><img src="Assets/logocute.jpg" class="img-logo ">Bake&Brew</h5>
                    <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>

                <div class="sidebar offcanvas-body d-flex flex-column p-4">
                    <ul class="navbar-nav justify-content-start flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="index.html" onclick="setActive(this)">Point Of Sales</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" role="button" aria-expanded="false" onclick="setActive(this)">Inventory</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="tambah_menu.php">Add Menu</a></li>
                                <li><a class="dropdown-item" href="tampil_menu.php">Edit Menu</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" onclick="setActive(this)" href="report.php">Report</a>
                        </li>
                    </ul>

                    <!-- Logout -->
                    <div>
                        <a href="logout.php" class="btn-logout text-decoration-none">Log out <img src="Assets/logout.svg"></a>
                    </div>
                </div>
            </div>  
        </div>
    </nav>
    <main>
        <div class="card-container">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Penjualan Hari Ini</h5>
                    <p class="card-text">
                        <span style="font-size: 24px;"><?php echo number_format($jumlah_transaksi); ?></span> item
                    </p>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Pemasukan Hari Ini</h5>
                    <p class="card-text">
                        <span style="font-size: 24px;">Rp <?php echo number_format($total_pemasukan, 0, ',', '.'); ?></span>
                    </p>
                </div>
            </div>
            <a href="riwayat_transaksi.php" style="text-decoration: none; color: inherit;">
            <div class="card riwayat">
                <div class="card-body">
                    <h5 class="card-title">Riwayat Transaksi</h5>
                    <p class="card-text">
                        <span style="font-size: 24px;"><?php echo number_format($riwayat_transaksi); ?></span> item
                    </p>
                </div>
            </div>
        </a>
        </div>
    <canvas id="myChart" width="300" height="100"></canvas>
    </main>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script>
//ambil tanggal sekarang
let today = new Date();
        let options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        let formattedDate = today.toLocaleDateString('id-ID', options);

        document.getElementById("date-time").innerHTML = formattedDate;

        // Mengubah format tanggal ke DD-MM-YYYY
        let day = String(today.getDate()).padStart(2, '0'); // Mengambil hari dan menambahkan nol di depan jika perlu
        let month = String(today.getMonth() + 1).padStart(2, '0'); // Mengambil bulan (0-11) dan menambahkan nol di depan
        let year = today.getFullYear(); // Mengambil tahun

        // Menggabungkan menjadi format DD-MM-YYYY
        let formattedOrderDate = `${day}-${month}-${year}`;

        //fungsi ketika tombol di klik jadi active
        function setActive(element) {
            var links = document.querySelectorAll('.nav-link');
            links.forEach(function(link) {
                link.classList.remove('active');
            });
            
            element.classList.add('active');
        }
    //Fungsi buat gambar diagram
    const labels = <?php echo $labels_json; ?>;
    const data = <?php echo $data_json; ?>;
    const ctx = document.getElementById('myChart').getContext('2d');
    const chartPemasukan = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Grafik Pemasukan 7 Hari Terakhir',
                data: data,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 2,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Tanggal',
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Pemasukan (Rp)',
                    },
                    beginAtZero: true,
                }
            }
        }
    });
</script>

</body>
</html>
