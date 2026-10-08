<?php
include 'koneksi.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: admin_dashboard.php");
    exit;
}

// Ambil data lama berdasarkan ID
$stmt = $conn->prepare("SELECT * FROM jadwal_dokter WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    echo "Data dokter tidak ditemukan!";
    exit;
}

$pesan_sukses = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_dokter = trim($_POST['nama_dokter']);
    $poliklinik  = trim($_POST['poliklinik']);
    $hari        = trim($_POST['hari_praktik']);
    $jam         = trim($_POST['jam_praktik']);
    $status      = trim($_POST['status']);
    $foto        = trim($_POST['foto']);

    $update = $conn->prepare("UPDATE jadwal_dokter SET nama_dokter=?, poliklinik=?, hari_praktik=?, jam_praktik=?, foto=?, status=? WHERE id=?");
    $update->bind_param("ssssssi", $nama_dokter, $poliklinik, $hari, $jam, $foto, $status, $id);

    if ($update->execute()) {
        $pesan_sukses = "Jadwal dokter berhasil diperbarui!";
        // Refresh data setelah update
        $row['nama_dokter'] = $nama_dokter;
        $row['poliklinik'] = $poliklinik;
        $row['hari_praktik'] = $hari;
        $row['jam_praktik'] = $jam;
        $row['status'] = $status;
        $row['foto'] = $foto;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Jadwal Dokter - RSIA Paramount</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600;700&display=swap');
        body { font-family: 'Quicksand', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen py-10">
    <div class="container mx-auto px-6 max-w-2xl">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800"><i class="fas fa-user-edit text-blue-500 mr-2"></i> Edit Jadwal Dokter</h1>
            <a href="admin_dashboard.php" class="text-sm font-semibold text-gray-600 hover:text-pink-600"><i class="fas fa-arrow-left mr-1"></i> Kembali ke Dashboard</a>
        </div>

        <?php if (!empty($pesan_sukses)): ?>
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl text-sm font-semibold">
                <?= $pesan_sukses; ?>
            </div>
        <?php endif; ?>

        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-200">
            <form action="" method="POST" class="space-y-5">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap & Gelar Dokter</label>
                    <input type="text" name="nama_dokter" value="<?= htmlspecialchars($row['nama_dokter']); ?>" required class="w-full p-4 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Poliklinik</label>
                    <select name="poliklinik" class="w-full p-4 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-400 cursor-pointer">
                        <option value="Poliklinik Obgyn (Kandungan)" <?= ($row['poliklinik'] == 'Poliklinik Obgyn (Kandungan)') ? 'selected' : ''; ?>>Poliklinik Obgyn (Kandungan)</option>
                        <option value="Poliklinik Pediatrik (Anak)" <?= ($row['poliklinik'] == 'Poliklinik Pediatrik (Anak)') ? 'selected' : ''; ?>>Poliklinik Pediatrik (Anak)</option>
                        <option value="Poliklinik laktasi & Anak" <?= ($row['poliklinik'] == 'Poliklinik laktasi & Anak') ? 'selected' : ''; ?>>Poliklinik Laktasi</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Hari Praktik</label>
                        <input type="text" name="hari_praktik" value="<?= htmlspecialchars($row['hari_praktik']); ?>" required class="w-full p-4 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Jam Praktik</label>
                        <input type="text" name="jam_praktik" value="<?= htmlspecialchars($row['jam_praktik']); ?>" required class="w-full p-4 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Status Ketersediaan</label>
                        <select name="status" class="w-full p-4 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-400 cursor-pointer">
                            <option value="Tersedia" <?= ($row['status'] == 'Tersedia') ? 'selected' : ''; ?>>Tersedia</option>
                            <option value="Cuti" <?= ($row['status'] == 'Cuti') ? 'selected' : ''; ?>>Cuti</option>
                            <option value="Penuh" <?= ($row['status'] == 'Penuh') ? 'selected' : ''; ?>>Penuh</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Path / Lokasi Foto</label>
                        <input type="text" name="foto" value="<?= htmlspecialchars($row['foto']); ?>" class="w-full p-4 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-blue-600 text-white py-4 rounded-xl font-bold hover:bg-blue-700 shadow-lg transition">
                        Perbarui Jadwal Dokter
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>