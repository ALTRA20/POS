<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'];
$status = $data['status'];
$active = 1;
if ($status == 'delist') {
    $active = 0;
}
$update = $db->query("UPDATE `user` SET `is_active` = '$active' ,`updated_at`=CURRENT_TIMESTAMP() WHERE `id` = '$id'");
if ($update) {
   echo "success";
}