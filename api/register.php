<?php
session_start();
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

  <!-- Flatpickr CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

  <style>
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

<body class="bg-sky-600">

  <div class="flex min-h-screen flex-col justify-center px-6 py-12 lg:px-8">

    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
      <img src="../1f324_3d.webp" alt="Logo Cuaca" class="mx-auto h-20 w-auto" />
      <h2 class="mt-4 text-center text-2xl font-bold tracking-tight text-white">
        Buat akun baru
      </h2>
    </div>

    <div class="mt-3 sm:mx-auto sm:w-full sm:max-w-sm bg-white px-8 py-10 rounded-2xl shadow-sm">

      <?php if (isset($_GET['error'])): ?>
        <p class="text-red-500 text-sm text-center mb-4">
          ⚠️ <?php echo htmlspecialchars($_GET['error']); ?>
        </p>
      <?php endif; ?>

      <form action="prosesRegister.php" method="POST" class="space-y-5">

        <div>
          <label for="nama" class="block text-sm font-medium text-gray-900">Nama Lengkap</label>
          <div class="mt-2">
            <input id="nama" type="text" name="nama" required autocomplete="name"
              class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900
              border border-gray-300 placeholder:text-gray-400
              focus:outline-none focus:ring-2 focus:ring-indigo-600 sm:text-sm" />
          </div>
        </div>

        <div>
          <label for="tanggal_lahir" class="block text-sm font-medium text-gray-900">Tanggal Lahir</label>
          <div class="mt-2">
            <!-- Hidden input untuk nilai yang dikirim ke server (format YYYY-MM-DD) -->
            <input id="tanggal_lahir" type="hidden" name="tanggal_lahir" />
            <!-- Input yang tampil ke user (format DD/MM/YYYY) -->
            <input id="tanggal_lahir_display"
              type="text"
              placeholder="DD/MM/YYYY"
              readonly
              class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900
              border border-gray-300 placeholder:text-gray-400
              focus:outline-none focus:ring-2 focus:ring-indigo-600 sm:text-sm cursor-pointer" />
          </div>
        </div>

        <div>
          <label for="email" class="block text-sm font-medium text-gray-900">Email</label>
          <div class="mt-2">
            <input id="email" type="email" name="email" required autocomplete="email"
              class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900
              border border-gray-300 placeholder:text-gray-400
              focus:outline-none focus:ring-2 focus:ring-indigo-600 sm:text-sm" />
          </div>
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-gray-900">Kata Sandi</label>
          <div class="mt-2">
            <input id="password" type="password" name="password" required autocomplete="new-password"
              class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900
              border border-gray-300 placeholder:text-gray-400
              focus:outline-none focus:ring-2 focus:ring-indigo-600 sm:text-sm" />
          </div>
        </div>

        <div>
          <label for="konfirm_password" class="block text-sm font-medium text-gray-900">Konfirmasi Kata Sandi</label>
          <div class="mt-2">
            <input id="konfirm_password" type="password" name="konfirm_password" required autocomplete="new-password"
              class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900
              border border-gray-300 placeholder:text-gray-400
              focus:outline-none focus:ring-2 focus:ring-indigo-600 sm:text-sm" />
          </div>
        </div>

        <div>
          <button type="submit"
            class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm
            font-semibold text-white hover:bg-indigo-500 transition-colors mt-1">
            Daftar
          </button>
        </div>

      </form>

      <p class="mt-8 text-center text-sm text-gray-500">
        Sudah punya akun?
        <a href="login.php" class="font-semibold text-indigo-600 hover:text-indigo-500">
          Masuk di sini
        </a>
      </p>

    </div>
  </div>

  <!-- Flatpickr JS -->
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script>
    flatpickr("#tanggal_lahir_display", {
      dateFormat: "d/m/Y",
      maxDate: "today",
      disableMobile: true,
      onChange: function(selectedDates, dateStr, instance) {
        if (selectedDates.length > 0) {
          const d = selectedDates[0];
          const yyyy = d.getFullYear();
          const mm = String(d.getMonth() + 1).padStart(2, '0');
          const dd = String(d.getDate()).padStart(2, '0');
          document.getElementById('tanggal_lahir').value = `${yyyy}-${mm}-${dd}`;
        }
      }
    });

    document.querySelector('form').addEventListener('submit', function(e) {
      const tgl = document.getElementById('tanggal_lahir').value;
      if (!tgl) {
        e.preventDefault();
        alert('Tanggal lahir wajib diisi!');
        document.getElementById('tanggal_lahir_display').focus();
      }
    });
  </script>

</body>
</html>