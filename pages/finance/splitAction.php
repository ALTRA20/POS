<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$data = json_decode(file_get_contents("php://input"), true);

$id_tr = $data['id_tr'];
$bank = $data['bank'];
$transfers = $data['transfers'];
$tabelBank = 'tr_'.$bank;


function choseAlphabet($number) {
    $huruf = ['Q','A', 'B', 'C', 'D', 'E', 'F', 'G', 'H','K'];
    // Pastikan $number adalah angka valid
    return $huruf[$number];
}

// Fungsi untuk menghasilkan kode bayar unik
function generateUniqueKodeBayar($db, $prefix = "T") {
    // Ambil ID terakhir dari tabel menggunakan mysql_insert_id
    $id_terakhir = mysqli_insert_id($db);

    // Ubah $id_terakhir menjadi array digit
    $ids_terakhir = str_split((string)$id_terakhir);
    $kodeBayar = $prefix;

    // Konversi setiap digit ke huruf
    foreach ($ids_terakhir as $angka) {
        $kodeBayar .= choseAlphabet((int)$angka);
    }
    $updateKode = $db->query("UPDATE `tr_split` SET `kode_bayar` = '$kodeBayar' WHERE `id` = '$id_terakhir'");
    return $kodeBayar;
}


if ($bank != "split") {
    $is_split = $db->query("UPDATE $tabelBank SET `split` = 1 WHERE `id` = '$id_tr'");
    foreach ($transfers as $key => $transfer) {
        $insert = $db->query("INSERT INTO `tr_split`(`nama_bank`, `tr_id`, `nominal`, `created_at`) VALUES ('$bank','$id_tr','$transfer',CURRENT_TIMESTAMP())");
        $generateUniqueKodeBayar = generateUniqueKodeBayar($db);
    }
}else{
    $bank = $db->query("SELECT * FROM `tr_split` WHERE `id` = '$id_tr'")->fetch_assoc();
    $nama_bank = $bank['nama_bank'];
    $tr_id = $bank['tr_id'];
    $delete = $db->query("DELETE FROM `tr_split` WHERE `id` = '$id_tr'");
    foreach ($transfers as $key => $transfer) {
        $insert = $db->query("INSERT INTO `tr_split`(`nama_bank`, `tr_id`, `nominal`, `created_at`) VALUES ('$nama_bank','$tr_id','$transfer',CURRENT_TIMESTAMP())");
        $generateUniqueKodeBayar = generateUniqueKodeBayar($db);
    }
}
echo "INSERT INTO `tr_split`(`nama_bank`, `tr_id`, `nominal`, `created_at`) VALUES ('$nama_bank','$tr_id','$transfer',CURRENT_TIMESTAMP())"
?>