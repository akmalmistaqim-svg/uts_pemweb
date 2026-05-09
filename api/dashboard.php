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
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Dashboard - CuacaKu</title>
  <style>
    body {
      background: linear-gradient(160deg, #0ea5e9 0%, #38bdf8 50%, #bae6fd 100%);
      min-height: 100vh;
    }
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
      background: url('../fotoLangit.jpg') center center / cover no-repeat;
    }
    .section-hero::before {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(160deg, rgba(14,165,233,0.55) 0%, rgba(56,189,248,0.45) 50%, rgba(186,230,253,0.35) 100%);
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

    /* SECTION TENTANG */
    .section-tentang {
      background: #ffffff;
    }
    .card-akurat {
      background: #f0f7ff;
      border: 1px solid #bfdbfe;
      border-radius: 1.25rem;
    }
    .checklist-dot {
      background: #2563eb;
    }

    /* SECTION DATA IKLIM */
    .section-iklim {
      background: #f4f6fa;
    }
    #tabelIklim table { width:100%; border-collapse:collapse; font-size:13px; }
    #tabelIklim th {
      background: #1d4ed8;
      color: white;
      padding: 10px;
      text-align: center;
      font-weight: 600;
    }
    #tabelIklim td {
      padding: 8px 12px;
      border: 1px solid #e2e8f0;
      text-align: center;
      color: #1e293b;
    }
    #tabelIklim tr:nth-child(even) { background: #f8fafc; }
    #tabelIklim tr:hover { background: #eff6ff; transition: background 0.2s; }

    .footer {
      background: linear-gradient(160deg, #0c4a6e 0%, #075985 100%);
      border-top: 1px solid rgba(255,255,255,0.1);
    }
  </style>
</head>

<body class="text-gray-800 min-h-screen">

  <!-- navbar -->
  <nav class="navbar fixed w-full z-20 top-0">
    <div class="max-w-screen-xl flex items-center justify-between mx-auto px-6 py-4">
      <a href="dashboard.php" class="flex items-center gap-3">
        <img src="../1f324_3d.webp" class="h-7" alt="Logo Cuaca" />
        <span class="text-xl text-white font-semibold whitespace-nowrap">Website Prediksi Cuaca</span>
      </a>

      <div class="hidden md:flex items-center gap-6">
        <ul class="flex flex-row font-medium gap-8">
          <li><a href="dashboard.php" class="text-white hover:text-yellow-200 transition-colors">Home</a></li>
          <li><a href="dashboard.php#grafik" class="text-white hover:text-yellow-200 transition-colors">Grafik Cuaca</a></li>
          <li><a href="dashboard.php#tentang" class="text-white hover:text-yellow-200 transition-colors">Tentang</a></li>
          <li><a href="dashboard.php#dataiklim" class="text-white hover:text-yellow-200 transition-colors">Data Iklim</a></li>
        </ul>
        <span class="text-sm text-yellow-200 font-medium">👋 <?php echo htmlspecialchars($_SESSION['nama']); ?></span>
        <a href="logout.php" class="btn-logout">Logout</a>
      </div>

      <button id="hamburger" class="md:hidden flex flex-col justify-center items-center gap-1.5 w-8 h-8">
        <span class="block w-6 h-0.5 bg-white transition-all duration-300" id="bar1"></span>
        <span class="block w-6 h-0.5 bg-white transition-all duration-300" id="bar2"></span>
        <span class="block w-6 h-0.5 bg-white transition-all duration-300" id="bar3"></span>
      </button>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="md:hidden hidden px-6 pb-4 border-t border-white/20" style="background: rgba(2,132,199,0.95)">
      <ul class="flex flex-col font-medium gap-3 pt-4">
        <li><a href="dashboard.php" class="block text-white hover:text-yellow-200 transition-colors">Home</a></li>
        <li><a href="dashboard.php#grafik" class="block text-white hover:text-yellow-200 transition-colors">Grafik Cuaca</a></li>
        <li><a href="dashboard.php#tentang" class="block text-white hover:text-yellow-200 transition-colors">Tentang</a></li>
        <li><a href="dashboard.php#dataiklim" class="block text-white hover:text-yellow-200 transition-colors">Data Iklim</a></li>
      </ul>
      <div class="border-t border-white/20 mt-4 pt-4 flex items-center justify-between">
        <span class="text-sm text-yellow-200 font-medium">👋 <?php echo htmlspecialchars($_SESSION['nama']); ?></span>
        <a href="logout.php" class="btn-logout">Logout</a>
      </div>
    </div>
  </nav>

  <div class="h-16"></div>

  <!-- hero section -->
  <section id="home" class="section-hero text-white py-24 px-6 text-center">

    <div class="hero-circles">
      <span style="width:320px;height:320px;top:-80px;left:-60px;"></span>
      <span style="width:200px;height:200px;top:40px;right:80px;background:rgba(255,255,255,0.05);"></span>
      <span style="width:500px;height:500px;bottom:-200px;right:-100px;"></span>
      <span style="width:150px;height:150px;bottom:20px;left:120px;background:rgba(255,255,255,0.06);"></span>
    </div>

    <div class="hero-content">
      <span class="text-7xl drop-shadow-md">⛅</span>
      <h1 class="text-4xl sm:text-5xl font-bold mt-6 mb-4 drop-shadow-sm">Sistem Prediksi Cuaca</h1>
      <p class="text-sky-100 text-lg mb-10 max-w-xl mx-auto font-medium">
        Informasi cuaca akurat dan cepat untuk wilayah Jawa Timur
      </p>
      <a href="cuaca.php"
         class="bg-white text-blue-600 font-semibold px-8 py-3 rounded-xl
          hover:bg-blue-50 inline-flex items-center justify-center gap-2 shadow-md">
        Cek Prediksi Cuaca
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M5 12h14M12 5l7 7-7 7"/>
        </svg>
      </a>
    </div>

  </section>

  <?php // include 'grafik.php'; ?>

  <!-- section tentang -->
  <section id="tentang" class="section-tentang py-16 px-6">
    <div class="max-w-4xl mx-auto">
      <div class="text-center mb-12">
        <h2 class="text-3xl font-bold text-slate-800 mb-3">Tentang Sistem Prediksi Cuaca</h2>
        <p class="text-slate-500 max-w-xl mx-auto">
          Kami hadir untuk memberikan informasi cuaca yang akurat dan mudah dipahami oleh semua kalangan masyarakat.
        </p>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center mb-10">
        <div>
          <h3 class="text-xl font-bold text-slate-800 mb-4">Apa itu Sistem Prediksi Cuaca?</h3>
          <p class="text-slate-600 text-sm leading-relaxed mb-4">
            Sistem ini adalah website prediksi cuaca berbasis kecerdasan buatan yang dirancang untuk memberikan informasi cuaca yang akurat.
          </p>
          <p class="text-slate-600 text-sm leading-relaxed mb-6">
            Kami hadir untuk membantu warga Jawa Timur lebih siap menghadapi perubahan cuaca setiap harinya.
          </p>
          <div class="space-y-3">
            <div class="flex items-center gap-3">
              <div class="checklist-dot w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
              </div>
              <p class="text-sm text-slate-700">Data diperbarui setiap 30 menit</p>
            </div>
            <div class="flex items-center gap-3">
              <div class="checklist-dot w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
              </div>
              <p class="text-sm text-slate-700">Mencakup seluruh wilayah Jawa Timur</p>
            </div>
            <div class="flex items-center gap-3">
              <div class="checklist-dot w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
              </div>
              <p class="text-sm text-slate-700">Dijamin akurat</p>
            </div>
          </div>
        </div>
        <div class="card-akurat p-10 text-center">
          <span class="text-8xl">🌤️</span>
          <p class="text-blue-700 font-semibold mt-6 text-lg">Akurat, Cepat, dan Terpercaya</p>
          <p class="text-blue-400 text-sm mt-2">Sistem prediksi cuaca terbaik untuk wilayah Jawa Timur</p>
        </div>
      </div>
    </div>
  </section>

  <!-- section data iklim BPS -->
  <section id="dataiklim" class="section-iklim py-16 px-6">
    <div class="max-w-4xl mx-auto">
      <div class="text-center mb-10">
        <h2 class="text-3xl font-bold text-slate-800 mb-3">Data Iklim Provinsi Jawa Timur</h2>
      </div>
      <div id="tabelIklim" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 overflow-x-auto">
        <p class="text-center text-gray-400 text-sm">Memuat data...</p>
      </div>
    </div>
  </section>

  <footer class="footer py-8 px-6 text-center">
    <p class="text-sm text-white/90">© 2026 CuacaKu. Sistem Prediksi Cuaca Jawa Timur.</p>
  </footer>

  <script>
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

    mobileMenu.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        mobileMenu.classList.add('hidden');
        bar1.classList.remove('rotate-45', 'translate-y-2');
        bar2.classList.remove('opacity-0');
        bar3.classList.remove('-rotate-45', '-translate-y-2');
      });
    });

    fetch('Iklim.php')
      .then(res => res.text())
      .then(text => {
        const data = JSON.parse(text);
        if (data.status === 'OK') {
          const tabelBersih = data.tabel
            .replace(/\\r\\n/g, '')
            .replace(/\\t/g, '')
            .replace(/\\"/g, '"');
          document.getElementById('tabelIklim').innerHTML = `${tabelBersih}`;
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