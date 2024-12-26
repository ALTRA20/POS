<?php
function exc() {
    $host = 'localhost';
    $username = 'root';
    $password = 'root';
    $database = 'catalog';
    
    // Ekspor nama file
    $dir = $_SERVER["DOCUMENT_ROOT"].'/public/bc/'.date("Y-m-d").'/';
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    $backup_file = $dir.'backup_' . $database . '_' . date("H:i:s") . '.sql';
    
    // Ekspor database
    exec('mysqldump --host=' . $host . ' --user=' . $username . ' --password=' . $password . ' ' . $database . ' > ' . $backup_file);
}
?>