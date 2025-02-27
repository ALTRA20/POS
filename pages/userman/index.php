<?php $_SESSION['last_url'] = $_SERVER[REQUEST_URI]; ?>
<?php include $_SERVER['DOCUMENT_ROOT'].'/components/header/index.php'; ?>
<script>
    document.title = 'Userman GS';
</script>
<?php include $_SERVER['DOCUMENT_ROOT'].'/components/userman/index.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'].'/components/footer/index.php'; ?>