<?php include $_SERVER['DOCUMENT_ROOT'].'/function/qr/qrlib.php';?>
<?php include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';?>
<?php 
include $_SERVER['DOCUMENT_ROOT'].'/function/exc.php';
if (isset($_POST["bayarNow"])) {
    session_start();
    $username = $_SESSION["username"];
    $user = $db->query("SELECT * FROM `user` WHERE `username` = '$username'")->fetch_assoc();
    $userId = $user['id'];

    $nominalBayar = (isset($_POST["nominalHarusDibayar"])) ? $_POST["nominalHarusDibayar"] : $_POST["nominalBayar"];
    $tanggalBayar = $_POST["tanggalBayar"];
    $tanggalBawa = $_POST["tanggalBawa"];
    $bayar = $_POST["bayar"];
    $bawa = $_POST["bawa"];
    $id = $_POST["i"];
    $isQuick = $_POST["isQuick"];
    $is_verif = ($isQuick == 1) ? 1 : 0;
    $level = (isset($_POST["qrProduk"])) ? '25' : '2';

    $date = date("Y-m-d");
    $date = md5($date);
    $link = "192.168.1.100/pages/nota/scan/?jb=".$id;
    $dir = $_SERVER["DOCUMENT_ROOT"]."/public/nota/".$id.".png";
    QRcode::png($link,$dir,"H",4,4);
    if($tanggalBawa == ""){
        $insertBayar = $db->query("INSERT INTO `bayar`(`pesanan_id`, `jalur`, `nominal_bayar`, `caraBawa`, `created_at`, `is_verifikasi`,`tanggal_bawa`) VALUES ('$id','$bayar','$nominalBayar','$bawa','$tanggalBayar','$is_verif',NULL)");
    }else{
        $insertBayar = $db->query("INSERT INTO `bayar`(`pesanan_id`, `jalur`, `nominal_bayar`, `caraBawa`, `created_at`, `is_verifikasi`,`tanggal_bawa`) VALUES ('$id','$bayar','$nominalBayar','$bawa','$tanggalBayar','$is_verif','$tanggalBawa')");
    }
    if ($insertBayar) {
        $updateLevel = $db->query("UPDATE `pesanan` SET `level` = '$level' WHERE `id` = '$id'");
        if (isset($_POST["nominalHarusDibayar"])) {
            $membayar = $_POST["nominalBayar"];
            $update_nominal_membayar = $db->query("UPDATE `pesanan` SET `nominal_membayar` = '$membayar' WHERE `id` = '$id'");
        }
        if ($isQuick == 1) {
            $bayar_id = $db->query("SELECT `id` FROM `bayar` WHERE `pesanan_id` = '$id'")->fetch_assoc()['id'];
            $insertDuitMasuk = $db->query("INSERT INTO `duit_masuk`(`kode_bayar`, `user_id`, `nominal`, `bayar_id`, `created_at`) VALUES (null,'$userId','$nominalBayar','$bayar_id',CURRENT_TIMESTAMP())");

            $updateLevel = $db->query("UPDATE `pesanan` SET `level` = '2' WHERE `id` = '$id'");
        }
        exc();
        $date = date("Y-m-d");
        $verif = md5($date);
        // echo "<script>window.location.href = '/pages/nota/?i=".rand(10,999)."&d=$id&f=$verif'</script>";
        if (isset($_POST["qrProduk"])) {
            $date = date("Y-m-d");
            $date = md5($date);
            $rand = rand(10,999);
            $bc = $_POST["bc"];
            $idPesanan = $_POST["idPesanan"];
            $pesanan_detail = $db->query("SELECT * FROM `pesanan_detail` WHERE `pesanan_id` = '$idPesanan'");
            foreach ($pesanan_detail as $key => $pd) {
                $produk_id = $pd['produk_id'];
                $jumlah = $pd['jumlah'];
                $qr_stock = intVal($db->query("SELECT `qr_stock` FROM `produk` WHERE `id` = '$produk_id'")->fetch_assoc()['qr_stock']) - $jumlah;

                $update = $db->query("UPDATE `produk` SET `qr_stock`='$qr_stock' WHERE `id` = '$produk_id'");
            }
            $bayar_id = $db->query("SELECT `id` FROM `bayar` WHERE `pesanan_id` = '$id'")->fetch_assoc()['id'];
            $insertKasir = $db->query("INSERT INTO `tr_duit_masuk_kasir`(`kode_bayar`, `user_id`, `nominal`, `bayar_id`, `created_at`) VALUES (null,'$userId','$nominalBayar','$bayar_id',CURRENT_TIMESTAMP())");
            echo "<script>window.location.href = '/pages/nota/?f=$date&i=$date&d=$id&bc=$bc'</script>";
        }
        echo "<script>window.location.href = '/pages/nota/?f=$date&i=$date&d=$id'</script>";
    }
}
?>