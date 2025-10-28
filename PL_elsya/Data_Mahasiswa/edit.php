<?php
require_once 'koneksi.php'; // Panggil file koneksi (Tugas 4)
$pdo = getKoneksi();

$id = isset($_GET['id']) ? $_GET['id'] : 0;
$pesan = '';

// TUGAS 1: Logika Update (Edit)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $jurusan = $_POST['jurusan'];
    $email = $_POST['email'];
    $id = $_POST['id']; // Ambil ID dari hidden input

    if (empty($nim) || empty($nama) || empty($jurusan) || empty($email)) {
        $pesan = "Semua field wajib diisi!";
    } else {
        try {
            $sql = "UPDATE mahasiswa SET nim = ?, nama = ?, jurusan = ?, email = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nim, $nama, $jurusan, $email, $id]);

            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            $pesan = "Error: " . $e->getMessage();
        }
    }
    // Jika update gagal (misal karena validasi), data $mhs diisi dari POST
    $mhs = ['id' => $id, 'nim' => $nim, 'nama' => $nama, 'jurusan' => $jurusan, 'email' => $email];

} else {
    // Ambil data mahasiswa berdasarkan ID dari URL
    $sql = "SELECT * FROM mahasiswa WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $mhs = $stmt->fetch();

    if (!$mhs) {
        echo "Data tidak ditemukan!";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data Mahasiswa</title>
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
    <h2>Edit Data Mahasiswa</h2>
    
    <?php if ($pesan): ?>
        <p class="error"><?= $pesan ?></p>
    <?php endif; ?>

    <form method="POST" action="edit.php">
        <input type="hidden" name="id" value="<?= htmlspecialchars($mhs['id']) ?>">
        
        <div class="form-group">
            <label for="nim">NIM</label>
            <input type="text" id="nim" name="nim" value="<?= htmlspecialchars($mhs['nim']) ?>" required>
        </div>
        <div class="form-group">
            <label for="nama">Nama</label>
            <input type="text" id="nama" name="nama" value="<?= htmlspecialchars($mhs['nama']) ?>" required>
        </div>
        <div class="form-group">
            <label for="jurusan">Jurusan</label>
            <input type="text" id="jurusan" name="jurusan" value="<?= htmlspecialchars($mhs['jurusan']) ?>" required>
        </div>
        <div class@="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($mhs['email']) ?>" required>
        </div>
        <button type="submit">Update</button>
        <a href="index.php">Batal</a>
    </form>
</div>

</body>
</html>