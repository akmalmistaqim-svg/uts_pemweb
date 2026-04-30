<?php
/**@var mysqli $koneksi */
require_once 'koneksi.php';
require_once 'session_handler.php';
if (!isset($_SESSION['id_pengguna'])) {
    header("Location: login.php");
    exit();
}

$kota = $_SESSION['kota_dicek'] ?? '';
$komentar = mysqli_query($koneksi, "SELECT * FROM komentar ORDER BY dibuat_pada DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Kabar Sekitar - CuacaKu</title>
</head>

<body class="bg-sky-100 text-gray-800 min-h-screen">

  <!-- navbar -->
  <nav class="bg-sky-200 fixed w-full z-20 top-0 border-b border-sky-300">
    <div class="max-w-screen-xl flex items-center justify-between mx-auto px-6 py-4">
      <a href="dashboard.php" class="flex items-center gap-3">
        <img src="../1f324_3d.webp" class="h-7" alt="Logo Cuaca" />
        <span class="text-xl text-gray-900 font-semibold">CuacaKu</span>
      </a>

      <!-- Desktop Menu -->
      <div class="hidden md:flex items-center gap-4">
        <span class="text-sm text-gray-600">👋 <?php echo htmlspecialchars($_SESSION['nama']); ?></span>
        <a href="logout.php"
          class="text-sm bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl transition-colors">
          Logout
        </a>
      </div>

      <!-- Hamburger Button (mobile only) -->
      <button id="hamburger" class="md:hidden flex flex-col justify-center items-center gap-1.5 w-8 h-8">
        <span class="block w-6 h-0.5 bg-gray-700 transition-all duration-300" id="bar1"></span>
        <span class="block w-6 h-0.5 bg-gray-700 transition-all duration-300" id="bar2"></span>
        <span class="block w-6 h-0.5 bg-gray-700 transition-all duration-300" id="bar3"></span>
      </button>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="md:hidden hidden px-6 pb-4 border-t border-sky-300 bg-sky-200">
      <div class="flex items-center justify-between pt-4">
        <span class="text-sm text-gray-600">👋 <?php echo htmlspecialchars($_SESSION['nama']); ?></span>
        <a href="logout.php"
          class="text-sm bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl transition-colors">
          Logout
        </a>
      </div>
    </div>
  </nav>

  <div class="h-16"></div>

  <div class="max-w-2xl mx-auto px-6 py-10">

    <!-- header -->
    <div class="mb-8 text-center">
      <h1 class="text-2xl font-bold text-gray-900">💬 Kabar Sekitar</h1>
      <p class="text-gray-500 text-sm mt-1">Bagikan kondisi cuaca aktual di daerahmu</p>
    </div>

    <?php if (isset($_GET['sukses'])): ?>
      <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-xl mb-6 text-sm">
        ✅ <?php echo htmlspecialchars($_GET['sukses']); ?>
      </div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
      <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm">
        ⚠️ <?php echo htmlspecialchars($_GET['error']); ?>
      </div>
    <?php endif; ?>

    <!-- form komentar -->
    <div class="bg-white rounded-2xl border border-sky-200 shadow-sm p-6 mb-8">
      <h2 class="font-semibold text-gray-900 mb-4">Tulis Kabar</h2>
      <form action="prosesKomentar.php" method="POST" class="space-y-4">

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1.5">📍 Kota</label>
          <input
            type="text"
            value="<?php echo htmlspecialchars($kota); ?>"
            readonly
            class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 text-gray-500"
          />
          <input type="hidden" name="kota" value="<?php echo htmlspecialchars($kota); ?>" />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1.5">⭐ Rating Cuaca</label>
          <select name="rating" required
            class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-white text-gray-700
            focus:outline-none focus:ring-2 focus:ring-blue-400">
            <option value="">-- Pilih Rating --</option>
            <option value="5">⭐⭐⭐⭐⭐ — Sangat Baik</option>
            <option value="4">⭐⭐⭐⭐ — Baik</option>
            <option value="3">⭐⭐⭐ — Cukup</option>
            <option value="2">⭐⭐ — Buruk</option>
            <option value="1">⭐ — Sangat Buruk</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1.5">📝 Komentar</label>
          <textarea
            name="isi_komentar"
            rows="3"
            required
            class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl
            focus:outline-none focus:ring-2 focus:ring-blue-400 resize-none"
          ></textarea>
        </div>

        <button type="submit"
          class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2.5 rounded-xl transition-colors">
          Kirim Kabar
        </button>

      </form>
    </div>

    <!-- daftar komentar -->
    <div class="space-y-4">
      <h2 class="font-semibold text-gray-900">Kabar dari Pengguna Lain</h2>

      <?php if (mysqli_num_rows($komentar) === 0): ?>
        <div class="bg-white rounded-2xl border border-sky-200 p-6 text-center text-gray-400 text-sm">
          Belum ada kabar. Jadilah yang pertama!
        </div>
      <?php else: ?>
        <?php while ($row = mysqli_fetch_assoc($komentar)): ?>
        <div class="bg-white rounded-2xl border border-sky-200 shadow-sm p-5">
          <div class="flex items-center justify-between mb-2">
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                <?php echo strtoupper(substr($row['nama'], 0, 1)); ?>
              </div>
              <div>
                <p class="font-semibold text-gray-900 text-sm"><?php echo htmlspecialchars($row['nama']); ?></p>
                <p class="text-xs text-gray-400">📍 <?php echo htmlspecialchars($row['kota']); ?> · <?php echo $row['dibuat_pada'] ? date('d M Y, H:i', strtotime($row['dibuat_pada']) + 7 * 3600) : '-'; ?></p>
              </div>
            </div>
            <div class="text-yellow-400 text-sm">
              <?php echo str_repeat('⭐', (int)$row['rating']); ?>
            </div>
          </div>
          <p class="text-sm text-gray-600 leading-relaxed pl-12"><?php echo htmlspecialchars($row['isi_komentar']); ?></p>
        </div>
        <?php endwhile; ?>
      <?php endif; ?>
    </div>

    <a href="dashboard.php"
      class="inline-flex items-center gap-2 text-sm font-medium bg-white border border-sky-200 text-sky-600 hover:bg-sky-50 hover:border-sky-300 px-4 py-2 rounded-xl transition-colors shadow-sm mt-6">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M19 12H5M12 5l-7 7 7 7"/>
      </svg>
      Kembali ke Dashboard
    </a>

  </div>

  <footer class="bg-sky-400 border-t border-sky-500 py-8 px-6 text-center mt-10">
    <p class="text-sm text-sky-100">© 2026 CuacaKu. Sistem Prediksi Cuaca Jawa Timur.</p>
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