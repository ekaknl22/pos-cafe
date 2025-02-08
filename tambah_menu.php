<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Gambar</title>
    <link rel="stylesheet" href="Assets/css/tambah_menu.css">
</head>
<body>
    <h1>Tambah Menu Baru</h1>
    <form action="tambah_menu.php" method="post" enctype="multipart/form-data">
        <label for="nama_menu">Nama Menu:</label>
        <input type="text" id="nama_menu" name="nama_menu" required><br>

        <label for="harga">Harga:</label>
        <input type="number" name="harga" id="harga" required><br>

        <label for="kategori">Kategori:</label>
        <input type="text" name="kategori" id="kategori" required><br>

        <label for="gambar">Pilih gambar untuk diupload:</label><br>
        <input type="file" name="gambar" id="gambar" accept="image/*" required><br><br>

        <div class="btn-container">
            <input type="submit" value="Upload Gambar" id="upload">
            <button type="button" onclick="location.href='index.html'" id="kembali">Kembali</button>
        </div>
    </form>

    <?php
    session_start(); // Mulai session

    include_once 'koneksi.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $image = $_FILES['gambar']['name'];
        // Folder tempat menyimpan gambar
        $targetDir = "Assets/Menu/";
        $targetFile = $targetDir . basename($image);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        $nama_menu = $_POST['nama_menu'];
        $harga = $_POST['harga'];
        $kategori = $_POST['kategori'];

        // Cek apakah file gambar adalah gambar sebenarnya
        $check = getimagesize($_FILES["gambar"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $_SESSION['message'] = "File yang diupload bukan gambar.";
            $uploadOk = 0;
        }

        // Cek ukuran file
        if ($_FILES["gambar"]["size"] > 5000000) { // 5MB
            $_SESSION['message'] = "Maaf, ukuran file terlalu besar.";
            $uploadOk = 0;
        }

        // Cek format file
        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            $_SESSION['message'] = "Maaf, hanya file JPG, JPEG, PNG & GIF yang diizinkan.";
            $uploadOk = 0;
        }

        // Cek apakah $uploadOk diatur ke 0 oleh kesalahan
        if ($uploadOk == 0) {
            $_SESSION['message'] = "Maaf, gambar Anda tidak terupload.";
        } else {
            if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $targetFile)) {
                // Menggunakan prepared statement untuk menghindari SQL injection
                $stmt = $conn->prepare("INSERT INTO tbl_cafe (nama_menu, harga, kategori, gambar) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("sdss", $nama_menu, $harga, $kategori, $image);
                
                if ($stmt->execute()) {
                    $_SESSION['message'] = "Menu baru berhasil ditambahkan";
                } else {
                    $_SESSION['message'] = "Maaf, menu baru gagal ditambahkan";
                }
                $stmt->close();
            } else {
                $_SESSION['message'] = "Maaf, terjadi kesalahan saat mengupload gambar.";
            }
        }

        // Redirect ke halaman yang sama
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // Menampilkan pesan jika ada
    if (isset($_SESSION['message'])) {
        echo "<script>alert('" . $_SESSION['message'] . "');</script>";
        unset($_SESSION['message']); // Hapus pesan setelah ditampilkan
    }
    ?>
</body>
</html>
