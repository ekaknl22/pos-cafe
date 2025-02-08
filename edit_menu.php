<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu</title>
    <link rel="stylesheet" href="Assets/css/style.css">
    <link rel="stylesheet" href="Assets/css/edit_menu.css">
</head>

<body>
    <h1 id="editmenu-header">Edit Menu</h1>

    <?php
    include_once 'koneksi.php';

    if (isset($_GET['id_menu'])) {
        $id_menu = $_GET['id_menu'];

        // Query to fetch menu data based on id_menu
        $query = "SELECT * FROM tbl_cafe WHERE id_menu = $id_menu";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        } else {
            echo "Menu not found.";
            exit;
        }
    } 
    ?>

    <!-- Form to Edit Menu -->
    <form method="POST" action="edit_menu.php" id="form-edit" enctype="multipart/form-data">
        <input type="hidden" name="id_menu" value="<?php echo $row['id_menu']; ?>">
        
        <label for="nama_menu">Name:</label>
        <input type="text" id="nama_menu" name="nama_menu" value="<?php echo htmlspecialchars($row['nama_menu']); ?>" required><br>
        
        <label for="harga">Price:</label>
        <input type="number" name="harga" id="harga" value="<?php echo htmlspecialchars($row['harga']); ?>" required><br>
        
        <label for="kategori">Category:</label>
        <input type="text" name="kategori" id="kategori" value="<?php echo htmlspecialchars($row['kategori']); ?>" required><br>
        
        <label for="gambar">Choose a picture to upload:</label><br>
        <input type="file" name="gambar" id="gambar" accept="image/*"><br><br>
        <div class="btn-container">
            <button type="submit" id="simpan" class="btn-simpan">Simpan Perubahan</button>
            <button onclick="location.href='tampil_menu.php'" id="kembali" class="btn-kembali">Kembali</button>
        </div>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        include 'koneksi.php';

        $id_menu = $_POST['id_menu'];
        $name = $_POST['nama_menu'];
        $price = $_POST['harga'];
        $id_category = $_POST['kategori'];
        $image = $_FILES['gambar']['name'];

        if ($image) {
            $targetDir = "Assets/Menu/";
            $targetFile = $targetDir . basename($image);
            move_uploaded_file($_FILES['gambar']['tmp_name'], $targetFile);

            // Update query with image
            $query = "UPDATE tbl_cafe SET 
                        nama_menu='$name', 
                        harga='$price', 
                        kategori='$id_category', 
                        gambar='$image' 
                        WHERE id_menu=$id_menu";
        } else {
            // Update query without image
            $query = "UPDATE tbl_cafe SET 
                        nama_menu='$name', 
                        harga='$price', 
                        kategori='$id_category' 
                        WHERE id_menu=$id_menu";
        }

        if ($conn->query($query) === TRUE) {
            // Redirect to tampil_menu.php after successful update
            header("Location: tampil_menu.php");
            exit(); // Ensure no further code is executed after the redirect
        } else {
            echo "Error updating record: " . $conn->error;
        }

        $conn->close();
    }
    ?>

</body>

</html>
