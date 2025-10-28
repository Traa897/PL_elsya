<?php
require_once 'koneksi.php'; // Panggil file koneksi (Tugas 4)

// TUGAS 1: Logika Create (Insert)
$pesan = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $jurusan = $_POST['jurusan'];
    $email = $_POST['email'];

    if (empty($nim) || empty($nama) || empty($jurusan) || empty($email)) {
        $pesan = "Semua field wajib diisi!";
    } else {
        try {
            $pdo = getKoneksi();
            $sql = "INSERT INTO mahasiswa (nim, nama, jurusan, email) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            // Gunakan prepared statement untuk keamanan
            $stmt->execute([$nim, $nama, $jurusan, $email]);

            // Redirect kembali ke halaman utama
            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            // Tangani jika NIM duplikat atau error lainnya
            if ($e->getCode() == 23000) {
                $pesan = "NIM sudah terdaftar. Gunakan NIM lain.";
            } else {
                $pesan = "Error: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Data Mahasiswa</title>
    <style>
        body { font-family: sans-serif; margin: 2em; }
        .container { width: 50%; margin: auto; padding: 20px; border: 1px solid #ccc; border-radius: 5px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; }
        .form-group input { width: 95%; padding: 8px; }
        .error { color: red; }
    </style>
</head>
<body>

<div class="container">
    <h2>Tambah Data Mahasiswa</h2>
    
    <?php if ($pesan): ?>
        <p class="error"><?= $pesan ?></p>
    <?php endif; ?>

    <form method="POST" action="tambah.php">
        <div class="form-group">
            <label for="nim">NIM</label>
            <input type="text" id="nim" name="nim" required>
        </div>
        <div class="form-group">
            <label for="nama">Nama</label>
            <input type="text" id="nama" name="nama" required>
        </div>
        <div class="form-group">
            <label for="jurusan">Jurusan</label>
            <input type="text" id="jurusan" name="jurusan" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        <button type="submit">Simpan</button>
        <a href="index.php">Batal</a>
    </form>
</div>

</body>
</html>