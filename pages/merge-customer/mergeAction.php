<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$jsonData = file_get_contents("php://input");
$data = json_decode($jsonData, true);
$id1 = $data['id1'];
$id2 = $data['id2'];

$pesanan = $db->query("UPDATE `pesanan` SET `customer_id` = '$id1' WHERE `customer_id` = '$id2'");
$namaProduk = $db->query("SELECT `nama` FROM `customer` WHERE `id` = '$id2'")->fetch_assoc()['nama'];


$deactive_user = $db->query("DELETE FROM `customer` WHERE `id` = '$id2'");
echo $namaProduk;