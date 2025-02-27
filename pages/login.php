<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="/public/bootstrap/css/bootstrap.css" rel="stylesheet">
    <script src="/public/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/function/dateNow.js"></script>
    <link href="/pages/index.css" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<body>
    <?php include $_SERVER['DOCUMENT_ROOT'].'/function/db.php'; 
    include $_SERVER['DOCUMENT_ROOT'].'/components/login/index.php';
    include $_SERVER['DOCUMENT_ROOT'].'/components/footer/index.php'; 
    ?>