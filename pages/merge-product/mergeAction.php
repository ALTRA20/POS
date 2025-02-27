<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$jsonData = file_get_contents("php://input");
$data = json_decode($jsonData, true);
$id1 = $data['id1'];
$id2 = $data['id2'];

$log_harga_jual = $db->query("UPDATE `log_harga_jual` SET `id_produk` = '$id1' WHERE `id_produk` = '$id2'");
$log_stock_temp = $db->query("UPDATE `log_stock_temp` SET `id_produk` = '$id1' WHERE `id_produk` = '$id2'");
$update_pesanan_detail = $db->query("UPDATE `pesanan_detail` SET `produk_id` = '$id1' WHERE `produk_id` = '$id2'");

$deactive = $db->query("UPDATE `produk` SET `is_active` = 0 WHERE `id` = '$id2'");

$namaProduk = $db->query("SELECT `nama` FROM `produk` WHERE `id` = '$id2'")->fetch_assoc()['nama'];
echo $namaProduk;