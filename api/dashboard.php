<?php
session_start();
if (!isset($_SESSION['id_pengguna'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Dashboard - CuacaKu</title>
</head>

<body class="bg-sky-100 text-gray-800 min-h-screen">

  <!-- navbar -->
  <nav class="bg-sky-200 fixed w-full z-20 top-0 border-b border-sky-300">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto px-6 py-4">
      <a href="dashboard.php" class="flex items-center gap-3">
        <img src="../1f324_3d.webp" class="h-7" alt="Logo Cuaca" />
        <span class="text-xl text-gray-900 font-semibold whitespace-nowrap">Website Prediksi Cuaca</span>
      </a>
      <div class="flex items-center gap-6">
        <ul class="flex flex-row font-medium gap-8">
          <li><a href="dashboard.php" class="text-gray-700 hover:text-blue-600 transition-colors">Home</a></li>
          <li><a href="dashboard.php#tentang" class="text-gray-700 hover:text-blue-600 transition-colors">Tentang</a></li>
          <li><a href="dashboard.php#dataiklim" class="text-gray-700 hover:text-blue-600 transition-colors">Data Iklim</a></li>
        </ul>
        <!-- tampilkan nama user dari session -->
        <span class="text-sm text-gray-600">👋 <?php echo htmlspecialchars($_SESSION['nama']); ?></span>
        <a href="logout.php"
          class="text-sm bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl transition-colors">
          Logout
        </a>
      </div>
    </div>
  </nav>

  <div class="h-16"></div>

  <!-- hero section -->
  <section id="home" class="bg-gradient-to-br from-blue-500 to-blue-600 text-white py-24 px-6 text-center">
    <span class="text-7xl">⛅</span>
    <h1 class="text-4xl sm:text-5xl font-bold mt-6 mb-4">Sistem Prediksi Cuaca</h1>
    <p class="text-blue-100 text-lg mb-10 max-w-xl mx-auto">
      Informasi cuaca akurat dan cepat untuk wilayah Jawa Timur
    </p>
    <a href="cuaca.php"
       class="bg-white text-blue-600 font-semibold px-8 py-3 rounded-xl
        hover:bg-blue-50 inline-flex items-center justify-center gap-2">
      Cek Prediksi Cuaca
      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M5 12h14M12 5l7 7-7 7"/>
      </svg>
    </a>
  </section>

  <!-- section tentang -->
  <section id="tentang" class="py-16 px-6 bg-sky-200">
    <div class="max-w-4xl mx-auto">
      <div class="text-center mb-12">
        <h2 class="text-3xl font-bold text-gray-900 mb-3">Tentang Sistem Prediksi Cuaca</h2>
        <p class="text-gray-600 max-w-xl mx-auto">
          Kami hadir untuk memberikan informasi cuaca yang akurat dan mudah dipahami oleh semua kalangan masyarakat.
        </p>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center mb-10">
        <div>
          <h3 class="text-xl font-bold text-gray-900 mb-4">Apa itu Sistem Prediksi Cuaca?</h3>
          <p class="text-gray-600 text-sm leading-relaxed mb-4">
            Sistem ini adalah website prediksi cuaca berbasis kecerdasan buatan yang dirancang untuk memberikan informasi cuaca yang akurat.
          </p>
          <p class="text-gray-600 text-sm leading-relaxed mb-6">
            Kami hadir untuk membantu warga Jawa Timur lebih siap menghadapi perubahan cuaca setiap harinya.
          </p>
          <div class="space-y-3">
            <div class="flex items-center gap-3">
              <div class="w-5 h-5 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
              </div>
              <p class="text-sm text-gray-700">Data diperbarui setiap 30 menit</p>
            </div>
            <div class="flex items-center gap-3">
              <div class="w-5 h-5 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
              </div>
              <p class="text-sm text-gray-700">Mencakup seluruh wilayah Jawa Timur</p>
            </div>
            <div class="flex items-center gap-3">
              <div class="w-5 h-5 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
              </div>
              <p class="text-sm text-gray-700">Dijamin akurat</p>
            </div>
          </div>
        </div>
        <div class="bg-white/60 rounded-3xl p-10 text-center border border-sky-300">
          <span class="text-8xl">🌤️</span>
          <p class="text-blue-600 font-semibold mt-6 text-lg">Akurat, Cepat, dan Terpercaya</p>
          <p class="text-blue-500 text-sm mt-2">Sistem prediksi cuaca terbaik untuk wilayah Jawa Timur</p>
        </div>
      </div>
    </div>
  </section>

  <!-- section data iklim BPS -->
  <section id="dataiklim" class="py-16 px-6 bg-white">
    <div class="max-w-4xl mx-auto">
      <div class="text-center mb-10">
        <h2 class="text-3xl font-bold text-gray-900 mb-3">Data Iklim Provinsi Jawa Timur</h2>
      </div>
      <div id="tabelIklim" class="bg-white rounded-2xl border border-sky-200 shadow-sm p-6 overflow-x-auto">
        <p class="text-center text-gray-400 text-sm">Memuat data...</p>
      </div>
    </div>
  </section>

  <footer class="bg-sky-400 border-t border-sky-500 py-8 px-6 text-center">
    <p class="text-sm text-sky-100">© 2026 CuacaKu. Sistem Prediksi Cuaca Jawa Timur.</p>
  </footer>

          <script>
        fetch('Iklim.php')
          .then(res => res.text())
          .then(text => {
            const data = JSON.parse(text);
            if (data.status === 'OK') {
              const tabelBersih = data.tabel
                .replace(/\\r\\n/g, '')
                .replace(/\\t/g, '')
                .replace(/\\"/g, '"');
              document.getElementById('tabelIklim').innerHTML = `
                <style>
                  #tabelIklim table { width:100%; border-collapse:collapse; font-size:13px; }
                  #tabelIklim th { background:#0ea5e9; color:white; padding:10px; text-align:center; }
                  #tabelIklim td { padding:8px 12px; border:1px solid #e0f2fe; text-align:center; }
                  #tabelIklim tr:nth-child(even) { background:#f0f9ff; }
                </style>
                ${tabelBersih}
              `;
            } else {
              document.getElementById('tabelIklim').innerHTML = '<p class="text-center text-red-400">Gagal memuat data.</p>';
            }
          })
          .catch(err => {
            console.error(err);
            document.getElementById('tabelIklim').innerHTML = '<p class="text-center text-red-400">Terjadi kesalahan.</p>';
          });
        </script>

</body>
</html>