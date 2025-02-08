<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Menu</title>
    <link rel="stylesheet" href="Assets/css/tampil_menu.css">
</head>
<body>
    <form id="daftar">
    <table border="1" id="tampil_menu">
        <h1>Daftar Menu</h1>
        <tr>
            <th>No</th>
            <th>Gambar</th>
            <th>Nama Menu</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>Pilihan</th>
        </tr>
    </form>

        <?php 
        include 'koneksi.php';

        $no = 1;
        $query = "SELECT * FROM tbl_cafe";
        $data = mysqli_query($conn,$query);
        if (!$data) {
            die("Query error: " . mysqli_error($conn)); 
        }

		while($d = mysqli_fetch_array($data)){
			?>
			<tr>
				<td><?php echo $no++; ?></td>
                <td>
                    <img src="Assets/Menu/<?php echo ($d['gambar']); ?>"alt="Gambar Menu" style="width:100px; height:100px; object-fit: cover; " >
                </td>
				<td><?php echo $d['nama_menu']; ?></td>
				<td><?php echo $d['kategori']; ?></td>
                <td><?php echo $d['harga']; ?></td>
				<td>
                    <div class="btn-container">
                    <a href="edit_menu.php?id_menu=<?php echo $d['id_menu']; ?>" id="edit">EDIT</a>
					<a href="hapus_menu.php?id_menu=<?php echo $d['id_menu']; ?>" id="hapus">HAPUS</a>
                    </div>
				</td>
			</tr>
			<?php 
		}
		?>
    </table>
    <div style="width: 150px; text-align: center; margin: 20px; padding: 10px; background-color: #5C636E; border-radius: 10px; margin-left:30em;" id="back">
        <a href="index.html" style="text-decoration: none; color:rgb(244, 246, 247); font-weight: bold; font-size: 16px;">Back</a>
    </div>
</body>
</html>
