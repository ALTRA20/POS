<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';

$data = json_decode(file_get_contents("php://input"), true);
$productId = $data['productId'];

$hargaJuals = [];

$produkId = $product['id'];
$logHargaJualQuery = $db->query("SELECT *, DATE(`created_at`) AS tanggal FROM `log_harga_jual` WHERE `id_produk` = '$productId' ORDER BY `created_at` DESC");

while ($log_harga_jual = $logHargaJualQuery->fetch_assoc()) {
    $harga = $log_harga_jual['harga_jual'];
    $tanggal = $log_harga_jual['tanggal'];
    $hargaJuals[] = ['tanggal' => $tanggal, 'harga' => $harga];
}
// echo $sql;
echo json_encode($hargaJuals);