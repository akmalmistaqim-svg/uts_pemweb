<?php
session_start();
if (!isset($_SESSION['nama'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Prediksi Cuaca - CuacaKu</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <style type="text/tailwindcss">
    html { scroll-behavior: smooth; }
    .hasil { display: none; }
    .hasil.tampil { display: block; }
    @keyframes munculAtas {
      from { opacity: 0; transform: translateY(24px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .animasi-muncul { animation: munculAtas 0.5s ease forwards; }
  </style>
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
        <span class="text-sm text-gray-600">👋 <?php echo htmlspecialchars($_SESSION['nama']); ?></span>
        <a href="logout.php"
          class="text-sm bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl transition-colors">
          Logout
        </a>
      </div>
    </div>
  </nav>

  <div class="h-16"></div>

  <div class="bg-gradient-to-br from-blue-500 to-sky-400 text-white py-14 px-6 text-center">
    <span class="text-6xl">🔍</span>
    <h1 class="text-3xl sm:text-4xl font-bold mt-4 mb-2">Cek Prediksi Cuaca</h1>
    <p class="text-blue-100 text-base max-w-md mx-auto">Masukkan daerah dan tanggal untuk melihat hasil prediksi</p>
  </div>

  <!-- form input -->
  <div class="max-w-xl mx-auto px-6 py-10">
    <div class="bg-white rounded-2xl shadow-sm border border-sky-200 p-8">

      <h2 class="text-lg font-semibold text-gray-900 mb-6">Isi Data di sini</h2>

      <div class="mb-5">
        <label class="block text-sm font-medium text-gray-700 mb-2">📍 Daerah / Kota</label>
        <select id="inputDaerah"
  class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-white text-gray-700
  focus:outline-none focus:ring-2 focus:ring-blue-400">
  <option value="">-- Pilih Daerah --</option>
</select>

      </div>

      <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">📅 Tanggal Prediksi</label>
        <input type="date" id="inputTanggal"
          class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl
          text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400" />
      </div>

      <button onclick="cekPrediksi()"
        class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 rounded-xl
        transition-colors flex items-center justify-center gap-2">
        Hasilnya
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M5 12h14M12 5l7 7-7 7"/>
        </svg>
      </button>

      <p id="pesanError" class="text-red-500 text-sm text-center mt-3 hidden">
        ⚠️ Mohon isi daerah dan tanggal terlebih dahulu.
      </p>

    </div>
  </div>

  <!-- hasil prediksi -->
  <div id="hasilPrediksi" class="hasil max-w-xl mx-auto px-6 pb-6">
    <div class="animasi-muncul">

      <div id="cardUtama" class="bg-gradient-to-br from-blue-500 to-sky-400 text-white rounded-2xl p-6 mb-4 shadow-sm">
        <div class="flex items-center justify-between mb-4">
          <div>
            <p class="text-blue-100 text-sm" id="hasilLokasi">📍 Magetan</p>
            <p class="text-blue-100 text-xs mt-0.5" id="hasilTanggal">Senin, 15 Maret 2025</p>
          </div>
          <span id="hasilEmoji" class="text-5xl">⛅</span>
        </div>
        <div class="flex items-end gap-3 mb-3">
          <span id="hasilSuhu" class="text-6xl font-bold">28°</span>
          <div class="mb-2">
            <p id="hasilKondisi" class="text-lg font-semibold">Berawan Sebagian</p>
            <p id="hasilRasa" class="text-blue-200 text-sm">Terasa seperti 30°C</p>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-2 gap-3 mb-4">
        <div class="bg-white rounded-2xl p-4 border border-sky-200">
          <p class="text-xs text-gray-500 mb-1">💧 Kelembaban</p>
          <p id="hasilLembab" class="text-xl font-bold text-gray-900">72%</p>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-sky-200">
          <p class="text-xs text-gray-500 mb-1">💨 Kec. Angin</p>
          <p id="hasilAngin" class="text-xl font-bold text-gray-900">14 km/j</p>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-sky-200">
          <p class="text-xs text-gray-500 mb-1">🌧️ Peluang Hujan</p>
          <p id="hasilHujan" class="text-xl font-bold text-gray-900">20%</p>
        </div>
       <div class="bg-white rounded-2xl p-4 border border-sky-200">
         <p class="text-xs text-gray-500 mb-1">🌡️ Tekanan Udara</p>
         <p id="hasilUV" class="text-xl font-bold text-gray-900">-</p>
        </div>
      </div>

      <div class="bg-white rounded-2xl p-5 border border-sky-200 mb-4">
        <h3 class="font-semibold text-gray-900 mb-3">💡 Rekomendasi</h3>
        <p id="hasilRekomendasi" class="text-sm text-gray-600 leading-relaxed"></p>
      </div>

      <!-- tombol kabar sekitar -->
      <div class="bg-white rounded-2xl p-5 border border-sky-200 text-center">
        <p class="text-sm text-gray-500 mb-3">Bagikan kondisi cuaca aktual di daerahmu</p>
        <a href="kabar_sekitar.php"
          class="inline-flex items-center gap-2 bg-sky-500 hover:bg-sky-600 text-white
          font-semibold px-6 py-2.5 rounded-xl transition-colors">
          💬 Kabar Sekitar
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M5 12h14M12 5l7 7-7 7"/>
          </svg>
        </a>
      </div>

    </div>
  </div>

  <div class="pb-16"></div>

  <footer class="bg-sky-400 border-t border-sky-500 py-8 px-6 text-center">
    <p class="text-sm text-sky-100">© 2026 CuacaKu. Sistem Prediksi Cuaca Jawa Timur.</p>
  </footer>

  <script src="../javascript/cuaca.js"></script>


  <script>
    // ambil data kota Jawa Timur dari API BPS
    fetch('ambilKota.php')
      .then(res => res.json())
      .then(data => {
        const select = document.getElementById('inputDaerah');
        data.forEach(item => {

          if (item.domain_name === 'Jawa Timur') return;
          // bersihkan nama kota (hapus "Kabupaten" / "Kota")
          var nama = item.domain_name
            .replace('Kabupaten ', '')
            .replace('Kota ', '');
          const option = document.createElement('option');
          option.value = nama;
          option.textContent = item.domain_name;
          select.appendChild(option);
        });
      });
</script>
</body>
</html>