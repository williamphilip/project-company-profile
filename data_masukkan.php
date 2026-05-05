<?php
session_start();
// Proteksi: Jika belum login, dialihkan ke halaman login
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: loginDataMasukkan.php");
    exit;
}

// Koneksi Database
$conn = new mysqli("localhost", "root", "root", "rsia_paramount");

// Ambil data masukan
$sql = "SELECT * FROM masukan_rs ORDER BY tanggal_kirim DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Masukan Pasien - RSIA Paramount</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-pink-50/30 min-h-screen">

    <nav class="bg-white shadow-sm border-b border-pink-100 py-4">
        <div class="container mx-auto px-6 flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-800"><i class="fas fa-comment-dots text-pink-500 mr-2"></i> Laporan <span class="text-teal-500">Masukan</span></h1>
            <div class="flex space-x-6 items-center">
                <a href="#" class="text-sm font-semibold text-gray-500 hover:text-teal-500">Data Pendaftaran</a>
                <a href="logoutDataMasukkan.php" class="bg-pink-500 text-white px-4 py-2 rounded-full text-xs font-bold shadow-md">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-6 py-10">
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800">Daftar Kritik & Saran Pasien</h2>
            <p class="text-gray-500 text-sm">Berikut adalah feedback terbaru untuk meningkatkan layanan RSIA Paramount.</p>
        </div>

        <div class="bg-white rounded-[2rem] shadow-xl overflow-hidden border border-pink-100">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-teal-500 text-white">
                            <th class="py-5 px-6 font-bold uppercase text-xs">Tanggal</th>
                            <th class="py-5 px-6 font-bold uppercase text-xs">Nama Pasien</th>
                            <th class="py-5 px-6 font-bold uppercase text-xs">No. WhatssApp</th>
                            <th class="py-5 px-6 font-bold uppercase text-xs">Kategori</th>
                            <th class="py-5 px-6 font-bold uppercase text-xs">Pesan / Saran</th>
                            <th class="py-5 px-6 font-bold uppercase text-xs text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-pink-50">
                        <?php 
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr class='hover:bg-pink-50/50 transition'>";
                                echo "<td class='py-5 px-6 text-xs text-gray-400'>".date('d/m/y H:i', strtotime($row['tanggal_kirim']))."</td>";
                                echo "<td class='py-5 px-6'>
                                        <div class='font-bold text-gray-800'>".htmlspecialchars($row['nama_pasien'])."</div>
                                        <div class='text-xs text-gray-400'>".htmlspecialchars($row['email'])."</div>
                                      </td>";
                                echo "<td class='py-5 px-6'>".htmlspecialchars($row['no_hp']);"</td>";      
                                echo "<td class='py-5 px-6'>
                                        <span class='bg-pink-100 text-pink-600 px-3 py-1 rounded-full text-[10px] font-bold uppercase'>".htmlspecialchars($row['kategori'])."</span>
                                      </td>";
                                echo "<td class='py-5 px-6 text-sm text-gray-600 leading-relaxed max-w-xs'>".htmlspecialchars($row['pesan'])."</td>";
                                echo "<td class='py-5 px-6 text-center'>
                                        <button class='text-teal-500 hover:text-teal-700 text-sm font-bold'><i class='fas fa-check-circle'></i> Selesai</button>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5' class='py-10 text-center text-gray-400'>Belum ada masukan yang diterima.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>