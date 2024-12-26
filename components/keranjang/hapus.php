<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'];
$delete = $db->query("DELETE FROM `keranjang` WHERE `id` = '$id'");
if ($delete) {
    echo "success";
}else{
    echo "failed";
}