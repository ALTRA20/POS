<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$data = json_decode(file_get_contents("php://input"), true);
$userId = $data['idUser'];
$keranjangs = $db->query("SELECT 
`keranjang`.*,
`keranjang`.foto AS customProdukFoto,
`foto`.`id` AS foto,
`customer`.`nama`,
`customer`.`alamat`,
`customer`.`wa`
FROM `keranjang` 
LEFT JOIN `foto` ON `foto`.id_produk = `keranjang`.`product_id` AND `foto`.`is_cover` = 1
LEFT JOIN `customer` ON `customer`.id = `keranjang`.`id_customer`
WHERE `keranjang`.`user_id` = '$userId' AND `keranjang`.komentar IS NOT NULL ORDER BY `keranjang`.id ASC");

$keranjang_list = [];
foreach ($keranjangs as $keranjang) {
    $keranjang_list[] = $keranjang;
}

echo json_encode($keranjang_list);