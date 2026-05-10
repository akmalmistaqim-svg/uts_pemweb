<?php
/**@var mysqli $koneksi */
require_once 'koneksi.php';
require_once 'session_handler.php';
if (!isset($_SESSION['id_pengguna'])) {
    header("Location: login.php");
    exit();
}

$riwayat = mysqli_query($koneksi, "SELECT * FROM riwayat_prediksi ORDER BY dibuat_pada DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Riwayat Prediksi - CuacaKu</title>
  <style>
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
    .footer {
      background: linear-gradient(160deg, #0c4a6e 0%, #075985 100%);
      border-top: 1px solid rgba(255,255,255,0.1);
    }
  </style>
</head>

<body class="bg-sky-100 text-gray-800 min-h-screen">

  <!-- navbar -->
  <nav class="navbar fixed w-full z-20 top-0">
    <div class="max-w-screen-xl flex items-center justify-between mx-auto px-6 py-4">
      <a href="dashboard.php" class="flex items-center gap-3">
        <img src="../1f324_3d.webp" class="h-7" alt="Logo Cuaca" />
        <span class="text-xl text-white font-semibold">CuacaKu</span>
      </a>

      <!-- Desktop Menu -->
      <div class="hidden md:flex items-center gap-4">
        <span class="text-sm text-yellow-200 font-medium">👋 <?php echo htmlspecialchars($_SESSION['nama']); ?></span>
        <a href="logout.php" class="btn-logout">Logout</a>
      </div>

      <!-- Hamburger -->
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

  <div class="max-w-2xl mx-auto px-6 py-10">

    <!-- header -->
    <div class="mb-8 text-center">
      <h1 class="text-2xl font-bold text-gray-900">🕐 Riwayat Prediksi</h1>
      <p class="text-gray-500 text-sm mt-1">Daftar prediksi cuaca yang telah dilakukan pengguna</p>
    </div>

    <!-- daftar riwayat -->
    <div class="space-y-4">

      <?php if (mysqli_num_rows($riwayat) === 0): ?>
        <div class="bg-white rounded-2xl border border-sky-200 p-6 text-center text-gray-400 text-sm">
          Belum ada riwayat prediksi.
        </div>
      <?php else: ?>
        <?php while ($row = mysqli_fetch_assoc($riwayat)): ?>
        <div class="bg-white rounded-2xl border border-sky-200 shadow-sm p-5">
          <div class="flex items-center justify-between mb-2">
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                <?php echo strtoupper(substr($row['nama'], 0, 1)); ?>
              </div>
              <div>
                <p class="font-semibold text-gray-900 text-sm"><?php echo htmlspecialchars($row['nama']); ?></p>
                <p class="text-xs text-gray-400">
                  📍 <?php echo htmlspecialchars($row['kota']); ?>
                  · 📅 <?php echo $row['tanggal_prediksi'] ? date('d M Y', strtotime($row['tanggal_prediksi'])) : '-'; ?>
                  · <?php echo $row['dibuat_pada'] ? date('d M Y, H:i', strtotime($row['dibuat_pada']) + 7 * 3600) : '-'; ?>
                </p>
              </div>
            </div>
            <div class="text-right">
              <p class="text-lg font-bold text-gray-900"><?php echo $row['suhu']; ?>°C</p>
              <p class="text-xs text-gray-400"><?php echo htmlspecialchars($row['kondisi']); ?></p>
            </div>
          </div>
        </div>
        <?php endwhile; ?>
      <?php endif; ?>

    </div>

    <!-- 2 tombol navigasi -->
    <div class="flex gap-3 mt-8">
      <a href="dashboard.php"
        class="flex-1 inline-flex items-center justify-center gap-2 text-sm font-medium
        bg-white border border-sky-200 text-sky-600 hover:bg-sky-50 hover:border-sky-300
        px-4 py-2.5 rounded-xl transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M19 12H5M12 5l-7 7 7 7"/>
        </svg>
        Dashboard
      </a>
      <a href="kabar_sekitar.php"
        class="flex-1 inline-flex items-center justify-center gap-2 text-sm font-medium
        bg-sky-500 hover:bg-sky-600 text-white
        px-4 py-2.5 rounded-xl transition-colors shadow-sm">
        💬 Kabar Sekitar
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M5 12h14M12 5l7 7-7 7"/>
        </svg>
      </a>
    </div>

  </div>

  <footer class="footer py-8 px-6 text-center mt-10">
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
  </script>

</body>
</html>