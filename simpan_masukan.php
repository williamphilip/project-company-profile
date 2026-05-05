<?php 
// konfigurasi database
$host = "localhost";
$user = "root";
$pass = "root";
$db = "rsia_paramount";

// koneksi
$koneksi = new mysqli($host, $user, $pass, $db);

// cek koneksi
if ($koneksi->connect_error) {
  die("Koneksi Gagal: " .$koneksi->connect_error);
}

// tangkap data dari form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nama = $_POST["nama"];
  $email = $_POST["email"];
  $no_hp = $_POST['no_hp'];
  $kategori = $_POST["kategori"];
  $pesan = $_POST["pesan"];
}

$sql = "INSERT INTO masukan_rs(nama_pasien, email, no_hp, kategori, pesan) VALUES ('$nama', '$email', '$no_hp', '$kategori', '$pesan')";

if ($koneksi->query($sql) === TRUE) {
  echo "<script>alert('Terima Kasih untuk Masukkan Yang Diberikan.'); window.location.href='index.php';</script>";
} else {
    echo "Error: " . $sql . "<br>" . $koneksi->error; 
}
$koneksi->close();
?>