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
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <title>Login - Sistem Prediksi Cuaca</title>
</head>

<body class="bg-sky-600">

  <div class="flex min-h-screen flex-col justify-center px-6 py-12 lg:px-8">

    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
      <img src="1f324_3d.webp" alt="Logo Cuaca" class="mx-auto h-20 w-auto" />
      <h2 class="mt-4 text-center text-2xl font-bold tracking-tight text-white">
        Masuk ke akun Anda
      </h2>
    </div>

    <!-- box login -->
    <div class="mt-3 sm:mx-auto sm:w-full sm:max-w-sm bg-white px-8 py-10 rounded-2xl shadow-sm">

      <!-- tampilkan pesan error dari PHP -->
      <?php if (isset($_GET['error'])): ?>
        <p class="text-red-500 text-sm text-center mb-4">
          ⚠️ <?php echo htmlspecialchars($_GET['error']); ?>
        </p>
      <?php endif; ?>

      <!-- tampilkan pesan sukses setelah register -->
      <?php if (isset($_GET['sukses'])): ?>
        <p class="text-green-500 text-sm text-center mb-4">
          ✅ <?php echo htmlspecialchars($_GET['sukses']); ?>
        </p>
      <?php endif; ?>

      <!-- form login, action ke proses/prosesLogin.php -->
      <form action="proses/prosesLogin.php" method="POST" class="space-y-6">

        <div>
          <label for="email" class="block text-sm font-medium text-gray-900">Email</label>
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

        <!-- input kata sandi -->
        <div>
          <div class="flex items-center justify-between">
            <label for="password" class="block text-sm font-medium text-gray-900">Kata sandi</label>
          </div>
          <!-- tipe password = input disembunyikan -->
          <div class="mt-2">
            <input
              id="password"
              type="password"
              name="password"
              required
              autocomplete="current-password"
              class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900
              outline-1 -outline-offset-1 outline-gray-300
              placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600
              sm:text-sm"
            />
          </div>
        </div>

        <div>
          <button
            type="submit"
            class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm
            font-semibold text-white hover:bg-indigo-500 transition-colors">
            Masuk
          </button>
        </div>

      </form>

      <!-- link ke register -->
      <p class="mt-10 text-center text-sm text-gray-500">
        Belum punya akun?
        <a href="register.php" class="font-semibold text-indigo-600 hover:text-indigo-500">
          Daftar di sini
        </a>
      </p>

    </div>
  </div>

</body>
</html>