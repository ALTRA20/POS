<?php
include $_SERVER['DOCUMENT_ROOT'].'/components/header/index.php';
if ($jabatan != 'super-admin') {
    echo "<script>window.location.href = '/'</script>";
}
include $_SERVER['DOCUMENT_ROOT'].'/components/register/index.php';
include $_SERVER['DOCUMENT_ROOT'].'/components/footer/index.php'; 
?>