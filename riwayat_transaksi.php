<?php
include_once 'koneksi.php';

$search_query = '';


if (isset($_GET['search'])) {
    $search_query = $_GET['search'];

    $sql = "SELECT * FROM orders WHERE (order_number LIKE ? OR nama_customer LIKE ?) AND deleted_at IS NULL ORDER BY order_date DESC";
    $stmt = $conn->prepare($sql);
    $like_query = "%" . $search_query . "%";
    $stmt->bind_param("ss", $like_query, $like_query);
} else {
    $sql = "SELECT * FROM orders WHERE deleted_At IS NULL ORDER BY order_number DESC";
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi</title>
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
                        <li class="nav-item"><a class="nav-link" aria-current="page" href="index.html" onclick="setActive(this)">Point Of Sales</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" role="button" aria-expanded="false" onclick="setActive(this)">Inventory</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="tambah_menu.php">Add Menu</a></li>
                                <li><a class="dropdown-item" href="tampil_menu.php">Edit Menu</a></li>
                            </ul>
                        </li>
                        <li class="nav-item"><a class="nav-link active" onclick="setActive(this)" href="#">Report</a></li>
                    </ul>

                    <!-- Logout -->
                    <div><a href="logout.php" class="btn-logout text-decoration-none">Log out <img src="Assets/logout.svg"></a></div>
                </div>
            </div>  
        </div>
    </nav>

    <!-- Tombol Kembali -->
    <div class='btn-kembali'>
        <a href='report.php' class='btn btn-secondary btn-back'>Kembali</a>
    </div>

    <h2 class='text-center'>Riwayat Transaksi</h2>

    <!-- Form Search -->
    <div class='form-container'>
        <form class='d-flex' action='riwayat_transaksi.php' method='GET'>
        <input name='search' class='form-control me-2' type='search' placeholder='Cari transaksi' aria-label='Search' value='<?php echo htmlspecialchars($search_query); ?>' style="width: 50%; margin-left: 17rem; display: block;">
            <button class='btn-search' type='submit'>Search</button>
        </form>
    </div>

    <main>
    <!-- Tabel Riwayat Transaksi -->
    <div class="table-container">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Nomor Order</th>
                    <th>Nama Customer</th>
                    <th>Tipe Order</th>
                    <th>Tanggal Transaksi</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['order_number']}</td>
                                <td>{$row['nama_customer']}</td>
                                <td>{$row['order_type']}</td>
                                <td>{$row['order_date']}</td>
                                <td>Rp " . number_format($row['total'], 0, ',', '.') . "</td>
                                <td><a href='javascript:void(0);' class='btn-delete' onclick=\"confirmDelete('{$row['order_number']}')\">Hapus</a></td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>Tidak ada data transaksi</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</main>
<!-- Tombol Cetak -->
<div class='btn-cetak'>
        <a href='cetak_riwayat_transaksi.php' class='cetak'>Cetak Laporan Transaksi</a>
</div>
    <!-- Bootstrap JS -->
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js' integrity='sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM' crossorigin='anonymous'></script>
    <script>
      //confirm sebelum hapus riwayat orderan
      function confirmDelete(orderNumber) {
          if (confirm("Apakah Anda yakin ingin menghapus order nomor " + orderNumber + "?")) {
              // Jika pengguna mengonfirmasi, arahkan ke delete_order.php
              window.location.href = 'delete_order.php?order_number=' + orderNumber;
          }
      }
    </script>
</body>
</html>

<?php
$conn->close();
?>
