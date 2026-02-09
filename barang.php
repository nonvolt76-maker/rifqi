<?php
// 1. KONEKSI DATABASE
$host     = "127.0.0.1";
$username = "root";
$password = "root"; // Kosongkan jika pakai XAMPP bawaan, atau isi 'root' sesuai config Anda
$database = "db_peminjaman";

$koneksi = mysqli_connect($host, $username, $password, $database);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// 2. LOGIKA SIMPAN DATA
if (isset($_POST['btn_simpan'])) {
    $nama_peminjam = $_POST['nama_peminjam'];
    $id_alat       = $_POST['id_alat']; // Mengambil ID dari tabel alat
    $tgl_pinjam    = $_POST['tgl_pinjam'];

    // Ambil nama alat berdasarkan ID untuk disimpan di tabel peminjaman
    $get_alat  = mysqli_query($koneksi, "SELECT nama_alat FROM alat WHERE id_alat = '$id_alat'");
    $data_alat = mysqli_fetch_assoc($get_alat);
    $nama_alat = $data_alat['nama_alat'];

    // Query Insert ke tabel peminjaman (Sesuaikan dengan nama kolom di DB Anda)
    $query = "INSERT INTO peminjaman (nama_barang, nama_peminjam, tanggal_pinjam, status) 
              VALUES ('$nama_alat', '$nama_peminjam', '$tgl_pinjam', 'Dipinjam')";

    if (mysqli_query($koneksi, $query)) {
        // Mengurangi stok di tabel alat secara otomatis
        mysqli_query($koneksi, "UPDATE alat SET stok = stok - 1 WHERE id_alat = '$id_alat'");
        echo "<script>alert('Peminjaman Berhasil!'); window.location='index.php';</script>";
    } else {
        echo "Gagal menyimpan: " . mysqli_error($koneksi);
    }
}

// Ambil daftar alat untuk dropdown
$daftar_alat = mysqli_query($koneksi, "SELECT * FROM alat WHERE stok > 0");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Transaksi Peminjaman</title>
    <style>
        /* CSS DISATUKAN */
        * { margin: 0; padding: 0; font-family: 'Segoe UI', Arial; box-sizing: border-box; }
        body { background-color: #f0f2f5; display: flex; }

        /* Sidebar Style (Mirip gambar pertama) */
        .sidebar { width: 230px; background: #fff; height: 100vh; border-right: 1px solid #ddd; position: fixed; }
        .sidebar-header { background: #1a1a1a; color: #fff; padding: 15px; font-weight: bold; font-size: 14px; }
        .menu-item { padding: 12px 20px; color: #555; cursor: pointer; border-bottom: 1px solid #f0f0f0; display: block; text-decoration: none; }
        .menu-item:hover { background: #f8f9fa; }
        .active { background: #007bff; color: #fff; }
        .category { padding: 15px 20px 5px; font-size: 11px; color: #999; font-weight: bold; text-transform: uppercase; }

        /* Content Area */
        .main-content { margin-left: 230px; width: 100%; padding: 30px; }
        .card { background: #fff; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 20px; }
        h2 { margin-bottom: 20px; font-weight: 400; color: #333; }

        /* Form Styling */
        .form-row { display: grid; grid-template-columns: 150px 1fr; align-items: center; margin-bottom: 15px; }
        label { color: #666; font-size: 14px; }
        input, select { padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; outline: none; width: 100%; max-width: 400px; }
        
        .btn-simpan { 
            background: #3498db; color: #fff; border: none; padding: 10px 25px; 
            border-radius: 4px; cursor: pointer; font-size: 14px; margin-top: 10px;
        }
        .btn-simpan:hover { background: #2980b9; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-header">PENJUALAN BESI / ALAT</div>
        <a href="dashboard.php" class="menu-item">🏠 Menu Utama</a>
        <div class="category">Master Data</div>
        <a href="dashboard.php" class="menu-item">📋 Data Alat/Besi</a>
        <div class="category">Transaksi</div>
        <a href="dashboard.php" class="menu-item active">📈 Peminjaman Barang</a>
    </div>

    <div class="main-content">
        <div class="card">
            <h2>Transaksi Peminjaman</h2>
            <hr style="margin-bottom: 20px; border: 0; border-top: 1px solid #eee;">

            <form action="" method="POST">
                <div class="form-row">
                    <label>Nama Peminjam</label>
                    <input type="text" name="nama_peminjam" placeholder="Input Nama..." required>
                </div>

                <div class="form-row">
                    <label>Pilih Barang/Alat</label>
                    <select name="id_alat" required>
                        <option value="">-- Pilih Tersedia --</option>
                        <?php while($row = mysqli_fetch_assoc($daftar_alat)) : ?>
                            <option value="<?= $row['id_alat']; ?>">
                                <?= $row['nama_alat']; ?> (Stok: <?= $row['stok']; ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-row">
                    <label>Tanggal Pinjam</label>
                    <input type="date" name="tgl_pinjam" value="<?= date('Y-m-d'); ?>" required>
                </div>

                <div class="form-row">
                    <label></label>
                    <button type="submit" name="btn_simpan" class="btn-simpan">Simpan Peminjaman</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
