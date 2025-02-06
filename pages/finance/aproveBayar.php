<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$userId = $_POST['userId'];
$jabatan = $db->query("SELECT * FROM `user` WHERE `id` = '$userId'")->fetch_assoc()['jabatan'];
if($jabatan == 'finance' || $jabatan == 'super-admin'){
    $edit = $_POST['edit'];
    $bank = $_POST['bank'];
    $tabel = 'tr_'.$bank;
    $bayarId = $_POST['bayarId'];
    $idPesananBank = $_POST['idPesananBank'];
    $kode_bayar = $_POST['kode_bayar'];
    $tr_id = $_POST['tr_id'];
    if ($edit == '1') {
        $kode_bayar_lama = $db->query("SELECT * FROM `$tabel` WHERE `id_bayar` = '$bayarId'")->fetch_assoc()['kode_bayar'];
        $hapusDuitMasuk = $db->query("DELETE FROM `tr_duit_masuk` WHERE `kode_bayar` = '$kode_bayar_lama'");
        $updateTransferBank = $db->query("UPDATE `$tabel` SET `id_bayar` = NULL WHERE `id_bayar` = '$bayarId'");
    }
    $nominalBayar = $db->query("SELECT * FROM $tabel WHERE `id` = '$tr_id'")->fetch_assoc()['duit_in'];
    $updateLevel = $db->query("UPDATE `pesanan` SET `level` = '3' WHERE `id` = '$idPesananBank'");
    $aproveBayar = $db->query("INSERT INTO `tr_duit_masuk`(`kode_bayar`,`user_id`, `nominal`, `bayar_id`, `created_at`) VALUES ('$kode_bayar','$userId','$nominalBayar','$bayarId',CURRENT_TIMESTAMP())");
    $updateIdBayarTr = $db->query("UPDATE `$tabel` SET `id_bayar`='$bayarId' WHERE `id` = '$tr_id'");
}
$page = $_POST['page'];
if ($page == 'history') {
    echo "<script>window.location.href = '/pages/history/'</script>";
}else{
    echo "<script>window.location.href = '/pages/finance/index.php?s=sudah'</script>";
}
?>