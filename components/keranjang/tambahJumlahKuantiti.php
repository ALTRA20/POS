<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';

$data = json_decode(file_get_contents("php://input"), true);
$jumlahBarang = $data['jumlahBarang'];
$userId = $data['idUser'];
$product_id = $data['idProduk'];

$update = $db->query("UPDATE `keranjang` SET `jumlah`='$jumlahBarang' WHERE `user_id` = '$userId' AND `product_id` = '$product_id'");
?>
