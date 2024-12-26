<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$productId = $_POST['productId'];
// $productId = 156;
$stocksTemp = $db->query("SELECT `log_stock`.id_produk,`log_stock`.harga,`log_stock`.tanggal_beli,`produk`.nama,`log_stock`.is_verif FROM `log_stock` LEFT JOIN `foto` ON `foto`.`id_produk` = `log_stock`.id_produk AND `foto`.`is_cover` = 1 AND `foto`.`is_active` = 1 JOIN `produk` ON `produk`.id = `log_stock`.id_produk WHERE `log_stock`.`id_produk` = '$productId' ORDER BY `log_stock`.id DESC");
$hargas_jual = $db->query("SELECT * FROM `log_harga_jual` WHERE `id_produk` = '$productId'");
// var_dump($stocksTemp->fetch_all());
$datas = [];
foreach ($stocksTemp as $key => $stocks) {
    $datas["belis"][] = $stocks; 
}
foreach ($hargas_jual as $key => $harga_jual) {
    $datas["juals"][] = $harga_jual; 
}
// var_dump($datas);
echo json_encode($datas);