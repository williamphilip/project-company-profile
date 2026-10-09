<?php
include 'koneksi.php';

// Ambil semua data dokter dari database
$sql_dokter = "SELECT * FROM jadwal_dokter ORDER BY nama_dokter ASC";
$result_dokter = $conn->query($sql_dokter);
?>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Jadwal Dokter - RSIA Paramount</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600;700&display=swap');
        body { font-family: 'Quicksand', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    <!-- Navbar Sederhana -->
    <nav class="bg-white/90 backdrop-blur-md shadow-sm sticky top-0 z-50">
      <div class="container mx-auto px-6 py-4 flex justify-between items-center">
        <div class="flex items-center space-x-2">
          <img src="assets/img/logo.png" alt="logo" class="w-10 h-auto" onerror="this.style.display='none'" />
          <span class="text-xl font-bold tracking-tight text-gray-800">RSIA <span class="text-pink-600">PARAMOUNT</span></span>
        </div>
        <a href="index.php" class="bg-pink-50 text-pink-600 hover:bg-pink-100 px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2">
          <i class="fas fa-arrow-left"></i> Kembali ke Beranda
        </a>
      </div>
    </nav>

    <!-- Header Judul -->
    <header class="bg-pink-50 py-12 px-6 border-b border-pink-100">
        <div class="container mx-auto text-center max-w-2xl">
            <span class="text-pink-600 font-bold uppercase tracking-wider text-xs bg-pink-100 px-3 py-1 rounded-full">Direktori Medis</span>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mt-3">Jadwal Lengkap Dokter Spesialis</h1>
            <p class="text-gray-500 mt-2 text-sm md:text-base">Temukan dokter spesialis terbaik kami dan jadwalkan kunjungan Anda dengan mudah.</p>
        </div>
    </header>

    <!-- Grid Semua Dokter -->
    <main class="container mx-auto px-6 py-12">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php if ($result_dokter && $result_dokter->num_rows > 0): ?>
                <?php while($row = $result_dokter->fetch_assoc()): 
                    $badge_color = ($row['status'] == 'Tersedia') ? 'bg-teal-500' : 'bg-orange-500';
                ?>
                    <div class="bg-white p-6 rounded-3xl shadow-md hover:shadow-xl transition border border-pink-100 flex flex-col justify-between">
                        <div>
                            <div class="relative mb-6">
                                <img
                                    src="<?= htmlspecialchars($row['foto']); ?>"
                                    alt="<?= htmlspecialchars($row['nama_dokter']); ?>"
                                    class="w-full h-72 object-cover object-top rounded-2xl bg-gray-50"
                                    onerror="this.src='assets/img/default-doctor.png'"
                                />
                                <span class="absolute top-3 right-3 <?= $badge_color; ?> text-white text-xs px-3 py-1 rounded-full font-bold shadow">
                                    <?= htmlspecialchars($row['status']); ?>
                                </span>
                            </div>
                            
                            <!-- Nama Poliklinik -->
                            <span class="inline-block bg-pink-100 text-pink-600 text-xs font-bold px-3 py-1 rounded-lg mb-2">
                                <i class="fas fa-clinic-medical mr-1"></i> <?= htmlspecialchars($row['poliklinik']); ?>
                            </span>

                            <h5 class="text-lg font-bold text-gray-900 leading-snug">
                                <?= htmlspecialchars($row['nama_dokter']); ?>
                            </h5>
                        </div>

                        <div class="mt-6 pt-4 border-t border-gray-100 space-y-2">
                            <div class="flex items-center text-xs text-gray-600 font-semibold">
                                <i class="far fa-calendar-alt text-pink-500 w-5"></i>
                                <span><?= htmlspecialchars($row['hari_praktik']); ?></span>
                            </div>
                            <div class="flex items-center text-xs text-gray-600 font-semibold">
                                <i class="far fa-clock text-pink-500 w-5"></i>
                                <span><?= htmlspecialchars($row['jam_praktik']); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-span-3 text-center py-16 text-gray-400">
                    <i class="fas fa-user-md text-4xl mb-3 text-gray-300"></i>
                    <p class="text-base font-semibold">Belum ada data jadwal dokter yang tersedia di sistem.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer Sederhana -->
    <footer class="bg-gray-900 text-white py-8 text-center text-xs mt-12">
        <p>&copy; 2026 RSIA Paramount. All rights reserved.</p>
    </footer>

</body>
</html>