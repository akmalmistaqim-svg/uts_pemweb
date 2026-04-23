// javascript/cuaca.js

var namaHari  = ["Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu"];
var namaBulan = ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];

function formatTanggal(tanggalStr) {
  var d = new Date(tanggalStr);
  return namaHari[d.getDay()] + ", " + d.getDate()
   + " " + namaBulan[d.getMonth()] + " " + d.getFullYear();
}

function cekPrediksi() {
  var daerah  = document.getElementById('inputDaerah').value;
  var tanggal = document.getElementById('inputTanggal').value;
  var error   = document.getElementById('pesanError');

  if (daerah === '' || tanggal === '') {
    error.classList.remove('hidden');
    return;
  }
  error.classList.add('hidden');

  // fetch data cuaca dari OpenWeatherMap lewat PHP
  fetch('../api/ambilCuaca.php?kota=' + encodeURIComponent(daerah))
    .then(res => res.json())
    .then(cuaca => {

      if (cuaca.error) {
        alert('Gagal ambil data cuaca: ' + cuaca.error);
        return;
      }

      // ambil data dari response OpenWeatherMap
      var suhu      = Math.round(cuaca.main.temp);
      var rasa      = Math.round(cuaca.main.feels_like);
      var lembab    = cuaca.main.humidity;
      var angin     = Math.round(cuaca.wind.speed * 3.6); // m/s ke km/j
      var kondisi   = cuaca.weather[0].description;
      var hujan     = cuaca.clouds.all; // persentase awan sebagai estimasi hujan

      // emoji berdasarkan kondisi
      var emoji = "⛅";
      var iconCode = cuaca.weather[0].icon;
      if (iconCode.includes('01')) emoji = "☀️";
      else if (iconCode.includes('02') || iconCode.includes('03')) emoji = "🌤️";
      else if (iconCode.includes('04')) emoji = "☁️";
      else if (iconCode.includes('09') || iconCode.includes('10')) emoji = "🌧️";
      else if (iconCode.includes('11')) emoji = "⛈️";

      // rekomendasi berdasarkan kondisi
      var rekomendasi = "";
      if (hujan >= 70) {
        rekomendasi = "Waspada hujan lebat! Hindari aktivitas di luar rumah jika tidak mendesak.";
      } else if (suhu >= 33) {
        rekomendasi = "Cuaca sangat panas! Perbanyak minum air putih dan hindari aktivitas berat di luar.";
      } else if (suhu <= 22) {
        rekomendasi = "Cuaca sejuk. Siapkan jaket untuk malam hari.";
      } else {
        rekomendasi = "Cuaca cukup nyaman. Tetap jaga kesehatan dan bawa payung sebagai antisipasi.";
      }

      // tampilkan hasil
      document.getElementById('hasilLokasi').textContent      = '📍 ' + daerah + ', Jawa Timur';
      document.getElementById('hasilTanggal').textContent     = formatTanggal(tanggal);
      document.getElementById('hasilEmoji').textContent       = emoji;
      document.getElementById('hasilSuhu').textContent        = suhu + '°';
      document.getElementById('hasilKondisi').textContent     = kondisi;
      document.getElementById('hasilRasa').textContent        = 'Terasa seperti ' + rasa + '°C';
      document.getElementById('hasilLembab').textContent      = lembab + '%';
      document.getElementById('hasilAngin').textContent       = angin + ' km/j';
      document.getElementById('hasilHujan').textContent       = hujan + '%';
      document.getElementById('hasilUV').textContent          = cuaca.main.pressure + ' hPa';
      document.getElementById('hasilRekomendasi').textContent = rekomendasi;

      // warna card
      var card = document.getElementById('cardUtama');
      if (hujan >= 70) {
        card.className = "bg-gradient-to-br from-slate-600 to-slate-700 text-white rounded-2xl p-6 mb-4 shadow-sm";
      } else if (suhu >= 33) {
        card.className = "bg-gradient-to-br from-orange-400 to-amber-500 text-white rounded-2xl p-6 mb-4 shadow-sm";
      } else {
        card.className = "bg-gradient-to-br from-blue-500 to-sky-400 text-white rounded-2xl p-6 mb-4 shadow-sm";
      }

      document.getElementById('hasilPrediksi').classList.add('tampil');
      document.getElementById('hasilPrediksi').scrollIntoView({ behavior: 'smooth', block: 'start' });

   // simpan kota ke session supaya muncul di kabar_sekitar.php
      fetch('../api/simpanKota.php?kota=' + encodeURIComponent(daerah));

    })
    .catch(err => {
      alert('Terjadi kesalahan: ' + err);
    });
}

function cekLagi() {
  document.getElementById('hasilPrediksi').classList.remove('tampil');
  document.getElementById('inputDaerah').value  = '';
  document.getElementById('inputTanggal').value = '';
  window.scrollTo({ top: 0, behavior: 'smooth' });
}