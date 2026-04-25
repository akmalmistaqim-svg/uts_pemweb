<?php
require_once 'koneksi.php';
require_once 'session_handler.php';
if (!isset($_SESSION['id_pengguna']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once 'koneksi.php';

// ambil semua data pengguna
$query    = "SELECT * FROM pengguna ORDER BY id ASC";
$pengguna = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Admin - CuacaKu</title>
</head>

<body class="bg-sky-100 text-gray-800 min-h-screen">

  <!-- navbar -->
  <nav class="bg-sky-200 fixed w-full z-20 top-0 border-b border-sky-300">
    <div class="max-w-screen-xl flex items-center justify-between mx-auto px-6 py-4">
      <a href="admin.php" class="flex items-center gap-3">
        <img src="../1f324_3d.webp" class="h-7" alt="Logo Cuaca" />
        <span class="text-xl text-gray-900 font-semibold">CuacaKu — Admin</span>
      </a>
      <div class="flex items-center gap-4">
        <span class="text-sm text-gray-600">👋 <?php echo htmlspecialchars($_SESSION['nama']); ?></span>
        <a href="logout.php"
          class="text-sm bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl transition-colors">
          Logout
        </a>
      </div>
    </div>
  </nav>

  <div class="h-16"></div>

  <div class="max-w-5xl mx-auto px-6 py-10">

    <!-- header -->
    <div class="mb-8">
      <h1 class="text-2xl font-bold text-gray-900">Manajemen Pengguna</h1>
      <p class="text-gray-500 text-sm mt-1">Kelola data pengguna yang terdaftar di sistem</p>
    </div>

    <!-- tampilkan pesan sukses/error -->
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

    <!-- tabel pengguna -->
    <div class="bg-white rounded-2xl border border-sky-200 shadow-sm overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-sky-50 border-b border-sky-200">
          <tr>
            <th class="px-6 py-3 text-left text-gray-600 font-semibold">No</th>
            <th class="px-6 py-3 text-left text-gray-600 font-semibold">Nama</th>
            <th class="px-6 py-3 text-left text-gray-600 font-semibold">Email</th>
            <th class="px-6 py-3 text-left text-gray-600 font-semibold">Tanggal Lahir</th>
            <th class="px-6 py-3 text-left text-gray-600 font-semibold">Role</th>
            <th class="px-6 py-3 text-left text-gray-600 font-semibold">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-sky-100">
          <?php $no = 1; while ($row = mysqli_fetch_assoc($pengguna)): ?>
          <tr class="hover:bg-sky-50 transition-colors">
            <td class="px-6 py-4 text-gray-500"><?php echo $no++; ?></td>
            <td class="px-6 py-4 font-medium text-gray-900"><?php echo htmlspecialchars($row['nama']); ?></td>
            <td class="px-6 py-4 text-gray-600"><?php echo htmlspecialchars($row['email']); ?></td>
            <td class="px-6 py-4 text-gray-600"><?php echo $row['tanggal_lahir']; ?></td>
            <td class="px-6 py-4">
              <?php if ($row['role'] === 'admin'): ?>
                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">Admin</span>
              <?php else: ?>
                <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-semibold">Pengguna</span>
              <?php endif; ?>
            </td>
            <td class="px-6 py-4">
              <div class="flex items-center gap-2">
                <?php if ($row['role'] === 'pengguna'): ?>
                  <!-- tombol jadikan admin -->
                  <a href="prosesAdmin.php?aksi=jadikan_admin&id=<?php echo $row['id']; ?>"
                    class="text-xs bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-lg transition-colors"
                    onclick="return confirm('Jadikan <?php echo htmlspecialchars($row['nama']); ?> sebagai admin?')">
                    Jadikan Admin
                  </a>
                <?php else: ?>
                  <!-- tombol jadikan pengguna -->
                  <a href="prosesAdmin.php?aksi=jadikan_pengguna&id=<?php echo $row['id']; ?>"
                    class="text-xs bg-gray-400 hover:bg-gray-500 text-white px-3 py-1.5 rounded-lg transition-colors"
                    onclick="return confirm('Jadikan <?php echo htmlspecialchars($row['nama']); ?> sebagai pengguna biasa?')">
                    Jadikan Pengguna
                  </a>
                <?php endif; ?>
                <!-- tombol hapus (tidak bisa hapus diri sendiri) -->
                <?php if ($row['email'] !== $_SESSION['email']): ?>
                  <a href="prosesAdmin.php?aksi=hapus&id=<?php echo $row['id']; ?>"
                    class="text-xs bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg transition-colors"
                    onclick="return confirm('Hapus pengguna <?php echo htmlspecialchars($row['nama']); ?>?')">
                    Hapus
                  </a>
                <?php endif; ?>
              </div>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

  </div>

</body>
</html>
<?php mysqli_close($koneksi); ?>