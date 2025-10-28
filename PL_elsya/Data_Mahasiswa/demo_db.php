<?php
// Asumsikan $pdo sudah terhubung (dari file koneksi.php)
require_once 'koneksi.php';
$pdo = getKoneksi();

// Pastikan tabel bersih untuk demo (opsional)
$pdo->exec("DELETE FROM mahasiswa WHERE nim IN ('991', '992')");

// --- Skenario 1: Menggunakan bindParam() ---

echo "<h3>Demo bindParam()</h3>";
$sql_param = "INSERT INTO mahasiswa (nim, nama, jurusan, email) VALUES (?, ?, 'Demo', 'param@test.com')";
$stmt_param = $pdo->prepare($sql_param);

$nim = "991";
$nama = "Budi"; // Nilai awal

// 1. Variabel $nama diikat sebagai REFERENSI
$stmt_param->bindParam(1, $nim);
$stmt_param->bindParam(2, $nama);

// 2. Nilai variabel $nama diubah SETELAH diikat
$nama = "Andi"; // Nilai baru

// 3. execute() dipanggil
$stmt_param->execute();

echo "Data yang dimasukkan menggunakan bindParam(): <b>$nama</b> (Nilai terakhir 'Andi')";


// --- Skenario 2: Menggunakan bindValue() ---

echo "<h3>Demo bindValue()</h3>";
$sql_value = "INSERT INTO mahasiswa (nim, nama, jurusan, email) VALUES (?, ?, 'Demo', 'value@test.com')";
$stmt_value = $pdo->prepare($sql_value);

$nim = "992";
$nama = "Budi"; // Nilai awal

// 1. NILAI variabel $nama ("Budi") diikat
$stmt_value->bindValue(1, $nim);
$stmt_value->bindValue(2, $nama);

// 2. Nilai variabel $nama diubah SETELAH diikat
$nama = "Andi"; // Nilai baru

// 3. execute() dipanggil
$stmt_value->execute();

echo "Data yang dimasukkan menggunakan bindValue(): <b>Budi</b> (Nilai saat 'Budi' diikat)";

echo "<hr><p>Cek database Anda. Data 'Andi' (NIM 991) dan 'Budi' (NIM 992) akan masuk.</p>";

?>