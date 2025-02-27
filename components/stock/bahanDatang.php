<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$data = json_decode(file_get_contents("php://input"), true);
$whereFix = '';
$wheres = $data['where'];
$wheres = explode(' ', $wheres);
foreach ($wheres as $key => $where) {
    if ($key != 0) {
        $whereFix .= " AND";
    }
    $whereFix .= "`produk`.nama LIKE '%$where%'";
}
$result = $db->query("SELECT `produk`.*,`foto`.`id` AS idFoto FROM `produk` LEFT JOIN `foto` ON `foto`.`id_produk` = `produk`.id AND `foto`.`is_cover` = 1 WHERE $whereFix AND `produk`.is_active = 1 ORDER BY `produk`.`created_at` DESC LIMIT 5");
echo json_encode($result->fetch_all());