<?php
session_start();
$_SESSION['last_url'] = $_SERVER[REQUEST_URI];
?>
<?php include $_SERVER['DOCUMENT_ROOT'].'/components/header/index.php'; 
if(!isset($_SESSION["username"])){
    echo "<script>window.location.href = '/pages/login.php'</script>";
}
include $_SERVER['DOCUMENT_ROOT'].'/components/footer/index.php';
include $_SERVER['DOCUMENT_ROOT'].'/components/stock/history.php';
include $_SERVER['DOCUMENT_ROOT'].'/components/footer/index.php'; ?>