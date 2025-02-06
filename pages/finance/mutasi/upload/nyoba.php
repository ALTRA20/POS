<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
function generateUniqueKodeBayar($db, $prefix = "W") {
  // Validasi koneksi database
  if (!$db || !($db instanceof mysqli)) {
    throw new InvalidArgumentException("Parameter \$db harus berupa koneksi mysqli yang valid.");
  }
  
  // Ambil ID terakhir dari tabel menggunakan mysqli_insert_id
  $id_terakhir = mysqli_insert_id($db);
  var_dump($id_terakhir);
  die();
  
  // Pastikan $id_terakhir valid
  if ($id_terakhir <= 0) {
    throw new RuntimeException("ID terakhir tidak valid. Pastikan operasi INSERT dilakukan sebelum memanggil fungsi ini.");
  }
  
  // Ubah $id_terakhir menjadi array digit
  $ids_terakhir = str_split((string)$id_terakhir);
  $kodeBayar = $prefix;

  // Konversi setiap digit ke huruf
  foreach ($ids_terakhir as $angka) {
      $kodeBayar .= chooseAlphabet((int)$angka);
  }

  return $kodeBayar;
}
$lastInsertedID = mysqli_insert_id($db);
var_dump($lastInsertedID);
?>