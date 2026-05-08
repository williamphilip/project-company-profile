<?php
// Koneksi ke database
$host = "localhost";
$user = "root"; // sesuaikan username database Anda
$pass = "root";     // sesuaikan password database Anda
$db   = "rsia_paramount";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// cek apakah tombol submit_daftar sudah ditekan (dengan asumsi user sudah mengisi formnya) HANYA JALAN JIKA TOMBOL 'submit' DIKLIK
if (isset($_POST["submit_daftar"])) {
  $nama_dokter = $_POST['nama_dokter'];
  $nama_pasien = $_POST['nama_pasien'];
  $nomor_wa = $_POST['nomor_wa'];

  // Penanganan Error Tanggal (Kunci agar tidak error lagi)
  // Jika user tidak isi tanggal, gunakan tanggal hari ini
  $tanggal_kunjungan = !empty($_POST['tanggal_kunjungan']) ? $_POST['tanggal_kunjungan'] : date('Y-m-d');
}


// Query Insert (ID tidak perlu dimasukkan karena otomatis)
$sql = "INSERT INTO pendaftaran_online (nama_dokter, nama_pasien, tanggal_kunjungan, nomor_wa) VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $nama_dokter, $nama_pasien, $tanggal_kunjungan, $nomor_wa);

if ($stmt->execute()) {
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Pendaftaran pasien atas nama $nama_pasien telah tersimpan.',
                icon: 'success',
                confirmButtonText: 'Mantap!',
                confirmButtonColor: '#3085d6',
                background: '#ffffff',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'index.php'; // Arahkan kembali ke halaman utama
                }
            });
        });
    </script>";
} else {
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Waduh!',
                text: 'Terjadi kesalahan: " . $stmt->error . "',
                icon: 'error',
                confirmButtonText: 'Coba Lagi'
            });
        });
    </script>";
  $stmt->close();
}
$conn->close();
?>

<!doctype html>
<html lang="id" class="scroll-smooth">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RSIA Paramount</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="style.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
    />
    <style>
      @import url("https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600;700&display=swap");
      body {
        font-family: "Quicksand", sans-serif;
      }
      .bg-paramount-pink {
        background-color: #fce7f3;
      }
      .text-paramount-main {
        color: #db2777;
      }
      .bg-paramount-main {
        background-color: #db2777;
      }
    </style>
  </head>

  <div id="bookingModal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
      <div
        class="fixed inset-0 bg-black opacity-50 transition-opacity"
        onclick="closeModal()"
      ></div>

      <div
        class="relative bg-white rounded-3xl shadow-2xl max-w-lg w-full p-8 overflow-hidden transform transition-all"
      >
        <button
          onclick="closeModal()"
          class="absolute top-4 right-4 text-gray-400 hover:text-gray-600"
        >
          <i class="fas fa-times text-xl"></i>
        </button>

        <div class="text-center mb-8">
          <h3 class="text-2xl font-bold text-gray-900">Pendaftaran Online</h3>
          <p class="text-sm text-gray-500">RSIA Paramount - Cepat & Mudah</p>
        </div>

        <form id="appointmentForm" class="space-y-4" action="" method="post">
          <div class="space-y-4">
            <label class="block text-sm font-semibold text-gray-700" for="nama_dokter"
              >Nama Dokter</label
            >
            <select
              id="doctor"
              name="nama_dokter"
              class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-pink-500 outline-none transition"
            >
              <option value="">-- Pilih Dokter --</option>
              <option value="dr-amanda">dr. Amanda Putri, Sp.OG</option>
              <option value="dr-amanda">dr. Amelia Abdullah, Sp.OG</option>
              <option value="dr-budi">dr. Budi Utomo, Sp.A</option>
            </select>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div class="col-span-2">
              <label class="block text-sm font-semibold text-gray-700" for="nama_pasien"
                >Nama Lengkap Pasien</label
              >
              <input
                id="nama_pasien"
                type="text"
                name="nama_pasien"
                placeholder="Masukkan nama sesuai KTP"
                class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-pink-500 outline-none"
                required
              />
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700" for="tanggal_kunjungan"
                >Tanggal Kunjungan</label
              >
              <input
                id="tanggal_kunjungan"
                type="date"
                name="tanggal_kunjungan"
                class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-pink-500 outline-none"
                required
              />
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700" for="no_wa"
                >Nomor WhatsApp</label
              >
              <input
                id="no_wa"
                type="tel"
                name="nomor_wa"
                placeholder="0812..."
                class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-pink-500 outline-none"
              />
            </div>
          </div>

          <div class="pt-4">
            <button
              type="submit"
              name="submit_daftar"
              onclick="submitRegistration()"
              class="w-full bg-pink-600 text-white py-4 rounded-2xl font-bold shadow-lg hover:bg-pink-700 transform hover:scale-[1.02] transition"
            >
              Konfirmasi Pendaftaran
            </button>
            <p
              class="text-[10px] text-center text-gray-400 mt-4 uppercase tracking-widest"
            >
              Data Anda aman sesuai protokol privasi RSIA Paramount
            </p>
          </div>
        </form>
      </div>
    </div>
  </div>

  <body class="bg-gray-50">
    <nav class="bg-white/90 backdrop-blur-md shadow-sm sticky top-0 z-50">
      <div
        class="container mx-auto px-6 py-3 flex justify-between items-center"
      >
        <div class="flex items-center space-x-2">
          <div class="p-2 rounded-lg">
            <img src="assets/img/logo.png" alt="logo" class="w-10 h-auto" />
          </div>
          <span class="text-xl font-bold tracking-tight text-gray-800"
            >RSIA <span class="text-pink-600">PARAMOUNT</span></span
          >
        </div>
        <div
          class="hidden md:flex space-x-8 text-gray-600 font-semibold text-sm"
        >
          <a href="#home" class="hover:text-pink-600 transition">Beranda</a>
          <a href="#tentang" class="hover:text-pink-600 transition">Tentang</a>
          <a href="#layanan" class="hover:text-pink-600 transition">Layanan</a>
          <a href="#dokter" class="hover:text-pink-600 transition"
            >Jadwal Dokter</a
          >
          <a href="#kontak" class="hover:text-pink-600 transition"
            >Hubungi Kami</a
          >
        </div>
        <button
          class="bg-pink-600 text-white px-5 py-2 rounded-full text-sm font-bold shadow-md hover:bg-pink-700"
        >
          Pendaftaran Online
        </button>

        <!-- mobile view -->
        <div class="md:hidden flex items-center">
          <button
            id="hamburger-btn"
            class="outline-none p-2 text-gray-600 hover:text-pink-500 transition"
          >
            <i class="fas fa-bars text-2xl"></i>
          </button>
        </div>
      </div>
      <div
        id="mobile-menu"
        class="hidden md:hidden bg-white border-t border-pink-50 shadow-inner"
      >
        <div
          class="flex flex-col p-6 space-y-4 font-semibold text-gray-700 text-center"
        >
          <a
            href="#home"
            class="hover:text-pink-500 py-2 border-b border-gray-50"
            >Beranda</a
          >
          <a
            href="#tentang"
            class="hover:text-pink-500 py-2 border-b border-gray-50"
            >Tentang</a
          >
          <a
            href="#layanan"
            class="hover:text-pink-500 py-2 border-b border-gray-50"
            >Layanan</a
          >
          <a
            href="#dokter"
            class="hover:text-pink-500 py-2 border-b border-gray-50"
            >Jadwal Dokter</a
          >
          <a
            href="#kontak"
            class="hover:text-pink-500 py-2 border-b border-gray-50"
            >Hubungi Kami</a
          >
        </div>
      </div>
    </nav>

    <section id="home" class="relative bg-pink-50 overflow-hidden">
      <div
        class="container mx-auto px-6 py-20 flex flex-col md:flex-row items-center"
      >
        <div class="md:w-1/2 z-10">
          <span
            class="bg-pink-200 text-pink-700 px-4 py-1 rounded-full text-xs font-bold uppercase tracking-widest"
            >Sahabat Ibu & Buah Hati</span
          >
          <h1
            class="text-4xl md:text-6xl font-bold text-gray-900 mt-4 leading-tight"
          >
            Momen Berharga Dimulai dengan
            <span class="text-pink-600">Perawatan Terbaik.</span>
          </h1>
          <p class="text-gray-600 mt-6 text-lg leading-relaxed">
            RSIA Paramount hadir memberikan kenyamanan ekstra untuk proses
            kehamilan, persalinan, hingga tumbuh kembang anak Anda.
          </p>
          <div class="mt-10 flex space-x-4">
            <a
              href="#layanan"
              class="bg-pink-600 text-white px-8 py-3 rounded-xl font-bold hover:shadow-lg transition"
              >Layanan Kami</a
            >
            <a
              href="https://wa.me/12345678"
              class="bg-white border border-pink-200 text-pink-600 px-8 py-3 rounded-xl font-bold hover:bg-pink-50 transition flex items-center"
            >
              <i class="fab fa-whatsapp mr-2"></i> Konsultasi WA
            </a>
          </div>
        </div>
        <div class="md:w-1/2 mt-12 md:mt-0 relative">
          <div
            class="absolute -top-10 -right-10 w-64 h-64 bg-pink-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-pulse"
          ></div>
          <img
            src="assets/img/hero_section.jpg"
            alt="Ibu dan Bayi"
            class="relative z-10 rounded-3xl shadow-2xl border-8 border-white"
          />
        </div>
      </div>
    </section>

    <div class="container mx-auto px-6 -mt-10 relative z-20">
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-6 rounded-2xl shadow-lg text-center">
          <h3 class="text-3xl font-bold text-pink-600">20+</h3>
          <p class="text-gray-500 text-sm">Dokter Spesialis</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-lg text-center">
          <h3 class="text-3xl font-bold text-pink-600">10k+</h3>
          <p class="text-gray-500 text-sm">Persalinan Sukses</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-lg text-center">
          <h3 class="text-3xl font-bold text-pink-600">24/7</h3>
          <p class="text-gray-500 text-sm">Layanan Darurat</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-lg text-center">
          <h3 class="text-3xl font-bold text-pink-600">A</h3>
          <p class="text-gray-500 text-sm">Akreditasi Paripurna</p>
        </div>
      </div>
    </div>

    <section id="layanan" class="py-24">
      <div class="container mx-auto px-6">
        <div class="text-center max-w-2xl mx-auto mb-16">
          <h2 class="text-3xl font-bold text-gray-900">Layanan Komprehensif</h2>
          <p class="text-gray-500 mt-4">
            Kami menyediakan fasilitas modern yang dirancang khusus untuk
            kenyamanan Ibu dan Anak.
          </p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div
            class="group p-8 bg-white rounded-3xl border border-gray-100 hover:border-pink-200 hover:shadow-2xl transition duration-300"
          >
            <div
              class="w-14 h-14 bg-pink-100 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-pink-600 transition"
            >
              <i
                class="fas fa-stethoscope text-pink-600 group-hover:text-white text-2xl"
              ></i>
            </div>
            <h4 class="text-xl font-bold mb-3">Klinik Obgyn</h4>
            <p class="text-gray-500 leading-relaxed">
              Pemeriksaan kehamilan rutin, USG 4D, dan konsultasi program
              kehamilan oleh ahli.
            </p>
          </div>
          <div
            class="group p-8 bg-white rounded-3xl border border-gray-100 hover:border-pink-200 hover:shadow-2xl transition duration-300"
          >
            <div
              class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-blue-600 transition"
            >
              <i
                class="fas fa-child text-blue-600 group-hover:text-white text-2xl"
              ></i>
            </div>
            <h4 class="text-xl font-bold mb-3">Pediatrik (Anak)</h4>
            <p class="text-gray-500 leading-relaxed">
              Layanan imunisasi, tumbuh kembang, dan penanganan penyakit anak
              dengan dokter yang ramah.
            </p>
          </div>
          <div
            class="group p-8 bg-white rounded-3xl border border-gray-100 hover:border-pink-200 hover:shadow-2xl transition duration-300"
          >
            <div
              class="w-14 h-14 bg-purple-100 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-purple-600 transition"
            >
              <i
                class="fas fa-bed text-purple-600 group-hover:text-white text-2xl"
              ></i>
            </div>
            <h4 class="text-xl font-bold mb-3">Rawat Inap Eksklusif</h4>
            <p class="text-gray-500 leading-relaxed">
              Kamar rawat inap dengan suasana *homey* layaknya hotel bintang
              lima untuk pemulihan Ibu.
            </p>
          </div>
        </div>
      </div>
    </section>

    <section id="dokter" class="py-24 bg-pink-50">
      <div class="container mx-auto px-6">
        <div class="flex flex-col md:flex-row justify-between items-end mb-12">
          <div>
            <h2 class="text-3xl font-bold text-gray-900">
              Dokter Spesialis Kami
            </h2>
            <p class="text-gray-500 mt-2">
              Tim ahli yang siap mendampingi setiap langkah kesehatan keluarga
              Anda.
            </p>
          </div>
          <a
            href="#"
            class="text-pink-600 font-bold hover:underline mt-4 md:mt-0"
            >Lihat Semua Dokter <i class="fas fa-arrow-right ml-2"></i
          ></a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <div
            class="bg-white p-4 rounded-3xl shadow-sm hover:shadow-xl transition"
          >
            <img
              src="assets/img/prof_nusrat.png"
              alt="Dokter"
              class="w-full h-64 object-cover rounded-2xl mb-4"
            />
            <h5 class="text-lg font-bold text-gray-900">
              Prof.Dr.dr. Nusratuddin Abdullah, Sp.OG.,Subsp. FER., MARS
            </h5>
            <p class="text-pink-600 text-sm font-semibold">
              Spesialis Kebidanan & Kandungan
            </p>
            <div
              class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center text-xs text-gray-500"
            >
              <span
                ><i class="far fa-calendar-alt mr-1"></i> Sen, Rab, Jum</span
              >
              <span class="bg-green-100 text-green-700 px-2 py-1 rounded"
                >Tersedia</span
              >
            </div>
          </div>
          <div
            class="bg-white p-4 rounded-3xl shadow-sm hover:shadow-xl transition"
          >
            <img
              src="assets/img/dr_ferry.png"
              alt="Dokter"
              class="w-full h-64 object-cover rounded-2xl mb-4"
            />
            <h5 class="text-lg font-bold text-gray-900">
              dr. Ferry Wijaya, Sp.OG ., Subsp., FER
            </h5>
            <p class="text-pink-500 text-sm font-semibold">
              Spesialis Kebidanan & Kandungan
            </p>
            <div
              class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center text-xs text-gray-500"
            >
              <span
                ><i class="far fa-calendar-alt mr-1"></i> Sel, Kam, Sab</span
              >
              <span class="bg-green-100 text-green-700 px-2 py-1 rounded"
                >Tersedia</span
              >
            </div>
          </div>
          <div
            class="bg-white p-4 rounded-3xl shadow-sm hover:shadow-xl transition"
          >
            <img
              src="assets/img/dr_amel.png"
              alt="Dokter"
              class="w-full h-64 object-cover rounded-2xl mb-4"
            />
            <h5 class="text-lg font-bold text-gray-900">
              dr. Amelia Abdullah, Sp.OG
            </h5>
            <p class="text-pink-500 text-sm font-semibold">
              Spesialis Kebidanan & Kandungan
            </p>
            <div
              class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center text-xs text-gray-500"
            >
              <span
                ><i class="far fa-calendar-alt mr-1"></i> Sel, Kam, Sab</span
              >
              <span class="bg-green-100 text-green-700 px-2 py-1 rounded"
                >Tersedia</span
              >
            </div>
          </div>
        </div>
      </div>
    </section>

     <section id="feedback" class="py-20 bg-white">
      <div class="container mx-auto px-6">
        <div
          class="bg-white rounded-[3rem] overflow-hidden shadow-2xl flex flex-col md:flex-row border border-pink-100"
        >
          <div
            class="md:w-1/3 p-12 text-white bg-teal-500 relative overflow-hidden"
          >
            <i
              class="fas fa-heart absolute -bottom-10 -right-10 text-9xl opacity-10"
            ></i>

            <h3 class="text-3xl font-bold mb-6">
              Suara Anda Adalah
              <span class="text-pink-200">Kekuatan Kami.</span>
            </h3>
            <p class="text-teal-50 mb-8 leading-relaxed">
              Bantu RSIA Paramount untuk terus meningkatkan kualitas layanan
              bagi Ibu dan Anak. Setiap saran Anda sangat kami hargai.
            </p>

            <div
              class="flex items-center space-x-4 bg-teal-600 p-4 rounded-2xl"
            >
              <div class="bg-white p-3 rounded-full text-teal-500">
                <i class="fas fa-bullhorn"></i>
              </div>
              <div>
                <p class="text-xs text-teal-100 uppercase font-bold">
                  Hotline Pengaduan
                </p>
                <span class="font-bold text-lg">(0411) 999 001</span>
              </div>
            </div>
          </div>

          <div class="md:w-2/3 p-12 bg-white">
            <form
              action="simpan_masukan.php"
              method="POST"
              class="grid grid-cols-1 md:grid-cols-2 gap-6"
            >
              <div>
                <label class="block text-sm font-bold text-gray-700 mb-2"
                  >Nama Lengkap</label
                >
                <input
                  type="text"
                  name="nama"
                  required
                  placeholder="Nama Ibu / Pasien"
                  class="w-full p-4 bg-pink-50/30 border border-pink-100 rounded-xl focus:ring-2 focus:ring-pink-400 focus:bg-white outline-none transition-all"
                />
              </div>
              <div>
                <label class="block text-sm font-bold text-gray-700 mb-2"
                  >Email Aktif</label
                >
                <input
                  type="email"
                  name="email"
                  required
                  placeholder="email@contoh.com"
                  class="w-full p-4 bg-pink-50/30 border border-pink-100 rounded-xl focus:ring-2 focus:ring-pink-400 focus:bg-white outline-none transition-all"
                />
              </div>
              <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">No. Handphone (WhatsApp)</label>
                <input type="tel" name="no_hp" required placeholder="0812xxxx" class="w-full p-4 bg-pink-50/30 border border-pink-100 rounded-xl focus:ring-2 focus:ring-pink-400 outline-none transition">
              </div>
              <div class="col-span-2">
                <label class="block text-sm font-bold text-gray-700 mb-2"
                  >Kategori Masukan</label
                >
                <select
                  name="kategori"
                  class="w-full p-4 bg-pink-50/30 border border-pink-100 rounded-xl focus:ring-2 focus:ring-pink-400 outline-none cursor-pointer"
                >
                  <option value="Pelayanan">Kualitas Pelayanan</option>
                  <option value="Fasilitas">Fasilitas & Kebersihan</option>
                  <option value="Tenaga Medis">Kepuasan Tenaga Medis</option>
                  <option value="Lainnya">Lain-lain</option>
                </select>
              </div>
              <div class="col-span-2">
                <label class="block text-sm font-bold text-gray-700 mb-2"
                  >Pesan / Saran</label
                >
                <textarea
                  name="pesan"
                  rows="4"
                  required
                  placeholder="Tuliskan pengalaman atau saran Anda secara detail..."
                  class="w-full p-4 bg-pink-50/30 border border-pink-100 rounded-xl focus:ring-2 focus:ring-pink-400 focus:bg-white outline-none transition-all"
                ></textarea>
              </div>
              <div class="col-span-2">
                <button
                  type="submit"
                  class="w-full bg-pink-500 text-white py-4 rounded-xl font-bold text-lg hover:bg-pink-600 hover:shadow-pink-200 transition shadow-lg transform active:scale-95"
                >
                  KIRIM MASUKAN SEKARANG
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>

    <footer id="kontak" class="bg-gray-900 text-white pt-20 pb-10">
      <div
        class="container mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-12"
      >
        <div class="col-span-1 md:col-span-1">
          <div class="flex items-center space-x-2 mb-6">
            <img src="assets/img/logo.png" alt="logo" class="w-10 h-auto" />
            <span class="text-xl font-bold tracking-tight">RSIA PARAMOUNT</span>
          </div>
          <p class="text-gray-400 leading-relaxed text-sm">
            Rumah Sakit Ibu dan Anak pilihan keluarga dengan standar layanan
            medis internasional dan sentuhan kasih sayang.
          </p>
        </div>
        <div>
          <h4 class="font-bold mb-6 italic">Navigasi</h4>
          <ul class="text-gray-400 space-y-3 text-sm">
            <li>
              <a href="#" class="hover:text-pink-400 transition"
                >Tentang Kami</a
              >
            </li>
            <li>
              <a href="#" class="hover:text-pink-400 transition"
                >Layanan Medis</a
              >
            </li>
            <li>
              <a href="#" class="hover:text-pink-400 transition"
                >Paket Persalinan</a
              >
            </li>
            <li>
              <a href="#" class="hover:text-pink-400 transition"
                >Artikel Kesehatan</a
              >
            </li>
          </ul>
        </div>
        <div>
          <h4 class="font-bold mb-6 italic">Kontak</h4>
          <ul class="text-gray-400 space-y-3 text-sm">
            <li>
              <i class="fas fa-map-marker-alt mr-2 text-pink-500"></i> Jl. A. P.
              Pettarani No.82, Kota Makassar, Sulawesi Selatan 90231
            </li>
            <li>
              <i class="fas fa-phone mr-2 text-pink-500"></i> (0411) 4671666
            </li>
            <li>
              <i class="fas fa-envelope mr-2 text-pink-500"></i>
              rsiaparamounthospital@gmail.com
            </li>
          </ul>
        </div>
        <div>
          <h4 class="font-bold mb-6 italic">Newsletter</h4>
          <p class="text-gray-400 text-sm mb-4">
            Dapatkan info kesehatan ibu & anak gratis.
          </p>
          <div class="flex">
            <input
              type="email"
              placeholder="Email Anda"
              class="bg-gray-800 border-none rounded-l-lg px-4 py-2 w-full focus:ring-2 focus:ring-pink-500"
            />
            <button class="bg-pink-600 px-4 rounded-r-lg hover:bg-pink-700">
              <i class="fas fa-paper-plane"></i>
            </button>
          </div>
        </div>
      </div>
      <div
        class="container mx-auto px-6 border-t border-gray-800 mt-16 pt-8 text-center text-gray-500 text-xs"
      >
        <p>
          &copy; 2026 RSIA Paramount. All rights reserved. Created IT RSIA Paramount.
        </p>
      </div>
    </footer>

    <script src="app.js"></script>
    
  </body>
</html>
