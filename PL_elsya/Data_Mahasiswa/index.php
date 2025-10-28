<?php
require_once 'koneksi.php'; // Panggil file koneksi (Tugas 4)
$pdo = getKoneksi();

// --- TUGAS 3: PAGINASI ---
$limit = 5; // Jumlah data per halaman
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$offset = ($halaman - 1) * $limit;

// --- TUGAS 2: PENCARIAN ---
$search = isset($_GET['search']) ? $_GET['search'] : '';
$params = [];

// Query dasar untuk mengambil data
$sql = "SELECT * FROM mahasiswa";
// Query untuk menghitung total data
$sql_count = "SELECT COUNT(*) FROM mahasiswa";

if (!empty($search)) {
    // Tambahkan kondisi WHERE jika ada pencarian
    $sql .= " WHERE nama LIKE ?";
    $sql_count .= " WHERE nama LIKE ?";
    // Gunakan bind parameter untuk keamanan (Prepared Statement)
    $params[] = "%$search%";
}

// --- Menghitung Total Halaman untuk Paginasi ---
$stmt_count = $pdo->prepare($sql_count);
$stmt_count->execute($params);
$total_data = $stmt_count->fetchColumn();
$total_halaman = ceil($total_data / $limit);

// Tambahkan LIMIT dan OFFSET untuk paginasi ke query utama
$sql .= " LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset;

// Eksekusi query utama
$stmt = $pdo->prepare($sql);

// Binding parameter secara dinamis
// Kita perlu bind $limit dan $offset sebagai PDO::PARAM_INT
$i = 1;
foreach ($params as $key => $value) {
    if (is_int($value)) {
        $stmt->bindValue($i, $value, PDO::PARAM_INT);
    } else {
        $stmt->bindValue($i, $value, PDO::PARAM_STR);
    }
    $i++;
}

$stmt->execute();
$mahasiswa = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Mahasiswa</title>
    <style>
        body { font-family: sans-serif; margin: 2em; }
        table { border-collapse: collapse; width: 80%; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; }
        .container { width: 80%; margin: auto; }
        .flex-between { display: flex; justify-content: space-between; align-items: center; }
        .pagination a { margin: 0 5px; text-decoration: none; padding: 5px 10px; border: 1px solid #ccc; }
        .pagination a.active { background-color: #007bff; color: white; }
        .search-form { margin-bottom: 15px; }
        .btn { padding: 5px 10px; text-decoration: none; border-radius: 3px; }
        .btn-add { background-color: #28a745; color: white; }
        .btn-edit { background-color: #ffc107; color: black; }
        .btn-delete { background-color: #dc3545; color: white; }
    </style>
</head>
<body>

<div class="container">
    <h2>Data Mahasiswa</h2>

    <div class="flex-between">
        <a href="tambah.php" class="btn btn-add">Tambah Data</a>
        
        <form method="GET" action="index.php" class="search-form">
            <input type="text" name="search" placeholder="Cari berdasarkan nama..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Cari</button>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>NIM</th>
                <th>Nama</th>
                <th>Jurusan</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($mahasiswa) > 0): ?>
                <?php foreach ($mahasiswa as $mhs): ?>
                <tr>
                    <td><?= htmlspecialchars($mhs['nim']) ?></td>
                    <td><?= htmlspecialchars($mhs['nama']) ?></td>
                    <td><?= htmlspecialchars($mhs['jurusan']) ?></td>
                    <td><?= htmlspecialchars($mhs['email']) ?></td>
                    <td>
                        <a href="edit.php?id=<?= $mhs['id'] ?>" class="btn btn-edit">Edit</a>
                        <a href="hapus.php?id=<?= $mhs['id'] ?>" class="btn btn-delete" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center;">Data tidak ditemukan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="pagination" style="margin-top: 20px;">
        <?php for ($i = 1; $i <= $total_halaman; $i++): ?>
            <a href="?halaman=<?= $i ?>&search=<?= htmlspecialchars($search) ?>" class="<?= ($i == $halaman) ? 'active' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>
</div>

</body>
</html>