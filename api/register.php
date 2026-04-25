<?php
session_start();
// kalau sudah login, langsung ke dashboard
if (isset($_SESSION['nama'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Daftar - Sistem Prediksi Cuaca</title>
</head>

<body class="bg-sky-600">

  <div class="flex min-h-screen flex-col justify-center px-6 py-12 lg:px-8">

    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
      <img src="../1f324_3d.webp" alt="Logo Cuaca" class="mx-auto h-20 w-auto" />
      <h2 class="mt-4 text-center text-2xl font-bold tracking-tight text-white">
        Buat akun baru
      </h2>
    </div>

    <!-- box registrasi -->
    <div class="mt-3 sm:mx-auto sm:w-full sm:max-w-sm bg-white px-8 py-10 rounded-2xl shadow-sm">

      <!-- tampilkan pesan error dari PHP -->
      <?php if (isset($_GET['error'])): ?>
        <p class="text-red-500 text-sm text-center mb-4">
          ⚠️ <?php echo htmlspecialchars($_GET['error']); ?>
        </p>
      <?php endif; ?>

      <!-- form registrasi, action ke proses/prosesRegister.php -->
      <form action="prosesRegister.php" method="POST" class="space-y-5">

        <!-- nama lengkap -->
        <div>
          <label for="nama" class="block text-sm font-medium text-gray-900">Nama Lengkap</label>
          <div class="mt-2">
            <input
              id="nama"
              type="text"
              name="nama"
              required
              autocomplete="name"
              class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900
              outline-1 -outline-offset-1 outline-gray-300
              placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600
              sm:text-sm"
            />
          </div>
        </div>

        <!-- tanggal lahir -->
        <div>
          <label for="tanggal_lahir" class="block text-sm font-medium text-gray-900">Tanggal Lahir</label>
          <div class="mt-2">
            <input
              id="tanggal_lahir"
              type="date"
              name="tanggal_lahir"
              required
              autocomplete="bday"
              class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900
              outline-1 -outline-offset-1 outline-gray-300
              placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600
              sm:text-sm"
            />
          </div>
        </div>

        <!-- email -->
        <div>
          <label for="email" class="block text-sm font-medium text-gray-900">Email</label>
          <!-- tipe email, required = wajib diisi -->
          <div class="mt-2">
            <input
              id="email"
              type="email"
              name="email"
              required
              autocomplete="email"
              class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900
              outline-1 -outline-offset-1 outline-gray-300
              placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600
              sm:text-sm"
            />
          </div>
        </div>

        <!-- kata sandi -->
        <div>
          <label for="password" class="block text-sm font-medium text-gray-900">Kata Sandi</label>
          <!-- tipe password = input disembunyikan -->
          <div class="mt-2">
            <input
              id="password"
              type="password"
              name="password"
              required
              autocomplete="new-password"
              class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900
              outline-1 -outline-offset-1 outline-gray-300
              placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600
              sm:text-sm"
            />
          </div>
        </div>

        <!-- konfirmasi kata sandi -->
        <div>
          <label for="konfirm_password" class="block text-sm font-medium text-gray-900">Konfirmasi Kata Sandi</label>
          <div class="mt-2">
            <input
              id="konfirm_password"
              type="password"
              name="konfirm_password"
              required
              autocomplete="new-password"
              class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900
              outline-1 -outline-offset-1 outline-gray-300
              placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600
              sm:text-sm"
            />
          </div>
        </div>

        <!-- tombol daftar -->
        <div>
          <button
            type="submit"
            class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm
            font-semibold text-white hover:bg-indigo-500 transition-colors mt-1">
            Daftar
          </button>
        </div>

      </form>

      <!-- link balik ke login -->
      <p class="mt-8 text-center text-sm text-gray-500">
        Sudah punya akun?
        <a href="login.php" class="font-semibold text-indigo-600 hover:text-indigo-500">
          Masuk di sini
        </a>
      </p>

    </div>
  </div>


</body>
</html>