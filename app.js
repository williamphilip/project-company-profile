// Fungsi untuk membuka modal
function openModal() {
  document.getElementById("bookingModal").classList.remove("hidden");
  document.body.style.overflow = "hidden"; // Matikan scroll background
}

// Fungsi untuk menutup modal
function closeModal() {
  document.getElementById("bookingModal").classList.add("hidden");
  document.body.style.overflow = "auto"; // Aktifkan scroll background
}

// Fungsi simulasi pendaftaran
function submitRegistration() {
  // Logika sederhana: Menampilkan pesan sukses
  alert(
    "Terima kasih! Pendaftaran Anda telah diterima. Tim Admin RSIA Paramount akan menghubungi Anda melalui WhatsApp untuk konfirmasi jadwal.",
  );
  closeModal();
}

// Sambungkan tombol "Pendaftaran Online" di Navbar dan "Buat Janji" di card dokter ke fungsi openModal
document.addEventListener("DOMContentLoaded", () => {
  const btnNav = document.querySelector("nav button");
  btnNav.setAttribute("onclick", "openModal()");

  const btnDoctors = document.querySelectorAll("#dokter button");
  btnDoctors.forEach((btn) => btn.setAttribute("onclick", "openModal()"));
});

// ambil elemen - elemen yang dibutuhkan
const hamburgerBtn = document.getElementById("hamburger-btn");
const mobileMenu = document.getElementById("mobile-menu");
const menuIcon = hamburgerBtn.querySelector("i");

// fungsi klik menu hamburger
hamburgerBtn.addEventListener("click", () => {
  // toogle buka/tutup menu
  mobileMenu.classList.toggle("hidden");

  // ganti icons bars menjadi x (times) saat menu terbuka
  if (mobileMenu.classList.contains("hidden")) {
    menuIcon.classList.remove("fa-times");
    menuIcon.classList.add("fa-bars");
  } else {
    menuIcon.classList.remove("fa-bars");
    menuIcon.classList.add("fa-times");
  }
});

// otomatis tutup menu saat link diklik (agar tidak menutupi layar)
const navLinks = mobileMenu.querySelector("a");
navLinks.forEach((link) => {
  link.addEventListener("click", () => {
    mobileMenu.classList.add("hidden");
    menuIcon.classList.remove("fa-times");
    menuIcon.classList.add("fa-bars");
  });
});
