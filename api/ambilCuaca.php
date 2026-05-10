<?php
header('Content-Type: application/json');

$kota = isset($_GET['kota']) ? trim($_GET['kota']) : '';
$tanggal = isset($_GET['tanggal']) ? trim($_GET['tanggal']) : '';

if (empty($kota)) {
    echo json_encode(["error" => "Kota tidak boleh kosong"]);
    exit;
}

// Step 1: Geocoding — cari koordinat dari nama kota
$geoUrl = "https://geocoding-api.open-meteo.com/v1/search?name=" . urlencode($kota) . "&count=1&language=id&countryCode=ID";
$geoResponse = @file_get_contents($geoUrl);

if ($geoResponse === FALSE) {
    echo json_encode(["error" => "Gagal mengambil koordinat kota"]);
    exit;
}

$geoData = json_decode($geoResponse, true);

if (empty($geoData['results'])) {
    echo json_encode(["error" => "Kota tidak ditemukan"]);
    exit;
}

$lat = $geoData['results'][0]['latitude'];
$lon = $geoData['results'][0]['longitude'];

// Step 2: Ambil cuaca — current + forecast 16 hari
$cuacaUrl = "https://api.open-meteo.com/v1/forecast"
    . "?latitude={$lat}&longitude={$lon}"
    . "&current=temperature_2m,relative_humidity_2m,apparent_temperature,weather_code,wind_speed_10m,surface_pressure"
    . "&daily=temperature_2m_max,temperature_2m_min,precipitation_probability_max,weather_code"
    . "&timezone=Asia%2FJakarta"
    . "&forecast_days=16";

$cuacaResponse = @file_get_contents($cuacaUrl);

if ($cuacaResponse === FALSE) {
    echo json_encode(["error" => "Gagal mengambil data cuaca"]);
    exit;
}

$cuacaData = json_decode($cuacaResponse, true);

// Step 3: Kalau ada tanggal, cari data hari itu dari daily forecast
if (!empty($tanggal)) {
    $dailyDates = $cuacaData['daily']['time'];
    $idx = array_search($tanggal, $dailyDates);

    if ($idx !== false) {
        // Kembalikan data hari yang dipilih
        echo json_encode([
            "source" => "open-meteo",
            "mode"   => "forecast",
            "lat"    => $lat,
            "lon"    => $lon,
            "daily_index" => $idx,
            "data"   => $cuacaData
        ]);
    } else {
        // Tanggal di luar jangkauan 16 hari, pakai current
        echo json_encode([
            "source" => "open-meteo",
            "mode"   => "current",
            "lat"    => $lat,
            "lon"    => $lon,
            "data"   => $cuacaData
        ]);
    }
} else {
    // Tidak ada tanggal, pakai current
    echo json_encode([
        "source" => "open-meteo",
        "mode"   => "current",
        "lat"    => $lat,
        "lon"    => $lon,
        "data"   => $cuacaData
    ]);
}
?>