<?php
require_once 'koneksi.php';
require_once 'session_handler.php';

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
  <style>
    .bg-gradient-cuaca {
      background: linear-gradient(160deg, #0ea5e9 0%, #38bdf8 50%, #bae6fd 100%);
    }
  </style>
  <title>Login - Sistem Prediksi Cuaca</title>
</head>

<body class="bg-gradient-cuaca">

  <div class="flex min-h-screen flex-col justify-center px-6 py-12 lg:px-8">

    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
      <img src="../1f324_3d.webp" alt="Logo Cuaca" class="mx-auto h-20 w-auto" />
      <h2 class="mt-4 text-center text-2xl font-bold tracking-tight text-white">
        Masuk ke akun Anda
      </h2>
    </div>

    <div class="mt-3 sm:mx-auto sm:w-full sm:max-w-sm bg-white/90 backdrop-blur-sm px-8 py-10 rounded-2xl shadow-lg border border-sky-100">

      <?php if (isset($_GET['error'])): ?>
        <p class="text-red-500 text-sm text-center mb-4">
          ⚠️ <?php echo htmlspecialchars($_GET['error']); ?>
        </p>
      <?php endif; ?>

      <?php if (isset($_GET['sukses'])): ?>
        <p class="text-green-500 text-sm text-center mb-4">
          ✅ <?php echo htmlspecialchars($_GET['sukses']); ?>
        </p>
      <?php endif; ?>

      <form action="prosesLogin.php" method="POST" class="space-y-6">

        <div>
          <label for="email" class="block text-sm font-medium text-sky-900">Email</label>
          <div class="mt-2">
            <input id="email" type="email" name="email" required autocomplete="email"
              class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900
              border border-sky-200 placeholder:text-gray-400
              focus:outline-none focus:ring-2 focus:ring-sky-500 sm:text-sm" />
          </div>
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-sky-900">Kata sandi</label>
          <div class="mt-2">
            <input id="password" type="password" name="password" required autocomplete="current-password"
              class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900
              border border-sky-200 placeholder:text-gray-400
              focus:outline-none focus:ring-2 focus:ring-sky-500 sm:text-sm" />
          </div>
        </div>

        <div>
          <button type="submit"
            class="flex w-full justify-center rounded-md px-3 py-1.5 text-sm
            font-semibold text-white transition-colors shadow-md"
            style="background: linear-gradient(90deg, #0284c7, #38bdf8);">
            Masuk
          </button>
        </div>

      </form>

      <p class="mt-10 text-center text-sm text-sky-700">
        Belum punya akun?
        <a href="register.php" class="font-semibold text-sky-600 hover:text-sky-500">
          Daftar di sini
        </a>
      </p>

    </div>
  </div>

</body>
</html>