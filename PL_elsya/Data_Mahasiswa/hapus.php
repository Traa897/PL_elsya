<?php
require_once 'koneksi.php'; // Panggil file koneksi (Tugas 4)

// TUGAS 1: Logika Delete (Hapus)
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    try {
        $pdo = getKoneksi();
        $sql = "DELETE FROM mahasiswa WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        // Gunakan prepared statement untuk keamanan
        $stmt->execute([$id]);

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        // Hentikan eksekusi jika terjadi error
        exit;
    }
}

// Redirect kembali ke halaman utama setelah menghapus
header("Location: index.php");
exit;
?>