<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$data = json_decode(file_get_contents("php://input"), true);
$bank = $data['bank'];
$search = $data['search'];
$tabel = 'tr_'.$bank;
if ($bank != 'split') {
    $sql = "SELECT * FROM `$tabel` WHERE";
    if ($search != '') {
        $sql .= " `nominal` LIKE '%$search%' OR `keterangan` LIKE '%$search%' AND";
    }
    $sql .= " `split` IS NULL AND `id_bayar` IS NULL";
    $transferDatasDb = $db->query($sql);
    $transferDatas = [];
    foreach ($transferDatasDb as $key => $transferData) {
        $transferData['sql'] = $sql;
        $transferDatas [] = $transferData;
    }
}else{
    $transferDatas = [];
    $sql = "SELECT * FROM `tr_split`";
    $splits = $db->query($sql);
    foreach ($splits as $key => $split) {
        $tr_id = $split['tr_id'];
        $bank = $split['nama_bank'];
        $tr = $db->query("SELECT * FROM `tr_$bank` WHERE `id` = '$tr_id'")->fetch_assoc();
        $split['tr'] = $tr;
        $split['sql'] = "SELECT * FROM `tr_$bank` WHERE `id` = '$tr_id'";
        $transferDatas[] = $split;
    }
}
echo json_encode($transferDatas);
?>