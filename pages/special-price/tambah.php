<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';

$data = json_decode(file_get_contents("php://input"), true);

$idCustomer = $data['idCustomer'];
$idProduk = $data['idProduk'];
$markup = $data['markup'];
$insert = $db->query("INSERT INTO `special_price`(`user_id`, `id_produk`, `markup`) VALUES ('$idCustomer','$idProduk','$markup')");

if($insert){
    echo json_encode(array('status' => 'success'));
}else{
    echo json_encode(array('status' => 'failed'));
}
