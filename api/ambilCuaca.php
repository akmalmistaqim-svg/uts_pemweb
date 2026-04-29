<?php
// Memberitahu browser bahwa response ini berformat JSON
header('Content-Type: application/json');

$apikey = "f7652f135c9a5c247ef630ab130d60e8";

$kota = isset($_GET['kota']) ? urlencode($_GET['kota']) : '';

if (empty($kota)) {
    echo json_encode(["error" => "Kota tidak boleh kosong"]);
    exit;
}

$url = "https://api.openweathermap.org/data/2.5/weather?q={$kota},ID&appid={$apikey}&units=metric&lang=id";

$response = @file_get_contents($url);

if ($response === FALSE) {
    echo json_encode(["error" => "Gagal mengambil data cuaca"]);
    exit;
}

$data = json_decode($response, true);

if ($data['cod'] != 200) {
    echo json_encode(["error" => "Kota tidak ditemukan"]);
    exit;
}

// Kembalikan seluruh data cuaca mentah ke client 
echo json_encode($data);
?>