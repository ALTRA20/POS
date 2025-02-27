<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
$data = json_decode(file_get_contents("php://input"), true);



function choseAlphabet($number) {
  $huruf = ['Q','A', 'B', 'C', 'D', 'E', 'F', 'G', 'H','K'];
  
  // Pastikan $number adalah angka valid
  return $huruf[$number];
}

// Fungsi untuk menghasilkan kode bayar unik
function generateUniqueKodeBayar($db, $prefix = "W") {
  // Ambil ID terakhir dari tabel menggunakan mysql_insert_id
  $id_terakhir = mysqli_insert_id($db);

  // Ubah $id_terakhir menjadi array digit
  $ids_terakhir = str_split((string)$id_terakhir);
  $kodeBayar = $prefix;

  // Konversi setiap digit ke huruf
  foreach ($ids_terakhir as $angka) {
      $kodeBayar .= choseAlphabet((int)$angka);
  }
  $updateKode = $db->query("UPDATE `tr_bca` SET `kode_bayar` = '$kodeBayar' WHERE `id` = '$id_terakhir'");
  return $kodeBayar;
}

$keterangan = $data['keterangan'];
$nominal = explode('.', $data['nominal'])[0];
$tanggal = $data['tanggal'];
$alasan = $data['alasan'];
$userId = $data['userId'];

$dateNow = date("Y-m-d");
$catatan_insertmanual = $dateNow . ' | ' . $userId . ' | ' . $alasan;

$insert = $db->query("INSERT INTO `tr_bca` (`duit_in`, `keterangan`, `tanggal_transaksi`, `created_at`, `status`, `catatan_insertmanual`) VALUES ('$nominal', '$keterangan', '$tanggal', CURRENT_TIMESTAMP(), 'CR', '$catatan_insertmanual')");

$updateKode = generateUniqueKodeBayar($db);

if ($insert) {
  echo json_encode([ 
    'status' => 'success',
    'message' => 'File processed successfully!',
    'datas' => $data,
  ]);
}else{
  echo json_encode([
    'status' => 'failed',
    'message' => 'File processed failed!',
    'datas' => $data,
  ]);
}

?>