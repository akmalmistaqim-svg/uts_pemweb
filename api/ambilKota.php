<?php
header('Content-Type: application/json');

$url = "https://webapi.bps.go.id/v1/api/domain/type/all/prov/35/key/8100aa782b00c8674a151309454e0901";

$response = file_get_contents($url);

if ($response === FALSE) {
    echo json_encode(["error" => "Gagal mengambil data dari BPS"]);
    exit;
}

// decode JSON
$data = json_decode($response, true);

// ambil isi data (struktur: data[1])
$list = $data['data'][1];

// filter hanya Jawa Timur (kode 35)
$jatim = array_filter($list, function($item) {
    return strpos($item['domain_id'], '35') === 0;
});

// reset index array biar rapi
$jatim = array_values($jatim);

// kirim hasil
echo json_encode($jatim);
?>