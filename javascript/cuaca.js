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
      // Cari index data sesuai tanggal yang dipilih
var list = cuaca.list;
var idx  = 0;
if (tanggal && list) {
  for (var i = 0; i < list.length; i++) {
    if (list[i].dt_txt.startsWith(tanggal)) {
      idx = i;
      break;
    }
  }
}

// Kalau tanggal di luar 5 hari, pakai data terakhir yang ada
if (!list || !list[idx]) {
  idx = list.length - 1;
}

var item = list[idx];
console.log('item:', item);
console.log('list length:', list.length);
console.log('idx:', idx);
var suhu    = Math.round(item.main.temp);
var rasa    = Math.round(item.main.feels_like);
var lembab  = item.main.humidity;
var angin   = Math.round(item.wind.speed * 3.6);
var kondisi = item.weather[0].description;
var hujan   = item.clouds.all;

var emoji    = "⛅";
var iconCode = item.weather[0].icon;
if (iconCode.includes('01')) emoji = "☀️";
else if (iconCode.includes('02') || iconCode.includes('03')) emoji = "🌤️";
else if (iconCode.includes('04')) emoji = "☁️";
else if (iconCode.includes('09') || iconCode.includes('10')) emoji = "🌧️";
else if (iconCode.includes('11')) emoji = "⛈️";


      // tampilkan hasil
      document.getElementById('hasilLokasi').textContent      = '📍 ' + daerah + ', Jawa Timur';
      document.getElementById('hasilTanggal').textContent     = formatTanggal(tanggal);
      document.getElementById('hasilEmoji').textContent       = emoji;
      document.getElementById('hasilSuhu').textContent        = suhu + '°';
      document.getElementById('hasilKondisi').textContent     = kondisi;
      document.getElementById('hasilLembab').textContent      = lembab + '%';
      document.getElementById('hasilAngin').textContent       = angin + ' km/j';
      document.getElementById('hasilHujan').textContent       = hujan + '%';
      document.getElementById('hasilUV').textContent = item.main.pressure + ' hPa';
      tampilkanRekomendasiPetani(suhu, lembab, hujan, angin, kondisi, emoji);

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
      // simpan riwayat prediksi ke database
      fetch('../api/simpanRiwayat.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          kota: daerah,
          tanggal: tanggal,
          kondisi: kondisi,
          suhu: suhu
        })
      });

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

// =============================================
// REKOMENDASI PETANI
// =============================================

function setTabRek(tab) {
  document.getElementById('rekMinggu').classList.toggle('hidden', tab !== 'minggu');
  document.getElementById('rekBulan').classList.toggle('hidden', tab !== 'bulan');

  var aktif   = 'flex-1 py-3 text-sm font-semibold text-sky-600 border-b-2 border-sky-500 bg-white transition-all';
  var nonaktif = 'flex-1 py-3 text-sm font-semibold text-gray-400 border-b-2 border-transparent transition-all';
  document.getElementById('tabMinggu').className = tab === 'minggu' ? aktif : nonaktif;
  document.getElementById('tabBulan').className  = tab === 'bulan'  ? aktif : nonaktif;
}

function buatKartu(bg, border, ikonClass, warnaBadge, warnaTextBadge, labelBadge, warnaJudul, judul, warnaIsi, isi) {
  return `
    <div style="background:${bg}; border:0.5px solid ${border}; border-radius:12px; padding:14px 16px; display:flex; gap:14px; align-items:flex-start;">
      <i class="${ikonClass}" style="font-size:22px; color:${warnaIsi}; margin-top:2px; flex-shrink:0;"></i>
      <div>
        <span style="display:inline-block; font-size:11px; font-weight:600; padding:3px 10px; border-radius:999px; margin-bottom:6px; background:${warnaBadge}; color:${warnaTextBadge};">${labelBadge}</span>
        <p style="font-size:14px; font-weight:600; color:${warnaJudul}; margin:0 0 4px;">${judul}</p>
        <p style="font-size:13px; color:${warnaIsi}; margin:0; line-height:1.65;">${isi}</p>
      </div>
    </div>`;
}

function buatAktivitas(warnaDot, judul, isi) {
  return `
    <div style="display:flex; align-items:flex-start; gap:10px; padding:10px 16px;">
      <span style="width:8px; height:8px; border-radius:50%; background:${warnaDot}; flex-shrink:0; margin-top:5px;"></span>
      <div>
        <p style="font-size:13px; font-weight:600; color:#111827; margin:0 0 2px;">${judul}</p>
        <p style="font-size:12px; color:#6b7280; margin:0; line-height:1.6;">${isi}</p>
      </div>
    </div>`;
}

function tampilkanRekomendasiPetani(suhu, lembab, hujan, angin, kondisi, emoji) {

  // ── Banner minggu ──
  var judulBanner = 'Kondisi minggu ini: ' + kondisi;
  var detailBanner = suhu + '°C · Kelembaban ' + lembab + '% · Peluang hujan ' + hujan + '% · Angin ' + angin + ' km/j';
  document.getElementById('rekEmojiBanner').textContent  = emoji;
  document.getElementById('rekJudulBanner').textContent  = judulBanner;
  document.getElementById('rekDetailBanner').textContent = detailBanner;

  // ── Banner bulan ──
  var judulBulan  = hujan >= 70 ? 'Prediksi Juni: Musim hujan, waspadai banjir lahan'
                  : suhu >= 33  ? 'Prediksi Juni: Kemarau panas, irigasi jadi kunci'
                  : 'Prediksi Juni: Awal musim kemarau ringan';
  var detailBulan = hujan >= 70 ? 'Hujan masih sering · Drainase harus siap'
                  : suhu >= 33  ? 'Suhu tinggi · Kelembaban turun · Siram lebih sering'
                  : 'Lebih cerah · Suhu naik · Potensi hujan di akhir bulan';
  document.getElementById('rekJudulBulan').textContent  = judulBulan;
  document.getElementById('rekDetailBulan').textContent = detailBulan;

  // ── Kartu minggu ──
  var kartuMinggu = [];

  // Penyiraman
  if (lembab >= 80) {
    kartuMinggu.push(buatKartu('#eaf3de','#c0dd97','ti ti-droplet','#c0dd97','#27500a','Penyiraman','#27500a','Kurangi frekuensi siram','#3b6d11',
      'Kelembaban ' + lembab + '%, tanah masih cukup lembap. Siram hanya jika tanah kering saat disentuh. Waktu terbaik: pagi 06.00–07.00 atau sore 16.00–17.00.'));
  } else if (lembab <= 50 || suhu >= 33) {
    kartuMinggu.push(buatKartu('#faeeda','#fac775','ti ti-droplet','#fac775','#412402','Penyiraman','#412402','Siram lebih sering dari biasanya','#633806',
      'Suhu ' + suhu + '°C dan kelembaban rendah membuat tanah cepat kering. Siram pagi dan sore hari. Gunakan mulsa untuk menjaga kelembaban tanah lebih lama.'));
  } else {
    kartuMinggu.push(buatKartu('#eaf3de','#c0dd97','ti ti-droplet','#c0dd97','#27500a','Penyiraman','#27500a','Siram normal seperti biasa','#3b6d11',
      'Kondisi kelembaban ' + lembab + '% cukup ideal. Siram seperti biasa pagi pukul 06.00–07.00 atau sore 16.00–17.00.'));
  }

  // Pemupukan
  if (angin <= 10 && suhu < 33 && hujan < 50) {
    kartuMinggu.push(buatKartu('#faeeda','#fac775','ti ti-seeding','#fac775','#412402','Pemupukan','#412402','Waktu tepat untuk pupuk daun','#633806',
      'Suhu ' + suhu + '°C dan angin pelan (' + angin + ' km/j) cocok untuk penyemprotan pupuk daun. Lakukan pagi hari. Hindari siang hari agar pupuk tidak menguap.'));
  } else if (hujan >= 70) {
    kartuMinggu.push(buatKartu('#fcebeb','#f7c1c1','ti ti-seeding','#f7c1c1','#501313','Pemupukan','#501313','Tunda pemupukan minggu ini','#791f1f',
      'Peluang hujan ' + hujan + '% terlalu tinggi. Pupuk akan terbawa air hujan sebelum terserap tanaman. Tunggu cuaca lebih kering sebelum memupuk.'));
  }

  // Hama
  if (lembab >= 75) {
    kartuMinggu.push(buatKartu('#fcebeb','#f7c1c1','ti ti-bug','#f7c1c1','#501313','Peringatan Hama','#501313','Risiko jamur & wereng meningkat','#791f1f',
      'Kelembaban ' + lembab + '% mempercepat tumbuhnya jamur daun dan perkembangan wereng. Periksa bagian bawah daun 2x seminggu. Siapkan fungisida organik jika ada bercak.'));
  }

  // Penanaman
  if (hujan >= 70) {
    kartuMinggu.push(buatKartu('#e6f1fb','#b5d4f4','ti ti-plant-2','#b5d4f4','#042c53','Penanaman','#042c53','Tunda penanaman bibit baru','#0c447c',
      'Hujan lebat bisa merusak bibit muda yang baru ditanam. Tunda penanaman hingga cuaca lebih stabil. Manfaatkan waktu ini untuk menyiapkan dan mengolah lahan.'));
  } else if (suhu >= 26 && suhu <= 32 && lembab >= 60 && hujan < 50) {
    kartuMinggu.push(buatKartu('#eaf3de','#c0dd97','ti ti-plant-2','#c0dd97','#27500a','Penanaman','#27500a','Kondisi mendukung untuk menanam','#3b6d11',
      'Suhu ' + suhu + '°C dan kelembaban ' + lembab + '% ideal untuk pertumbuhan bibit. Cocok menanam padi, jagung, atau sayuran. Tanam pagi hari sebelum terik.'));
  } else {
    kartuMinggu.push(buatKartu('#faeeda','#fac775','ti ti-plant-2','#fac775','#412402','Penanaman','#412402','Cukup kondusif, perhatikan suhu','#633806',
      'Kondisi cukup mendukung namun perhatikan suhu ' + suhu + '°C. Pilih varietas yang sesuai iklim dan tanam di pagi hari untuk mengurangi stres panas pada bibit.'));
  }

  document.getElementById('rekKartuMinggu').innerHTML = kartuMinggu.join('');

  // ── Aktivitas minggu petani ──
var aktMinggu = [];

// Senin–Selasa: tergantung hujan & angin
if (hujan >= 70) {
  aktMinggu.push(buatAktivitas('#a32d2d', 'Senin–Selasa: Pantau drainase lahan',
    'Hujan lebat diprediksi. Jangan keluar lahan saat hujan deras. Pastikan saluran air tidak tersumbat agar lahan tidak tergenang.'));
} else if (angin <= 10 && suhu < 33) {
  aktMinggu.push(buatAktivitas('#639922', 'Senin–Selasa: Semprot pupuk daun',
    'Angin pelan ' + angin + ' km/j dan suhu ' + suhu + '°C ideal untuk pemupukan daun. Lakukan pagi pukul 05.30–07.00 sebelum suhu naik.'));
} else if (suhu >= 33) {
  aktMinggu.push(buatAktivitas('#ba7517', 'Senin–Selasa: Siram pagi & pasang mulsa',
    'Suhu ' + suhu + '°C cukup panas. Siram pagi sebelum terik. Pasang mulsa di sekitar tanaman untuk menjaga kelembaban tanah.'));
} else {
  aktMinggu.push(buatAktivitas('#639922', 'Senin–Selasa: Perawatan rutin tanaman',
    'Kondisi cuaca cukup normal. Lakukan penyiraman pagi dan periksa kondisi tanaman secara umum.'));
}

// Rabu: tergantung kelembaban
if (lembab >= 75) {
  aktMinggu.push(buatAktivitas('#639922', 'Rabu: Penyiangan gulma',
    'Kelembaban ' + lembab + '% membuat tanah lembap sehingga gulma mudah dicabut. Kerjakan pagi hari sebelum terik.'));
} else if (hujan >= 70) {
  aktMinggu.push(buatAktivitas('#a32d2d', 'Rabu: Periksa kondisi tanaman',
    'Setelah hujan, periksa apakah ada tanaman roboh atau akar terendam. Tegakkan kembali tanaman yang miring.'));
} else {
  aktMinggu.push(buatAktivitas('#639922', 'Rabu: Penyiangan & penggemburan tanah',
    'Tanah cukup kering, cocok untuk penggemburan sekaligus penyiangan gulma. Lakukan pagi hari.'));
}

// Kamis: inspeksi hama (selalu, tapi pesannya beda)
if (lembab >= 75 || hujan >= 50) {
  aktMinggu.push(buatAktivitas('#ba7517', 'Kamis: Inspeksi hama & jamur',
    'Kelembaban ' + lembab + '% meningkatkan risiko jamur dan wereng. Periksa bagian bawah daun, semprot fungisida organik jika ada bercak.'));
} else if (suhu >= 33) {
  aktMinggu.push(buatAktivitas('#ba7517', 'Kamis: Inspeksi hama & stres panas',
    'Suhu tinggi ' + suhu + '°C bisa memicu stres pada tanaman. Periksa daun yang mengering atau menggulung, tanda kekurangan air.'));
} else {
  aktMinggu.push(buatAktivitas('#ba7517', 'Kamis: Inspeksi rutin hama & penyakit',
    'Periksa bagian bawah daun untuk kutu, ulat, atau bercak. Catat tanaman bergejala dan semprot jika ditemukan.'));
}

// Jumat: penyiraman tergantung kondisi
if (hujan >= 70) {
  aktMinggu.push(buatAktivitas('#a32d2d', 'Jumat: Kurangi atau tunda penyiraman',
    'Hujan sudah cukup membasahi tanah. Tunda penyiraman hari ini. Cek kelembaban tanah sebelum memutuskan siram atau tidak.'));
} else if (lembab >= 80) {
  aktMinggu.push(buatAktivitas('#ba7517', 'Jumat: Cek tanah, siram jika perlu',
    'Kelembaban udara masih ' + lembab + '%. Cek kondisi tanah dulu — jika masih lembap, tunda siram. Kalau kering, siram sore pukul 16.00–17.30.'));
} else {
  aktMinggu.push(buatAktivitas('#ba7517', 'Jumat: Siram tambahan sore hari',
    'Kelembaban rendah ' + lembab + '% dan suhu ' + suhu + '°C membuat tanah cepat kering. Siram sore pukul 16.00–17.30 dan gunakan mulsa.'));
}

// Sabtu–Minggu: tergantung kondisi umum
if (hujan >= 70) {
  aktMinggu.push(buatAktivitas('#185fa5', 'Sabtu–Minggu: Perkuat drainase & pematang',
    'Manfaatkan jeda hujan untuk bersihkan saluran air dan perkuat pematang sawah agar lahan tidak kebanjiran minggu depan.'));
} else if (suhu >= 33) {
  aktMinggu.push(buatAktivitas('#185fa5', 'Sabtu–Minggu: Siapkan sistem irigasi',
    'Kemarau panas diprediksi berlanjut. Cek pompa dan selang irigasi. Pertimbangkan pasang mulsa di seluruh lahan untuk hemat air.'));
} else {
  aktMinggu.push(buatAktivitas('#185fa5', 'Sabtu–Minggu: Olah & siapkan lahan',
    'Bajak tanah, tambahkan kompos atau pupuk kandang. Bersihkan saluran drainase sebagai antisipasi perubahan cuaca.'));
}

document.getElementById('rekAktivitasMinggu').innerHTML = aktMinggu.join('');

  // ── Kartu bulan ──
  var kartuBulan = [];

  if (hujan >= 70) {
    kartuBulan.push(buatKartu('#eaf3de','#c0dd97','ti ti-plant-2','#c0dd97','#27500a','Tanam','#27500a','Pilih tanaman tahan air','#3b6d11',
      'Kondisi basah cocok untuk padi sawah. Hindari tanaman yang mudah busuk akar. Pastikan drainase lahan berjalan baik sebelum tanam.'));
    kartuBulan.push(buatKartu('#fcebeb','#f7c1c1','ti ti-cloud-rain','#f7c1c1','#501313','Antisipasi','#501313','Perkuat drainase & pematang sawah','#791f1f',
      'Hujan masih sering terjadi. Bersihkan saluran air, perkuat pematang agar lahan tidak tergenang. Lakukan minggu ini sebelum hujan semakin lebat.'));
    kartuBulan.push(buatKartu('#faeeda','#fac775','ti ti-droplet','#fac775','#412402','Irigasi','#412402','Kurangi irigasi, manfaatkan air hujan','#633806',
      'Air hujan sudah cukup. Kurangi penggunaan pompa. Fokus pada pengelolaan air agar tidak berlebih dan merusak akar tanaman.'));
  } else if (suhu >= 33) {
    kartuBulan.push(buatKartu('#eaf3de','#c0dd97','ti ti-plant-2','#c0dd97','#27500a','Tanam','#27500a','Pilih tanaman tahan panas','#3b6d11',
      'Kemarau panas cocok untuk jagung, cabai, dan kacang tanah. Ketiganya tahan suhu tinggi dan butuh sinar matahari penuh. Siapkan benih dari sekarang.'));
    kartuBulan.push(buatKartu('#faeeda','#fac775','ti ti-droplet','#fac775','#412402','Irigasi','#412402','Siram lebih sering, 1–2 hari sekali','#633806',
      'Kemarau membuat tanah cepat kering. Jadwalkan irigasi 1–2 hari sekali. Pastikan pompa dan selang irigasi berfungsi baik sebelum musim tanam.'));
    kartuBulan.push(buatKartu('#fcebeb','#f7c1c1','ti ti-sun','#f7c1c1','#501313','Perhatian','#501313','Pasang mulsa untuk kurangi penguapan','#791f1f',
      'Suhu tinggi mempercepat penguapan air tanah. Gunakan mulsa jerami atau plastik di sekitar tanaman untuk menjaga kelembaban tanah lebih lama.'));
  } else {
    kartuBulan.push(buatKartu('#eaf3de','#c0dd97','ti ti-plant-2','#c0dd97','#27500a','Tanam','#27500a','Cocok menanam jagung & cabai','#3b6d11',
      'Awal bulan depan ideal untuk jagung dan cabai. Keduanya tahan kemarau ringan dan butuh sinar matahari penuh. Siapkan benih dari sekarang.'));
    kartuBulan.push(buatKartu('#faeeda','#fac775','ti ti-droplet','#fac775','#412402','Irigasi','#412402','Siapkan sistem irigasi dari sekarang','#633806',
      'Kemarau ringan membuat tanah lebih cepat kering. Jadwalkan penyiraman 2 hari sekali. Pastikan pompa dan selang berfungsi baik sebelum musim tanam.'));
    kartuBulan.push(buatKartu('#fcebeb','#f7c1c1','ti ti-cloud-rain','#f7c1c1','#501313','Antisipasi','#501313','Waspadai hujan tiba-tiba di akhir bulan','#791f1f',
      'Potensi hujan meningkat di akhir bulan. Bersihkan saluran drainase dan perkuat pematang sawah agar lahan tidak tergenang.'));
  }

  document.getElementById('rekKartuBulan').innerHTML = kartuBulan.join('');

  // ── Aktivitas bulan ──
  var aktBulan = [];
  aktBulan.push(buatAktivitas('#639922', 'Akhir bulan ini: Olah lahan & pupuk dasar',
    'Bajak tanah dan campurkan pupuk kandang atau kompos. Beri waktu 5–7 hari agar tanah siap menerima bibit di awal bulan depan.'));

  if (hujan >= 70) {
    aktBulan.push(buatAktivitas('#639922', 'Awal bulan depan: Tanam padi sawah',
      'Kondisi basah cocok untuk padi. Tanam pagi hari. Pastikan drainase sawah terkontrol agar air tidak menggenangi bibit muda.'));
  } else {
    aktBulan.push(buatAktivitas('#639922', 'Awal bulan depan: Tanam jagung atau cabai',
      'Tanam di pagi hari saat suhu belum tinggi. Beri jarak tanam yang cukup agar sirkulasi udara baik dan mengurangi risiko penyakit.'));
  }

  aktBulan.push(buatAktivitas('#ba7517', 'Minggu ke-2: Siram rutin & pupuk susulan',
    'Siram sesuai jadwal. Berikan pupuk NPK susulan di minggu ke-2 setelah tanam untuk mendorong pertumbuhan vegetatif yang optimal.'));
  aktBulan.push(buatAktivitas('#ba7517', 'Minggu ke-3: Inspeksi hama & penyakit',
    'Periksa tanaman secara menyeluruh. Pantau wereng, ulat grayak, dan bercak daun. Siapkan pestisida organik jika ditemukan gejala awal.'));
  aktBulan.push(buatAktivitas('#185fa5', 'Akhir bulan: Bersihkan drainase & pantau cuaca',
    'Hujan bisa datang tiba-tiba. Pastikan saluran air tidak tersumbat agar tanaman tidak terendam saat hujan deras mendatang.'));

  document.getElementById('rekAktivitasBulan').innerHTML = aktBulan.join('');
}