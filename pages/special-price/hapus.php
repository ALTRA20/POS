<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';

$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'];
$insert = $db->query("DELETE FROM `special_price` WHERE `id` = '$id'");

if($insert){
    echo json_encode(array('status' => 'success'));
}else{
    echo json_encode(array('status' => 'failed'));
}
