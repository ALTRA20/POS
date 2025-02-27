<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$data = json_decode(file_get_contents("php://input"), true);
$nama = $data['nama'];
$nomor = $data['nomor'];
$alamat = $data['alamat'];
$insert = $db->query("INSERT INTO `customer`(`nama`, `alamat`, `wa`) VALUES ('$nama','$alamat','$nomor')");
if ($insert) {
    $customers = $db->query("SELECT * FROM `customer` WHERE `nama` LIKE '%$nama%'")->fetch_all();
    echo json_encode($customers);
}
