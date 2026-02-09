<?php
// 1. KONEKSI DATABASE
$host     = "127.0.0.1";
$username = "root";
$password = "root"; // Kosongkan jika menggunakan XAMPP standar
$database = "db_peminjaman";

$koneksi = mysqli_connect($host, $username, $password, $database);

// Cek Koneksi
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// 2. QUERY AMBIL DATA
$query = "SELECT * FROM peminjaman ORDER BY id_peminjaman DESC";
$sql   = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Peminjaman Alat</title>
    <style>
        /* CSS tetap sama seperti codingan kamu sebelumnya */
        * { margin: 0; padding: 0; font-family: Arial, sans-serif; }
        body { background: #f4f6f9; }
        .navbar { background: #222; color: #fff; padding: 12px 20px; display: flex; justify-content: space-between; }
        .sidebar { width: 200px; background: #2c3e50; height: 100vh; position: fixed; color: #fff; }
        .sidebar h3 { padding: 15px; background: #1a252f; }
        .sidebar a { display: block; padding: 12px 15px; color: #ddd; text-decoration: none; }
        .sidebar a:hover { background: #34495e; }
        .content { margin-left: 200px; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; }
        .btn { padding: 6px 12px; border: none; color: #fff; cursor: pointer; border-radius: 3px; font-size: 12px; }
        .btn-tambah { background: #e74c3c; }
        .btn-edit { background: #3498db; } /* Ganti warna biru agar beda dengan hapus */
        .btn-hapus { background: #c0392b; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; background: #fff; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: center; }
        th { background: #f1f1f1; }
        .badge { padding: 5px 8px; border-radius: 4px; font-size: 12px; color: #fff; }
        .badge-danger { background: #e74c3c; }
        .badge-success { background: #2ecc71; }
    </style>
</head>
<body>

<div class="navbar">
    <div>Peminjaman Alat</div>
    <input type="text" placeholder="Search">
</div>

<div class="sidebar">
    <h3>Peminjaman Alat</h3>
    <a href="#">Dashboard</a>
    <a href="barang.php">Barang</a>
    <a href="#">Anggota</a>
    <a href="#">Peminjaman</a>
    <a href="#">Pengembalian</a>
</div>

<div class="content">
    <div class="header">
        <h2>Peminjaman</h2>
        <button class="btn btn-tambah">+ Tambah</button>
    </div>

    <table>
        <tr>
            <th>No</th>
            <th>Nama Barang</th>
            <th>Nama Peminjam</th>
            <th>Tanggal Pinjam</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php 
        $no = 1;
        // 3. LOOPING DATA DARI DATABASE
        while($row = mysqli_fetch_assoc($sql)): 
        ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= $row['nama_barang'] ?></td>
            <td><?= $row['nama_peminjam'] ?></td>
            <td><?= date('d-m-Y', strtotime($row['tanggal_pinjam'])) ?></td>
            <td>
                <?php if($row['status'] == "dipinjam" || $row['status'] == "Belum Dikembalikan"){ ?>
                    <span class="badge badge-danger">Belum Dikembalikan</span>
                <?php } else { ?>
                    <span class="badge badge-success">Sudah Dikembalikan</span>
                <?php } ?>
            </td>
            <td>
                <button class="btn btn-edit">Edit</button>
                <button class="btn btn-hapus">Hapus</button>
            </td>
        </tr>
        <?php endwhile; ?>

        <?php if(mysqli_num_rows($sql) == 0): ?>
            <tr>
                <td colspan="6">Data tidak ditemukan</td>
            </tr>
        <?php endif; ?>
    </table>
</div>

</body>
</html>
