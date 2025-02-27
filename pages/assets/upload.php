<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
include $_SERVER['DOCUMENT_ROOT'].'/function/resize.php';
function resizeImage($sourcePath, $destinationPath, $newWidth = 200, $newHeight = 200) {
    // Cek tipe gambar
    $imageInfo = getimagesize($sourcePath);
    $mime = $imageInfo['mime'];
    
    switch ($mime) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg($sourcePath);
            break;
        case 'image/jpg':
            $image = imagecreatefromjpeg($sourcePath);
            break;
        case 'image/png':
            $image = imagecreatefrompng($sourcePath);
            break;
        case 'image/gif':
            $image = imagecreatefromgif($sourcePath);
            break;
        default:
            die('Format gambar tidak didukung.');
    }
    
    // Dapatkan ukuran asli gambar
    $originalWidth = imagesx($image);
    $originalHeight = imagesy($image);
    
    // Buat kanvas baru dengan ukuran yang diinginkan
    $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
    
    // Resize gambar
    imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);
    
    // Simpan gambar hasil resize
    imagejpeg($resizedImage, $destinationPath, 90);
    
    // Bersihkan memori
    imagedestroy($image);
    imagedestroy($resizedImage);
}


$uploadDirectory = $_SERVER['DOCUMENT_ROOT'] . '/public/foto/lg/';
$messages = [];
$maxFileSize = 5 * 1024 * 1024; // 5 MB (perbaikan dari 90MB)

if (!file_exists($uploadDirectory)) {
    mkdir($uploadDirectory, 0755, true);
}

$idProduk = $_POST['idProduk'] ?? null;
$idUser = $_POST['idUser'] ?? null;

foreach ($_FILES['files']['tmp_name'] as $key => $tmpName) {
    $fileName = basename($_FILES['files']['name'][$key]);
    $fileType = mime_content_type($tmpName);
    $fileSize = $_FILES['files']['size'][$key];
    
    if ($_FILES['files']['error'][$key] !== UPLOAD_ERR_OK) {
        $messages[] = "Error uploading file: {$fileName}";
        continue;
    }

    if ($fileSize > $maxFileSize) {
        $messages[] = "File terlalu besar: {$fileName}";
        continue;
    }

    // Ambil ID selanjutnya
    $idResult = $db->query("SELECT COALESCE(MAX(`id`), 0) AS max_id FROM `foto`");
    $idNext = $idResult->fetch_assoc()['max_id'] + 1;
    
    $is_video = 0;
    $extension = '';
    
    if (strpos($fileType, 'image/') === 0) {
        $extension = 'jpg'; // Simpan gambar sebagai JPG
    } elseif (strpos($fileType, 'video/') === 0) {
        $is_video = 1;
        $extension = 'mp4'; // Simpan video sebagai MP4
    } else {
        $messages[] = "Jenis file tidak didukung: {$fileName}";
        continue;
    }

    $targetFilePath = $uploadDirectory . $idNext . '.' . $extension;

    if (move_uploaded_file($tmpName, $targetFilePath)) {
        if (!$is_video) {
            $pathMd = $_SERVER['DOCUMENT_ROOT'] . '/public/foto/md/' . $idNext . '.' . $extension;
            $pathSm = $_SERVER['DOCUMENT_ROOT'] . '/public/foto/sm/' . $idNext . '.' . $extension;
            
            resizeImage($targetFilePath, $pathMd);
            resizeImage($targetFilePath, $pathSm);
        }

        $is_cover = 0;
        $is_available = $db->query("SELECT 1 FROM `foto` WHERE `id_produk` = '$idProduk' LIMIT 1")->num_rows > 0;
        if (!$is_available) {
            $is_cover = 1;
        }

        $insert = $db->query("INSERT INTO `foto` (`id`, `is_cover`, `id_user`, `id_produk`, `is_active`, `is_video`, `created_at`) 
                            VALUES ('$idNext', '$is_cover', '$idUser', '$idProduk', 1, '$is_video', NOW())");

        if ($insert) {
            $messages[] = "Upload berhasil: $fileName";
        } else {
            $messages[] = "Database error: " . $db->error;
        }
    } else {
        $messages[] = "Gagal mengunggah: $fileName";
    }
}

header('Content-Type: application/json');
echo json_encode(['message' => implode(', ', $messages)]);
?>
