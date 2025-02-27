<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'];
$result = $db->query("DELETE FROM `log_stock_temp` WHERE `id` = '$id'");