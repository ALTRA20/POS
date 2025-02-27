<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$stocksTemp = $db->query("SELECT * FROM `fotoTemp` ORDER BY `id` DESC LIMIT 10");
echo json_encode($stocksTemp->fetch_all());