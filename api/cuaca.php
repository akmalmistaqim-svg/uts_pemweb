<?php
require_once 'koneksi.php';
require_once 'session_handler.php';
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
  <title>Prediksi Cuaca - CuacaKu</title>
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Flatpickr CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

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

  <style>
    body {
      background: #f4f6fa;
      min-height: 100vh;
    }

    /* NAVBAR */
    .navbar {
      background: rgba(2, 132, 199, 0.88);
      backdrop-filter: blur(16px);
      border-bottom: 1px solid rgba(255,255,255,0.18);
      box-shadow: 0 2px 20px rgba(0,0,0,0.12);
    }
    .btn-logout {
      border: 2px solid rgba(255,255,255,0.75);
      color: white;
      border-radius: 999px;
      padding: 0.3rem 1.1rem;
      font-size: 0.875rem;
      transition: all 0.2s;
    }
    .btn-logout:hover { background: rgba(255,255,255,0.2); }

    /* HERO */
    .section-hero {
      position: relative;
      background: url('fotoLangit.jpg') center center / cover no-repeat;
    }
    .section-hero::before {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(160deg, rgba(14,165,233,0.82) 0%, rgba(56,189,248,0.75) 50%, rgba(186,230,253,0.65) 100%);
      z-index: 0;
    }
    .hero-circles {
      position: absolute;
      inset: 0;
      overflow: hidden;
      pointer-events: none;
      z-index: 1;
    }
    .hero-circles span {
      position: absolute;
      border-radius: 50%;
      background: rgba(255,255,255,0.08);
    }
    .hero-content {
      position: relative;
      z-index: 2;
    }

    /* FORM SECTION */
    .section-form {
      background: #ffffff;
    }

    /* HASIL SECTION */
    .section-hasil {
      background: #f4f6fa;
    }

    /* FOOTER */
    .footer {
      background: linear-gradient(160deg, #0c4a6e 0%, #075985 100%);
      border-top: 1px solid rgba(255,255,255,0.1);
    }

    /* Flatpickr */
    .flatpickr-input {
      display: block;
      width: 100%;
      border-radius: 0.375rem;
      background-color: white;
      padding: 0.375rem 0.75rem;
      color: #111827;
      border: 1px solid #d1d5db;
      outline: none;
      font-size: 0.875rem;
      cursor: pointer;
    }
    .flatpickr-input:focus {
      border-color: #4f46e5;
      box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.3);
    }
    .flatpickr-day.selected,
    .flatpickr-day.selected:hover {
      background: #4f46e5;
      border-color: #4f46e5;
    }
    .flatpickr-day:hover {
      background: #e0e7ff;
    }
    .flatpickr-months .flatpickr-month {
      background: #4f46e5;
    }
    .flatpickr-current-month .flatpickr-monthDropdown-months,
    .flatpickr-current-month input.cur-year {
      color: white;
    }
    .flatpickr-weekday {
      color: #4f46e5;
      font-weight: 600;
    }
    .flatpickr-prev-month svg,
    .flatpickr-next-month svg {
      fill: white;
    }
  </style>
</head>

<body class="text-gray-800 min-h-screen">

  <!-- navbar -->
  <nav class="navbar fixed w-full z-20 top-0">
    <div class="max-w-screen-xl flex items-center justify-between mx-auto px-6 py-4">
      <a href="dashboard.php" class="flex items-center gap-3">
        <img src="../1f324_3d.webp" class="h-7" alt="Logo Cuaca" />
        <span class="text-xl text-white font-semibold whitespace-nowrap">CuacaKu</span>
      </a>

      <!-- Desktop Menu -->
      <div class="hidden md:flex items-center gap-6">
        <span class="text-sm text-yellow-200 font-medium">👋 <?php echo htmlspecialchars($_SESSION['nama']); ?></span>
        <a href="logout.php" class="btn-logout">Logout</a>
      </div>

      <!-- Hamburger Button (mobile only) -->
      <button id="hamburger" class="md:hidden flex flex-col justify-center items-center gap-1.5 w-8 h-8">
        <span class="block w-6 h-0.5 bg-white transition-all duration-300" id="bar1"></span>
        <span class="block w-6 h-0.5 bg-white transition-all duration-300" id="bar2"></span>
        <span class="block w-6 h-0.5 bg-white transition-all duration-300" id="bar3"></span>
      </button>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="md:hidden hidden px-6 pb-4 border-t border-white/20" style="background: rgba(2,132,199,0.95)">
      <div class="flex items-center justify-between pt-4">
        <span class="text-sm text-yellow-200 font-medium">👋 <?php echo htmlspecialchars($_SESSION['nama']); ?></span>
        <a href="logout.php" class="btn-logout">Logout</a>
      </div>
    </div>
  </nav>

  <div class="h-16"></div>

  <!-- hero section -->
  <section class="section-hero text-white py-14 px-6 text-center">
    <div class="hero-circles">
      <span style="width:320px;height:320px;top:-80px;left:-60px;"></span>
      <span style="width:200px;height:200px;top:40px;right:80px;background:rgba(255,255,255,0.05);"></span>
      <span style="width:500px;height:500px;bottom:-200px;right:-100px;"></span>
      <span style="width:150px;height:150px;bottom:20px;left:120px;background:rgba(255,255,255,0.06);"></span>
    </div>
    <div class="hero-content">
      <span class="text-6xl">🔍</span>
      <h1 class="text-3xl sm:text-4xl font-bold mt-4 mb-2">Cek Prediksi Cuaca</h1>
      <p class="text-blue-100 text-base max-w-md mx-auto">Masukkan daerah dan tanggal untuk melihat hasil prediksi</p>
    </div>
  </section>

  <!-- form input -->
  <div class="section-form">
    <div class="max-w-xl mx-auto px-6 py-10">
      <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">

        <h2 class="text-lg font-semibold text-gray-900 mb-6">Isi Data di sini</h2>

        <div class="mb-5">
          <label class="block text-sm font-medium text-gray-700 mb-2">📍 Daerah / Kota</label>
          <select id="inputDaerah"
            class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-white text-gray-700
            focus:outline-none focus:ring-2 focus:ring-blue-400 appearance-none bg-white">
            <option value="">-- Pilih Daerah --</option>
          </select>
        </div>

        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 mb-2">📅 Tanggal Prediksi</label>
          <input type="hidden" id="inputTanggal" />
          <input id="inputTanggal_display"
            type="text"
            placeholder="DD/MM/YYYY"
            readonly
            class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl
            text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 cursor-pointer bg-white" />
        </div>

        <button onclick="cekPrediksi()"
          class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm
          font-semibold text-white hover:bg-indigo-500 transition-colors mt-1">
          Hasilnya
        </button>

        <p id="pesanError" class="text-red-500 text-sm text-center mt-3 hidden">
          ⚠️ Mohon isi daerah dan tanggal terlebih dahulu.
        </p>

      </div>
    </div>
  </div>

  <!-- hasil prediksi -->
  <div id="hasilPrediksi" class="hasil section-hasil">
    <div class="max-w-xl mx-auto px-6 pb-6 pt-6">
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
          <div class="rounded-2xl p-4 border border-slate-200" style="background:#f4f6fa;">
            <p class="text-xs text-gray-500 mb-1">💧 Kelembaban</p>
            <p id="hasilLembab" class="text-xl font-bold text-gray-900">72%</p>
          </div>
          <div class="rounded-2xl p-4 border border-slate-200" style="background:#f4f6fa;">
            <p class="text-xs text-gray-500 mb-1">💨 Kec. Angin</p>
            <p id="hasilAngin" class="text-xl font-bold text-gray-900">14 km/j</p>
          </div>
          <div class="rounded-2xl p-4 border border-slate-200" style="background:#f4f6fa;">
            <p class="text-xs text-gray-500 mb-1">🌧️ Peluang Hujan</p>
            <p id="hasilHujan" class="text-xl font-bold text-gray-900">20%</p>
          </div>
          <div class="rounded-2xl p-4 border border-slate-200" style="background:#f4f6fa;">
            <p class="text-xs text-gray-500 mb-1">🌡️ Tekanan Udara</p>
            <p id="hasilUV" class="text-xl font-bold text-gray-900">-</p>
          </div>
        </div>

                <!-- REKOMENDASI PETANI -->
        <div class="rounded-2xl border border-slate-200 mb-4 overflow-hidden" style="background:#f4f6fa;">

          <!-- Tab -->
          <div class="flex border-b border-slate-200">
            <button id="tabMinggu" onclick="setTabRek('minggu')"
              class="flex-1 py-3 text-sm font-semibold text-sky-600 border-b-2 border-sky-500 bg-white transition-all">
              7 Hari ke Depan
            </button>
            <button id="tabBulan" onclick="setTabRek('bulan')"
              class="flex-1 py-3 text-sm font-semibold text-gray-400 border-b-2 border-transparent transition-all">
              Bulan Depan
            </button>
          </div>

          <!-- Isi Tab Minggu -->
          <div id="rekMinggu" class="p-5">

            <!-- Banner kondisi -->
            <div id="rekBanner" class="rounded-xl p-4 mb-4 flex items-center gap-3" style="background:#e8f4fd;">
              <span id="rekEmojiBanner" class="text-3xl">⛅</span>
              <div>
                <p id="rekJudulBanner" class="text-sm font-semibold text-blue-800">Kondisi minggu ini</p>
                <p id="rekDetailBanner" class="text-xs text-blue-600 mt-0.5">Memuat data cuaca...</p>
              </div>
            </div>

            <!-- Kartu-kartu -->
            <div id="rekKartuMinggu" class="space-y-3"></div>

            <!-- Aktivitas -->
            <p class="text-xs font-bold text-gray-400 tracking-widest mt-5 mb-3">AKTIVITAS PETANI MINGGU INI</p>
            <div id="rekAktivitasMinggu" class="rounded-xl border border-slate-200 bg-white divide-y divide-slate-100"></div>

          </div>

          <!-- Isi Tab Bulan -->
          <div id="rekBulan" class="p-5 hidden">

            <!-- Banner bulan -->
            <div class="rounded-xl p-4 mb-4 flex items-center gap-3" style="background:#e8f4fd;">
              <span class="text-3xl">🌤️</span>
              <div>
                <p id="rekJudulBulan" class="text-sm font-semibold text-blue-800">Prediksi bulan depan</p>
                <p id="rekDetailBulan" class="text-xs text-blue-600 mt-0.5">Berdasarkan tren cuaca saat ini</p>
              </div>
            </div>

            <!-- Kartu-kartu bulan -->
            <div id="rekKartuBulan" class="space-y-3"></div>

            <!-- Aktivitas bulan -->
            <p class="text-xs font-bold text-gray-400 tracking-widest mt-5 mb-3">AKTIVITAS PETANI BULAN DEPAN</p>
            <div id="rekAktivitasBulan" class="rounded-xl border border-slate-200 bg-white divide-y divide-slate-100"></div>

          </div>
        </div>

        <!-- tombol kabar sekitar -->
        <div class="rounded-2xl p-5 border border-slate-200 text-center" style="background:#f4f6fa;">
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
  </div>

  <div class="pb-4"></div>

  <footer class="footer py-8 px-6 text-center">
    <p class="text-sm text-white/90">© 2026 CuacaKu. Sistem Prediksi Cuaca Jawa Timur.</p>
  </footer>

  <script src="../javascript/cuaca.js"></script>

  <!-- Flatpickr JS -->
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script>
    // Hamburger toggle
    const hamburger = document.getElementById('hamburger');
    const mobileMenu = document.getElementById('mobileMenu');
    const bar1 = document.getElementById('bar1');
    const bar2 = document.getElementById('bar2');
    const bar3 = document.getElementById('bar3');

    hamburger.addEventListener('click', () => {
      mobileMenu.classList.toggle('hidden');
      bar1.classList.toggle('rotate-45');
      bar1.classList.toggle('translate-y-2');
      bar2.classList.toggle('opacity-0');
      bar3.classList.toggle('-rotate-45');
      bar3.classList.toggle('-translate-y-2');
    });

    // Flatpickr
    flatpickr("#inputTanggal_display", {
      dateFormat: "d/m/Y",
      disableMobile: true,
      onChange: function(selectedDates, dateStr, instance) {
        if (selectedDates.length > 0) {
          const d = selectedDates[0];
          const yyyy = d.getFullYear();
          const mm = String(d.getMonth() + 1).padStart(2, '0');
          const dd = String(d.getDate()).padStart(2, '0');
          document.getElementById('inputTanggal').value = `${yyyy}-${mm}-${dd}`;
        }
      }
    });

    // ambil data kota Jawa Timur dari API BPS
    fetch('ambilKota.php')
      .then(res => res.json())
      .then(data => {
        const select = document.getElementById('inputDaerah');
        data.forEach(item => {
          if (item.domain_name === 'Jawa Timur') return;
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