<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$data = json_decode(file_get_contents("php://input"), true);
$userId = $data['userId'];
$stocksTemp = $db->query("SELECT `log_stock_temp`.*, `produk`.nama, `produk`.id AS produkId, `foto`.id AS fotoId FROM `log_stock_temp` LEFT JOIN `foto` ON `foto`.`id_produk` = `log_stock_temp`.id_produk AND `foto`.`is_cover` = 1 AND `foto`.`is_active` = 1 JOIN `produk` ON `produk`.id = `log_stock_temp`.id_produk WHERE `log_stock_temp`.`user_id` = '$userId' ORDER BY `log_stock_temp`.id ASC");
echo json_encode($stocksTemp->fetch_all());