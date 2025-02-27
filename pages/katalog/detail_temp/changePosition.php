<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$jsonData = file_get_contents("php://input");
$data = json_decode($jsonData, true);
$idTemp = $data['idTemp'];
$oldPosition = $data['oldPosition'];
$idNow = $data['idNow'];
$userId = $data['userId'];
$updatePosition = $db->query("UPDATE `katalog_detail_temp` SET `urutan` = '$oldPosition' WHERE `user_id` = '$userId' AND `urutan` = '$idNow'");
$updatePosition = $db->query("UPDATE `katalog_detail_temp` SET `urutan` = '$idNow' WHERE `id` = '$idTemp'");

if ($updatePosition) {
    echo json_encode("UPDATE `katalog_detail_temp` SET `urutan` = '$oldPosition' WHERE `user_id` = '$userId' AND `urutan` = '$idNow'");
} 