<?php
include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';   
if (isset($_POST['btn-login'])) {
    // Prevent SQL injection by using prepared statements
    $username = $_POST['username'];
    $password = md5($_POST['password']); // Consider using stronger encryption/hash algorithm
    // Assuming $db is your database connection object
    $sql = "SELECT * FROM `user` WHERE `username` = '$username' AND `password` = '$password'";
    $result = $db->query($sql);
    // var_dump($sql);
    // die();
    if ($result->num_rows > 0) {
        session_start();
        $result = $result->fetch_assoc();
        $userId = $result['id'];
        $_SESSION["username"] = $username;
        $_SESSION["userId"] = $userId;
        if($_SESSION['last_url']){
            echo "<script>window.location.href = '".$_SESSION['last_url']."'</script>";
        }else{
            echo "<script>window.location.href = '/'</script>";
        }
    } else {
        echo "<script>alert('Username atau password salah')</script>";
        echo "<script>window.location.href = '/pages/login.php'</script>";
    }
}
?>