<?php
/**
 * TUGAS 4: Fungsi reusable untuk koneksi database
 *
 * Fungsi ini menangani koneksi ke database menggunakan PDO
 * dan sudah dilengkapi dengan error handling (try-catch).
 */
function getKoneksi() {
    $host = 'localhost';
    $db   = 'data_mahasiswa'; 
    $user = 'root';        
    $pass = '';            
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
        return $pdo;
    } catch (\PDOException $e) {
        // Hentikan eksekusi dan tampilkan error
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
}
?>