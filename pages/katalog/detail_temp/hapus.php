<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'];
$delete = $db->query("DELETE FROM `katalog_detail_temp` WHERE `id` = '$id'");
if ($delete) {
    echo "DELETE FROM `katalog_detail_temp` WHERE `id` = '$id'";
    echo "success";
}else{
    echo "DELETE FROM `katalog_detail_temp` WHERE `id` = '$id'";
}