<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link rel="stylesheet" href="Assets/css/tampil.css">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
<div class="card-container mt-4">
    <div class="row g-3">
        <?php
        include_once 'koneksi.php';

        // Mengambil data menu
        $sql = "SELECT * FROM tbl_cafe";
        $result = $conn->query($sql);
        
        // Menampilkan data menu
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '<div class="col-md-3 col-sm-6 col-12 mb-3">';
                    echo '<div class="card h-100">';
                        echo '<img src="Assets/Menu/' . $row['gambar'] . '" class="card-img-top" alt="Gambar menu">';
                        echo '<div class="card-body">';
                            echo '<h5 class="card-title">' . $row['nama_menu'] . '</h5>';
                            $kategoriClass = ($row["kategori"] == 'makanan') ? 'kategori-makanan' : 'kategori-minuman';
                            echo '<p class="card-text ' . $kategoriClass . '"><strong>' . $row["kategori"] . '</strong></p>';
                            echo '<p class="card-text harga"><strong>Rp ' . number_format($row["harga"], 2, ',', '.') . '</strong></p>';
                        echo '</div>';
                        echo '<button class="btn-order mt-auto add-to-order" onclick="addToCart(\'' . htmlspecialchars($row["nama_menu"]) . '\', ' . $row["harga"] . ')"> Tambahkan </button>';
                    echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<p class="text-center">Menu tidak tersedia.</p>';
        }

        // Menutup koneksi
        $conn->close();
        ?>
    </div>

   
</div>

<script src="Assets/js/submit_order.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
