<?php 
require 'vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

// 1. Koneksi Database
$host = "localhost";
$user = "root";
$pass = "root";
$db   = "rsia_paramount";
$conn = new mysqli($host, $user, $pass, $db);

// 2. ambil data masukkan
$query = "SELECT * FROM masukan_rs ORDER BY tanggal_kirim DESC";
$result = $conn->query($query);

// 3. Desain HTML untuk PDF (Menggunakan Inline CSS karena PDF engine terbatas)
$html = '
<!DOCTYPE html>
<html>
<head>
  <div class="mt-6 border-t border-pink-100 pt-6 text-center">
    <p class="text-xs text-gray-400 mb-3">Khusus Staf Administrasi:</p>
    <a href="cetak_laporan.php" class="inline-flex items-center text-teal-600 font-bold hover:text-teal-700 transition">
        <i class="fas fa-file-pdf mr-2"></i> Unduh Laporan Masukan (PDF)
    </a>
  </div>
    <style>
        body { font-family: sans-serif; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #14B8A6; padding-bottom: 10px; margin-bottom: 20px; }
        .title { color: #EC4899; font-size: 24px; font-weight: bold; margin: 0; }
        .subtitle { color: #666; font-size: 12px; margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #14B8A6; color: white; padding: 10px; font-size: 12px; text-align: left; }
        td { border-bottom: 1px solid #eee; padding: 10px; font-size: 11px; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: right; font-size: 10px; color: #999; }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">RSIA PARAMOUNT</h1>
        <p class="subtitle">Laporan Masukan & Saran Pasien - Per April 2026</p>
        <p class="subtitle">Jl. Paramount No. 1, Makassar, Sulawesi Selatan</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Pasien</th>
                <th>Kategori</th>
                <th>Pesan/Saran</th>
            </tr>
        </thead>
        <tbody>';

$no = 1;
while($row = $result->fetch_assoc()) {
    $html .= '
            <tr>
                <td>' . $no++ . '</td>
                <td>' . date('d/m/Y', strtotime($row['tanggal_kirim'])) . '</td>
                <td>' . htmlspecialchars($row['nama_pasien']) . '</td>
                <td>' . htmlspecialchars($row['kategori']) . '</td>
                <td>' . htmlspecialchars($row['pesan']) . '</td>
            </tr>';
}

$html .= '
        </tbody>
    </table>
    <div class="footer">Dicetak pada: ' . date('d/m/Y H:i') . '</div>
</body>
</html>';

// 4. inisialisasi Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true); // Penting jika ada gambar eksternal

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);

// (Opsional) Atur Ukuran Kertas
$dompdf->setPaper('A4', 'portrait');

// Render HTML ke PDF
$dompdf->render();

// Output ke Browser (Download otomatis)
$dompdf->stream("Laporan_Masukan_RSIA_Paramount.pdf", array("Attachment" => 1));

?>