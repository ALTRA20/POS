<?php
function uploadFile($file, $name, $directory, $ekstensi, $nextId) {
    $allowedExtensions = '';
    if ($ekstensi == 'gambar') {
        $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
    }else if ($ekstensi == 'audio'){
        $allowedExtensions = array('mp3', 'wav', 'ogg', 'mpeg', 'aac', 'mp4', 'mpeg');
    }else if ($ekstensi == 'video'){
        $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif', 'mp4', 'avi', 'mov');
    }else{
        $allowedExtensions = array('all');
    }
    $maxSize = 2000 * 1024 * 1024; // 100 MB dalam byte
    $fileInfo = $file[$name];
    $ext = pathinfo($fileInfo['name'], PATHINFO_EXTENSION);
    $size = $fileInfo['size'];
    $tmpName = $fileInfo['tmp_name'];
    if (in_array('all', $allowedExtensions) || in_array($ext, $allowedExtensions) && $size < $maxSize) {
        $today = date('Y-m-d');
        $destinationDir = $_SERVER['DOCUMENT_ROOT'] . '/public/' . $directory;
        $file_path = $destinationDir . $nextId;
        // var_dump($file_path);
        // die();
        if (file_exists($file_path)) {
            return 'is_available';
        }
        if (!is_dir($destinationDir)) {
            if (!mkdir($destinationDir, 0755, true)) {
                throw new Exception('Failed to create destination directory.');
            }
        }        
        $destinationPath = $file_path;
        
        if (move_uploaded_file($tmpName, $destinationPath)) {
            // var_dump($destinationPath);
            // die();
            return '/public/' . $directory . $fileInfo['name'] ; // Berhasil mengunggah
        } else {
            throw new Exception('Gagal mengunggah file.');
        }
    } else {
        $message = 'Ekstensi atau ukuran file tidak sesuai persyaratan.';
        echo '<script type="text/javascript">alert("' . $message . '");</script>';
        return null;
    }
}
?>