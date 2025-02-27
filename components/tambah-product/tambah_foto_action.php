<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
include $_SERVER['DOCUMENT_ROOT'].'/function/resize.php';
include $_SERVER['DOCUMENT_ROOT'].'/function/copyFile.php'; // Jika diperlukan
$id_user = $_POST['id_user'];
$id_produk = $_POST['id_produk'];
$judul = $_POST['judul'];

// Mendapatkan ID foto berikutnya
$nextId = $db->query("SELECT `id` FROM `foto` ORDER BY `id` DESC LIMIT 1")->fetch_assoc()['id'] + 1;

// Upload file foto
$file = uploadFile($_FILES, 'foto', 'foto/lg/', 'gambar', $nextId.'.jpg');
if ($file) {
    $sourceImagePath = $_SERVER['DOCUMENT_ROOT'].'/public/foto/lg/'.$nextId.'.jpg'; // Menggunakan $nextId
    resize($sourceImagePath);
    // Buat objek Imagick
    $image = new Imagick($sourceImagePath);
    
    // Resize gambar
    $image->resizeImage(200, 200, Imagick::FILTER_LANCZOS, 1);
    
    // Simpan gambar yang diresize
    $imgSizeMd = $_SERVER['DOCUMENT_ROOT'].'/public/foto/md/'.$nextId.'.jpg'; // Menggunakan $nextId
    $image->writeImage($imgSizeMd);
    $image->destroy();
    // Insert data foto ke dalam database
    $is_cover = 0;
    $is_available = $db->query("SELECT * FROM `foto` WHERE `id_produk` = '$id_produk'")->num_rows > 0;
    if (!$is_available) {
        $is_cover = 1;
    }
    $insert = $db->query("INSERT INTO `foto`(`is_cover`, `id_user`, `id_produk`, `created_at`) VALUES ('$is_cover','$id_user','$id_produk',CURRENT_TIMESTAMP())");
    
    if ($insert) {
        echo 'success';
    } else {
        echo 'gagal';
    }
    // die();
} else {
    echo "<script>alert('file sudah pernah diinput masukkan file lain')</script>";
}
// Redirect ke halaman tertentu setelah proses selesai
echo "<script>window.location.href = '/pages/?ikan=$judul'</script>";
?>
