<?php
session_start();
if (!isset($_SESSION['admin_dokter_logged_in'])) {
    header("Location: login_dokter.php");
    exit;
}

include 'koneksi.php';

// Ambil semua data dokter dari database
$sql = "SELECT * FROM jadwal_dokter ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Manajemen Jadwal Dokter - RSIA Paramount</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600;700&display=swap');
        body { font-family: 'Quicksand', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    <!-- Sidebar / Top Navigation Admin yang Responsive -->
    <header class="bg-white border-b border-gray-200 py-4 px-4 md:px-8 flex flex-col sm:flex-row justify-between items-center shadow-sm gap-4">
        <div class="flex items-center space-x-3 w-full sm:w-auto justify-between sm:justify-start">
            <div class="flex items-center space-x-3">
                <div class="bg-pink-600 text-white p-2.5 rounded-xl">
                    <i class="fas fa-hospital-user text-xl"></i>
                </div>
                <div>
                    <h1 class="font-bold text-gray-800 text-base md:text-lg">Admin Dashboard</h1>
                    <p class="text-xs text-gray-400">RSIA Paramount</p>
                </div>
            </div>
            <!-- Tombol Keluar khusus tampilan mobile agar tetap sejajar di atas -->
            <a href="logout_dokter.php" class="sm:hidden bg-red-50 text-red-600 px-3 py-2 rounded-xl text-xs font-bold hover:bg-red-100 transition flex items-center">
                <i class="fas fa-sign-out-alt mr-1"></i> Keluar
            </a>
        </div>

        <div class="flex flex-wrap items-center justify-end gap-3 sm:gap-4 w-full sm:w-auto">
            <a href="index.php" target="_blank" class="text-xs md:text-sm text-gray-600 hover:text-pink-600 font-semibold">
                <i class="fas fa-external-link-alt mr-1"></i> Website Utama
            </a>
            <span class="text-gray-300 hidden sm:inline">|</span>
            <span class="text-xs md:text-sm font-bold text-pink-600 bg-pink-50 px-3 py-1 rounded-full">Role: Staff / Admin</span>
            
            <!-- Tombol Keluar untuk tampilan layar besar (Desktop) -->
            <a href="logout_dokter.php" class="hidden sm:flex bg-red-50 text-red-600 px-4 py-2 rounded-xl text-xs font-bold hover:bg-red-100 transition items-center">
                <i class="fas fa-sign-out-alt mr-1"></i> Keluar
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 md:px-6 py-6 md:py-10">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div>
                <h2 class="text-xl md:text-2xl font-bold text-gray-800">Daftar Jadwal Dokter</h2>
                <p class="text-xs md:text-sm text-gray-500 mt-1">Kelola data dokter, poliklinik, serta jam praktik yang tampil di halaman utama.</p>
            </div>
            <a href="tambah_dokter.php" class="w-full sm:w-auto bg-pink-600 hover:bg-pink-700 text-white font-bold px-5 py-3 rounded-xl shadow-lg transition flex items-center justify-center text-sm">
                <i class="fas fa-plus mr-2"></i> Tambah Dokter Baru
            </a>
        </div>

        <!-- Tabel Data Dokter yang Responsif (Bisa digeser horizontal di layar HP) -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[700px]">
                    <thead>
                        <tr class="bg-pink-50 text-gray-700 text-xs uppercase tracking-wider font-bold border-b border-gray-200">
                            <th class="py-4 px-6">Dokter & Foto</th>
                            <th class="py-4 px-6">Poliklinik</th>
                            <th class="py-4 px-6">Hari & Jam Praktik</th>
                            <th class="py-4 px-6 text-center">Status</th>
                            <th class="py-4 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="py-4 px-6">
                                        <div class="flex items-center space-x-3">
                                            <img src="<?= htmlspecialchars($row['foto']); ?>" alt="Foto" class="w-10 h-10 md:w-12 md:h-12 object-cover rounded-xl bg-gray-100 border shrink-0" onerror="this.src='assets/img/default-doctor.png'">
                                            <div>
                                                <div class="font-bold text-gray-900 text-xs md:text-sm"><?= htmlspecialchars($row['nama_dokter']); ?></div>
                                                <div class="text-[11px] text-gray-400">ID: #<?= $row['id']; ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 font-semibold text-gray-600">
                                        <span class="bg-pink-100 text-pink-600 px-3 py-1 rounded-lg text-xs">
                                            <?= htmlspecialchars($row['poliklinik']); ?>
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-gray-600">
                                        <div class="font-semibold text-xs md:text-sm"><i class="far fa-calendar-alt text-pink-500 mr-1"></i> <?= htmlspecialchars($row['hari_praktik']); ?></div>
                                        <div class="text-xs text-gray-400 mt-0.5"><i class="far fa-clock text-pink-500 mr-1"></i> <?= htmlspecialchars($row['jam_praktik']); ?></div>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <?php if ($row['status'] == 'Tersedia'): ?>
                                            <span class="bg-teal-100 text-teal-700 text-xs font-bold px-3 py-1 rounded-full">Tersedia</span>
                                        <?php else: ?>
                                            <span class="bg-orange-100 text-orange-700 text-xs font-bold px-3 py-1 rounded-full"><?= htmlspecialchars($row['status']); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <div class="inline-flex items-center space-x-2">
                                            <a href="edit_dokter.php?id=<?= $row['id']; ?>" class="inline-block bg-blue-50 text-blue-600 p-2.5 rounded-xl hover:bg-blue-100 transition" title="Edit Dokter">
                                                <i class="fas fa-edit text-xs"></i>
                                            </a>
                                            <a href="hapus_dokter.php?id=<?= $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus jadwal dokter ini?');" class="inline-block bg-red-50 text-red-600 p-2.5 rounded-xl hover:bg-red-100 transition" title="Hapus Dokter">
                                                <i class="fas fa-trash-alt text-xs"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-400 text-xs">Belum ada data dokter tersimpan di database.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</body>
</html>