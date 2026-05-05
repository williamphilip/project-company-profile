<?php 
session_start();

// Jika tidak ada session login, tendang balik ke halaman login
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// koneksi ke database
$host = "localhost";
$user = "root";
$pass = "root";
$db = "rsia_paramount";

$koneksi = new mysqli($host, $user, $pass, $db);

if ($koneksi->connect_error) {
  die ("Koneksi Gagal: " . $koneksi->connect_error);
}

// ambil data dari pendaftaran online
$sql = "SELECT * FROM pendaftaran_online ORDER BY tanggal_kunjungan DESC";
$result = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pendaftaran - RSIA Paramount</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600;700&display=swap');
        body { font-family: 'Quicksand', sans-serif; }
    </style>
</head>
<body class="bg-pink-50/30">

    <div class="bg-white shadow-sm border-b border-pink-100 sticky top-0 z-10">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <div class="bg-pink-500 p-2 rounded-lg">
                    <i class="fas fa-file-medical text-white text-xl"></i>
                </div>
                <h1 class="text-xl font-bold text-gray-800">Laporan <span class="text-pink-500">Pendaftaran Online</span></h1>
            </div>
            <div class="flex space-x-3">
                <a href="index.html" class="text-sm font-semibold text-gray-500 hover:text-pink-500 py-2">Kembali ke Beranda</a>
                <a href="cetak_laporan_pdf.php" class="bg-teal-500 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-teal-600 transition shadow-md">
                    <i class="fas fa-print mr-2"></i> Cetak PDF
                </a>
                <a href="logout.php" class="text-red-500 font-bold text-sm hover:underline">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </a>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-6 py-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white p-6 rounded-3xl border border-pink-100 shadow-sm">
                <p class="text-gray-500 text-sm font-semibold">Total Pendaftar</p>
                <h3 class="text-3xl font-bold text-pink-600"><?php echo $result->num_rows; ?></h3>
            </div>
            </div>

        <div class="bg-white rounded-[2rem] shadow-xl overflow-hidden border border-pink-50">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-teal-500 text-white">
                            <th class="py-5 px-6 font-bold uppercase text-xs tracking-wider">No</th>
                            <th class="py-5 px-6 font-bold uppercase text-xs tracking-wider">Nama Pasien</th>
                            <th class="py-5 px-6 font-bold uppercase text-xs tracking-wider">Kontak</th>
                            <th class="py-5 px-6 font-bold uppercase text-xs tracking-wider">Dokter Spesialis</th>
                            <th class="py-5 px-6 font-bold uppercase text-xs tracking-wider">Tanggal Kunjungan</th>
                            <th class="py-5 px-6 font-bold uppercase text-xs tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-pink-50">
                        <?php 
                        $no = 1;
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr class='hover:bg-pink-50/50 transition'>";
                                echo "<td class='py-5 px-6 text-sm text-gray-500'>".$no++."</td>";
                                echo "<td class='py-5 px-6 text-sm font-bold text-gray-800'>".htmlspecialchars($row['nama_pasien'])."</td>";
                                echo "<td class='py-5 px-6 text-sm text-gray-600'>".htmlspecialchars($row['nomor_wa'])."</td>";
                                echo "<td class='py-5 px-6 text-sm'><span class='bg-teal-50 text-teal-700 px-3 py-1 rounded-full font-semibold'>".htmlspecialchars($row['nama_dokter'])."</span></td>";
                                echo "<td class='py-5 px-6 text-sm text-gray-600'>".date('d M Y', strtotime($row['tanggal_kunjungan']))."</td>";
                                echo "<td class='py-5 px-6 text-center text-sm'>
                                        <button class='text-pink-500 hover:text-pink-700'><i class='fas fa-eye'></i> Detail</button>
                                    </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='py-10 text-center text-gray-400'>Belum ada data pendaftaran masuk.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>