<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
include $_SERVER['DOCUMENT_ROOT'].'/function/exc.php';

$data = json_decode(file_get_contents("php://input"), true);
$idPesananDetail = $data['idPesananDetail'];
$idProdukBaru = $data['idProdukBaru'];
$jumlahPesananDetail = $data['jumlahPesananDetail'];
$harga = $data['harga'];
$totalHarga = $harga * intval($jumlahPesananDetail);
$idPesanan = $db->query("SELECT `pesanan_id` FROM `pesanan_detail` WHERE `id` = '$idPesananDetail'")->fetch_assoc()['pesanan_id'];

$updateDetail = $db->query("UPDATE `pesanan_detail` SET `produk_id`='$idProdukBaru',`harga_jual`='$harga',`jumlah`='$jumlahPesananDetail' WHERE `id` = '$idPesananDetail'");
$updatePesanan = $db->query("UPDATE `pesanan` SET `nominal_pesanan` = '$totalHarga' WHERE `id` = '$idPesanan'");
exc();
echo $idPesananDetail;
?>