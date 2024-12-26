<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$data = json_decode(file_get_contents("php://input"), true);

$id_tr = $data['id_tr'];
$bank = $data['bank'];
$transfers = $data['transfers'];
$tabelBank = 'tr_'.$bank;
if ($bank != "split") {
    $is_split = $db->query("UPDATE $tabelBank SET `split` = 1 WHERE `id` = '$id_tr'");
    foreach ($transfers as $key => $transfer) {
        $insert = $db->query("INSERT INTO `tr_split`(`nama_bank`, `tr_id`, `nominal`, `created_at`) VALUES ('$bank','$id_tr','$transfer',CURRENT_TIMESTAMP())");
    }
}else{
    $bank = $db->query("SELECT * FROM `tr_split` WHERE `id` = '$id_tr'")->fetch_assoc();
    $nama_bank = $bank['nama_bank'];
    $tr_id = $bank['tr_id'];
    $delete = $db->query("DELETE FROM `tr_split` WHERE `id` = '$id_tr'");
    foreach ($transfers as $key => $transfer) {
        $insert = $db->query("INSERT INTO `tr_split`(`nama_bank`, `tr_id`, `nominal`, `created_at`) VALUES ('$nama_bank','$tr_id','$transfer',CURRENT_TIMESTAMP())");
    }
}
echo "INSERT INTO `tr_split`(`nama_bank`, `tr_id`, `nominal`, `created_at`) VALUES ('$nama_bank','$tr_id','$transfer',CURRENT_TIMESTAMP())"
?>