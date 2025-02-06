<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$data = json_decode(file_get_contents("php://input"), true);

$idBayar = $data['idBayar'];
$jalurBayar = $data['jalurBayar'];

$ubah = $db->query("UPDATE `bayar` SET `jalur` = '$jalurBayar' WHERE `id` = '$idBayar'");
$response = [
  "sql" => "UPDATE `bayar` SET `jalur` = '$jalurBayar' WHERE `id` = '$idBayar'"
];
echo json_encode($response);