<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$userId = $_POST['userId'];
$jabatan = $db->query("SELECT * FROM `user` WHERE `id` = '$userId'")->fetch_assoc()['jabatan'];
if($jabatan == 'finance' || $jabatan == 'super-admin'){
    $bayarCashId = $_POST['bayarCashId'];
    $nominalBayar = str_replace(',','',$_POST['nominal']);
    $idPesanan = $_POST['idPesanan'];
    // var_dump($userId);
    $aproveBayar = $db->query("INSERT INTO `tr_duit_masuk`(`user_id`, `nominal`, `bayar_id`, `created_at`) VALUES ('$userId','$nominalBayar','$bayarCashId',CURRENT_TIMESTAMP())");
    $updateLevel = $db->query("UPDATE `pesanan` SET `level` = '3' WHERE `id` = '$idPesanan'");
    // var_dump("UPDATE `pesanan` SET `level` = '3' WHERE `id` = '$idPesanan'");
}
echo "<script>window.location.href = '/pages/finance/index.php?s=sudah'</script>";
?>