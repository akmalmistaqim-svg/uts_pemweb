<!-- ====================================================
     SECTION GRAFIK CUACA - CuacaKu
     Taruh include ini di dashboard.php SEBELUM section tentang:
     <?php include 'grafik.php'; ?>
     ==================================================== -->

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<style>
  .section-grafik {
    background: linear-gradient(180deg, #f0f9ff 0%, #e0f2fe 100%);
    padding: 4rem 1.5rem;
  }
  .grafik-card {
    background: #ffffff;
    border-radius: 1.5rem;
    border: 1px solid #bae6fd;
    box-shadow: 0 4px 24px rgba(14,165,233,0.08);
    padding: 2rem;
    max-width: 56rem;
    margin: 0 auto;
  }
  .grafik-search-wrap {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
  }
  .grafik-input {
    flex: 1;
    border: 1.5px solid #bae6fd;
    border-radius: 0.75rem;
    padding: 0.65rem 1rem;
    font-size: 0.95rem;
    color: #1e293b;
    outline: none;
    transition: border-color 0.2s;
    background: #f0f9ff;
  }
  .grafik-input:focus { border-color: #38bdf8; background: #fff; }
  .grafik-btn-cari {
    background: linear-gradient(135deg, #0ea5e9, #0284c7);
    color: white;
    border: none;
    border-radius: 0.75rem;
    padding: 0.65rem 1.4rem;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    transition: opacity 0.2s, transform 0.1s;
    white-space: nowrap;
  }
  .grafik-btn-cari:hover { opacity: 0.9; }
  .grafik-btn-cari:active { transform: scale(0.97); }
  .grafik-btn-cari:disabled { opacity: 0.6; cursor: not-allowed; }

  /* Tabs */
  .grafik-tabs {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1.25rem;
    border-bottom: 1.5px solid #e0f2fe;
    padding-bottom: 0.75rem;
  }
  .grafik-tab {
    background: transparent;
    border: 1.5px solid #bae6fd;
    border-radius: 0.6rem;
    padding: 0.4rem 1.1rem;
    font-size: 0.85rem;
    cursor: pointer;
    color: #0369a1;
    font-weight: 500;
    transition: all 0.15s;
  }
  .grafik-tab.aktif {
    background: #0ea5e9;
    color: white;
    border-color: #0ea5e9;
  }
  .grafik-tab:hover:not(.aktif) { background: #e0f2fe; }

  /* Metric cards */
  .grafik-metrics {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 0.75rem;
    margin-bottom: 1.5rem;
  }
  .grafik-metric {
    background: #f0f9ff;
    border: 1px solid #bae6fd;
    border-radius: 1rem;
    padding: 0.85rem 1rem;
    text-align: center;
  }
  .grafik-metric-label {
    font-size: 0.75rem;
    color: #64748b;
    margin-bottom: 0.25rem;
  }
  .grafik-metric-value {
    font-size: 1.3rem;
    font-weight: 700;
    color: #0369a1;
  }
  .grafik-metric-icon { font-size: 1.2rem; }

  /* Chart area */
  .grafik-canvas-wrap {
    position: relative;
    width: 100%;
    height: 300px;
  }

  /* Legend */
  .grafik-legend {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-top: 1rem;
    font-size: 0.78rem;
    color: #64748b;
  }
  .grafik-legend-item {
    display: flex;
    align-items: center;
    gap: 5px;
  }
  .grafik-legend-dot {
    width: 10px;
    height: 10px;
    border-radius: 2px;
    display: inline-block;
  }

  /* Status */
  .grafik-status {
    text-align: center;
    padding: 2.5rem 0;
    color: #94a3b8;
    font-size: 0.9rem;
  }
  .grafik-error { color: #ef4444; }

  /* Spinner */
  .grafik-spinner {
    display: inline-block;
    width: 22px;
    height: 22px;
    border: 3px solid #bae6fd;
    border-top-color: #0ea5e9;
    border-radius: 50%;
    animation: spin 0.7s linear infinite;
    vertical-align: middle;
    margin-right: 8px;
  }
  @keyframes spin { to { transform: rotate(360deg); } }

  .grafik-kota-label {
    font-size: 0.8rem;
    color: #0369a1;
    background: #e0f2fe;
    border-radius: 999px;
    padding: 0.2rem 0.85rem;
    display: inline-block;
    margin-bottom: 1rem;
    font-weight: 500;
  }

  @media (max-width: 480px) {
    .grafik-card { padding: 1.25rem; }
    .grafik-canvas-wrap { height: 230px; }
  }
</style>

<section id="grafik" class="section-grafik">
  <div class="max-w-4xl mx-auto">

    <div class="text-center mb-10">
      <h2 class="text-3xl font-bold text-slate-800 mb-3">Grafik Cuaca Jawa Timur</h2>
      <p class="text-slate-500 max-w-xl mx-auto text-sm">
        Masukkan nama kota di Jawa Timur untuk melihat grafik cuaca harian, mingguan, dan bulanan.
      </p>
    </div>

    <div class="grafik-card">

      <!-- Input Kota -->
      <div class="grafik-search-wrap">
        <input
          type="text"
          id="grafikInputKota"
          class="grafik-input"
          placeholder="Ketik nama kota... (contoh: Surabaya, Malang, Banyuwangi)"
          autocomplete="off"
        />
        <button class="grafik-btn-cari" id="grafikBtnCari" onclick="grafikCariKota()">
          🔍 Cari
        </button>
      </div>

      <!-- Area konten grafik -->
      <div id="grafikKonten">
        <div class="grafik-status">
          ☁️ Masukkan nama kota untuk menampilkan grafik cuaca
        </div>
      </div>

    </div>
  </div>
</section>

<script>
(function() {

  let grafikChart = null;
  let grafikTabAktif = 'harian';
  let grafikDataCache = null;
  let grafikNamaKota = '';

  // Tekan Enter untuk cari
  document.getElementById('grafikInputKota').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') grafikCariKota();
  });

  window.grafikCariKota = async function() {
    const kota = document.getElementById('grafikInputKota').value.trim();
    if (!kota) {
      document.getElementById('grafikKonten').innerHTML =
        '<div class="grafik-status grafik-error">⚠️ Masukkan nama kota terlebih dahulu.</div>';
      return;
    }

    const btn = document.getElementById('grafikBtnCari');
    btn.disabled = true;
    btn.textContent = 'Mencari...';

    document.getElementById('grafikKonten').innerHTML =
      '<div class="grafik-status"><span class="grafik-spinner"></span> Mencari lokasi <b>' + kota + '</b>...</div>';

    try {
      // Step 1: Geocoding - cari koordinat kota
      const geoRes = await fetch(
        'https://geocoding-api.open-meteo.com/v1/search?name=' +
        encodeURIComponent(kota + ' Jawa Timur Indonesia') +
        '&count=5&language=id&format=json'
      );
      const geoData = await geoRes.json();

      if (!geoData.results || geoData.results.length === 0) {
        throw new Error('Kota "' + kota + '" tidak ditemukan. Coba nama yang lebih spesifik.');
      }

      // Pilih hasil yang paling relevan (filter Jawa Timur dulu)
      let lokasi = geoData.results.find(r =>
        r.admin1 && r.admin1.toLowerCase().includes('jawa timur')
      ) || geoData.results[0];

      grafikNamaKota = lokasi.name + (lokasi.admin2 ? ', ' + lokasi.admin2 : '');

      document.getElementById('grafikKonten').innerHTML =
        '<div class="grafik-status"><span class="grafik-spinner"></span> Mengambil data cuaca untuk <b>' + grafikNamaKota + '</b>...</div>';

      const lat = lokasi.latitude;
      const lon = lokasi.longitude;

      // Step 2: Ambil forecast 16 hari (untuk harian & mingguan)
      const forecastRes = await fetch(
        'https://api.open-meteo.com/v1/forecast?' +
        'latitude=' + lat + '&longitude=' + lon +
        '&daily=temperature_2m_max,temperature_2m_min,precipitation_sum,relative_humidity_2m_mean,weathercode' +
        '&timezone=Asia%2FJakarta' +
        '&forecast_days=16'
      );
      const forecastData = await forecastRes.json();

      // Step 3: Ambil data historis 12 bulan (untuk bulanan)
      const today = new Date();
      const endDate = today.toISOString().split('T')[0];
      const startDate = new Date(today.getFullYear() - 1, today.getMonth() + 1, 1)
        .toISOString().split('T')[0];

      const histRes = await fetch(
        'https://archive-api.open-meteo.com/v1/archive?' +
        'latitude=' + lat + '&longitude=' + lon +
        '&start_date=' + startDate + '&end_date=' + endDate +
        '&daily=temperature_2m_max,temperature_2m_min,precipitation_sum,relative_humidity_2m_mean' +
        '&timezone=Asia%2FJakarta'
      );
      const histData = await histRes.json();

      // Olah data
      grafikDataCache = {
        harian: olaHarian(forecastData.daily),
        mingguan: olahMingguan(forecastData.daily),
        bulanan: olahBulanan(histData.daily)
      };

      grafikTabAktif = 'harian';
      renderGrafikKonten();

    } catch (err) {
      document.getElementById('grafikKonten').innerHTML =
        '<div class="grafik-status grafik-error">❌ ' + err.message + '</div>';
    } finally {
      btn.disabled = false;
      btn.textContent = '🔍 Cari';
    }
  };

  // =====================
  // OLAH DATA
  // =====================

  function olaHarian(daily) {
    const labels = daily.time.slice(0, 7).map(t => {
      const d = new Date(t);
      return ['Min','Sen','Sel','Rab','Kam','Jum','Sab'][d.getDay()];
    });
    return {
      labels,
      suhuMax: daily.temperature_2m_max.slice(0, 7).map(v => Math.round(v)),
      suhuMin: daily.temperature_2m_min.slice(0, 7).map(v => Math.round(v)),
      hujan: daily.precipitation_sum.slice(0, 7).map(v => Math.round(v * 10) / 10),
      kelembaban: daily.relative_humidity_2m_mean.slice(0, 7).map(v => Math.round(v)),
    };
  }

  function olahMingguan(daily) {
    const minggu = [[], [], [], []];
    for (let i = 0; i < Math.min(28, daily.time.length); i++) {
      minggu[Math.floor(i / 7)].push(i);
    }
    const labels = ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'];
    const suhuMax = [], suhuMin = [], hujan = [], kelembaban = [];

    minggu.forEach(idx => {
      if (idx.length === 0) return;
      suhuMax.push(Math.round(avg(idx.map(i => daily.temperature_2m_max[i]))));
      suhuMin.push(Math.round(avg(idx.map(i => daily.temperature_2m_min[i]))));
      hujan.push(Math.round(idx.reduce((s, i) => s + (daily.precipitation_sum[i] || 0), 0)));
      kelembaban.push(Math.round(avg(idx.map(i => daily.relative_humidity_2m_mean[i]))));
    });

    return { labels, suhuMax, suhuMin, hujan, kelembaban };
  }

  function olahBulanan(daily) {
    if (!daily || !daily.time) return { labels: [], suhuMax: [], suhuMin: [], hujan: [], kelembaban: [] };

    const bulanMap = {};
    const namaBulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];

    daily.time.forEach((t, i) => {
      const d = new Date(t);
      const key = d.getFullYear() + '-' + d.getMonth();
      if (!bulanMap[key]) bulanMap[key] = { nama: namaBulan[d.getMonth()], tMax: [], tMin: [], hujan: [], rh: [] };
      bulanMap[key].tMax.push(daily.temperature_2m_max[i]);
      bulanMap[key].tMin.push(daily.temperature_2m_min[i]);
      bulanMap[key].hujan.push(daily.precipitation_sum[i] || 0);
      bulanMap[key].rh.push(daily.relative_humidity_2m_mean[i]);
    });

    const keys = Object.keys(bulanMap).slice(-12);
    return {
      labels: keys.map(k => bulanMap[k].nama),
      suhuMax: keys.map(k => Math.round(avg(bulanMap[k].tMax))),
      suhuMin: keys.map(k => Math.round(avg(bulanMap[k].tMin))),
      hujan: keys.map(k => Math.round(bulanMap[k].hujan.reduce((a, b) => a + b, 0))),
      kelembaban: keys.map(k => Math.round(avg(bulanMap[k].rh))),
    };
  }

  function avg(arr) {
    const valid = arr.filter(v => v !== null && v !== undefined);
    return valid.length ? valid.reduce((a, b) => a + b, 0) / valid.length : 0;
  }

  // =====================
  // RENDER UI
  // =====================

  function renderGrafikKonten() {
    const d = grafikDataCache[grafikTabAktif];
    const suhuRataMax = Math.round(avg(d.suhuMax));
    const suhuRataMin = Math.round(avg(d.suhuMin));
    const totalHujan = d.hujan.reduce((a, b) => a + b, 0).toFixed(1);
    const rhRata = Math.round(avg(d.kelembaban));

    document.getElementById('grafikKonten').innerHTML = `
      <span class="grafik-kota-label">📍 ${grafikNamaKota}</span>

      <div class="grafik-metrics" id="grafikMetrics">
        <div class="grafik-metric">
          <div class="grafik-metric-icon">🌡️</div>
          <div class="grafik-metric-label">Suhu Maks</div>
          <div class="grafik-metric-value">${suhuRataMax}°C</div>
        </div>
        <div class="grafik-metric">
          <div class="grafik-metric-icon">🌡️</div>
          <div class="grafik-metric-label">Suhu Min</div>
          <div class="grafik-metric-value">${suhuRataMin}°C</div>
        </div>
        <div class="grafik-metric">
          <div class="grafik-metric-icon">🌧️</div>
          <div class="grafik-metric-label">Total Hujan</div>
          <div class="grafik-metric-value">${totalHujan} mm</div>
        </div>
        <div class="grafik-metric">
          <div class="grafik-metric-icon">💧</div>
          <div class="grafik-metric-label">Kelembaban</div>
          <div class="grafik-metric-value">${rhRata}%</div>
        </div>
      </div>

      <div class="grafik-tabs">
        <button class="grafik-tab ${grafikTabAktif === 'harian' ? 'aktif' : ''}"
          onclick="grafikGantiTab('harian', this)">Harian</button>
        <button class="grafik-tab ${grafikTabAktif === 'mingguan' ? 'aktif' : ''}"
          onclick="grafikGantiTab('mingguan', this)">Mingguan</button>
        <button class="grafik-tab ${grafikTabAktif === 'bulanan' ? 'aktif' : ''}"
          onclick="grafikGantiTab('bulanan', this)">Bulanan</button>
      </div>

      <div class="grafik-canvas-wrap">
        <canvas id="grafikCanvas" role="img"
          aria-label="Grafik suhu dan curah hujan ${grafikNamaKota}">
        </canvas>
      </div>

      <div class="grafik-legend">
        <span class="grafik-legend-item">
          <span class="grafik-legend-dot" style="background:#0ea5e9;"></span> Suhu Maks (°C)
        </span>
        <span class="grafik-legend-item">
          <span class="grafik-legend-dot" style="background:#38bdf8;opacity:0.7;"></span> Suhu Min (°C)
        </span>
        <span class="grafik-legend-item">
          <span class="grafik-legend-dot" style="background:#1d9e75;"></span> Curah Hujan (mm)
        </span>
        <span class="grafik-legend-item">
          <span class="grafik-legend-dot" style="background:#f59e0b;opacity:0.8;"></span> Kelembaban (%)
        </span>
      </div>

      <p style="font-size:0.72rem;color:#94a3b8;margin-top:1rem;text-align:center;">
        Sumber: Open-Meteo · Data forecast & historis · Diperbarui otomatis
      </p>
    `;

    renderChart();
  }

  window.grafikGantiTab = function(tab, el) {
    grafikTabAktif = tab;
    document.querySelectorAll('.grafik-tab').forEach(b => b.classList.remove('aktif'));
    el.classList.add('aktif');

    const d = grafikDataCache[tab];
    const suhuRataMax = Math.round(avg(d.suhuMax));
    const suhuRataMin = Math.round(avg(d.suhuMin));
    const totalHujan = d.hujan.reduce((a, b) => a + b, 0).toFixed(1);
    const rhRata = Math.round(avg(d.kelembaban));

    document.getElementById('grafikMetrics').innerHTML = `
      <div class="grafik-metric">
        <div class="grafik-metric-icon">🌡️</div>
        <div class="grafik-metric-label">Suhu Maks</div>
        <div class="grafik-metric-value">${suhuRataMax}°C</div>
      </div>
      <div class="grafik-metric">
        <div class="grafik-metric-icon">🌡️</div>
        <div class="grafik-metric-label">Suhu Min</div>
        <div class="grafik-metric-value">${suhuRataMin}°C</div>
      </div>
      <div class="grafik-metric">
        <div class="grafik-metric-icon">🌧️</div>
        <div class="grafik-metric-label">Total Hujan</div>
        <div class="grafik-metric-value">${totalHujan} mm</div>
      </div>
      <div class="grafik-metric">
        <div class="grafik-metric-icon">💧</div>
        <div class="grafik-metric-label">Kelembaban</div>
        <div class="grafik-metric-value">${rhRata}%</div>
      </div>
    `;

    renderChart();
  };

  function renderChart() {
    const d = grafikDataCache[grafikTabAktif];
    const ctx = document.getElementById('grafikCanvas');
    if (!ctx) return;

    if (grafikChart) { grafikChart.destroy(); grafikChart = null; }

    grafikChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: d.labels,
        datasets: [
          {
            label: 'Suhu Maks (°C)',
            data: d.suhuMax,
            type: 'line',
            borderColor: '#0ea5e9',
            backgroundColor: 'rgba(14,165,233,0.10)',
            pointBackgroundColor: '#0ea5e9',
            borderWidth: 2,
            pointRadius: 4,
            tension: 0.35,
            yAxisID: 'yTemp',
            fill: false,
            order: 1
          },
          {
            label: 'Suhu Min (°C)',
            data: d.suhuMin,
            type: 'line',
            borderColor: '#38bdf8',
            backgroundColor: 'rgba(56,189,248,0.08)',
            pointBackgroundColor: '#38bdf8',
            borderWidth: 1.5,
            pointRadius: 3,
            borderDash: [4, 3],
            tension: 0.35,
            yAxisID: 'yTemp',
            fill: false,
            order: 1
          },
          {
            label: 'Curah Hujan (mm)',
            data: d.hujan,
            type: 'bar',
            backgroundColor: 'rgba(29,158,117,0.65)',
            borderColor: '#1d9e75',
            borderWidth: 0,
            borderRadius: 4,
            yAxisID: 'yHujan',
            order: 2
          },
          {
            label: 'Kelembaban (%)',
            data: d.kelembaban,
            type: 'line',
            borderColor: '#f59e0b',
            backgroundColor: 'transparent',
            pointBackgroundColor: '#f59e0b',
            borderWidth: 1.5,
            borderDash: [3, 3],
            pointRadius: 3,
            tension: 0.3,
            yAxisID: 'yTemp',
            fill: false,
            order: 0
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: {
          legend: { display: false },
          tooltip: {
            callbacks: {
              label: function(ctx) {
                const label = ctx.dataset.label || '';
                if (label.includes('Hujan')) return ' ' + label + ': ' + ctx.parsed.y + ' mm';
                if (label.includes('Kelembaban')) return ' ' + label + ': ' + ctx.parsed.y + '%';
                return ' ' + label + ': ' + ctx.parsed.y + '°C';
              }
            }
          }
        },
        scales: {
          yTemp: {
            type: 'linear',
            position: 'left',
            min: 0,
            max: 100,
            ticks: {
              font: { size: 11 },
              color: '#94a3b8',
              callback: v => v <= 50 ? v + '°' : v + '%'
            },
            grid: { color: 'rgba(148,163,184,0.15)' }
          },
          yHujan: {
            type: 'linear',
            position: 'right',
            min: 0,
            ticks: {
              font: { size: 11 },
              color: '#1d9e75',
              callback: v => v + ' mm'
            },
            grid: { drawOnChartArea: false }
          },
          x: {
            ticks: {
              font: { size: 11 },
              color: '#94a3b8',
              autoSkip: false
            },
            grid: { color: 'rgba(148,163,184,0.08)' }
          }
        }
      }
    });
  }

})();
</script>