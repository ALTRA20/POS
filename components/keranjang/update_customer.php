<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
include $_SERVER['DOCUMENT_ROOT'].'/function/exc.php';

$datas = json_decode(file_get_contents("php://input"), true);
$idCustomer = $datas['idCustomer'];
$userId = $datas['userId'];
$ids = $datas['ids'];
if (count($ids) > 0) {
    $ids = implode(',', $ids);
    $sql = "UPDATE `keranjang` SET `id_customer` = '$idCustomer' WHERE  `id` IN ($ids)";
    $sql = $db->query($sql);
    exc();
    # code...
}else{
    $insert = $db->query("INSERT INTO `keranjang` (`user_id`,`id_customer`) VALUES ('$userId','$idCustomer')");
}
echo json_encode("INSERT INTO `keranjang` (`user_id`,`id_customer`) VALUES ('$userId','$idCustomer')");