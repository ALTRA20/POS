<?php
session_start();
$_SESSION['last_url'] = $_SERVER[REQUEST_URI];
?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/components/header/index.php';?>
<section class="container py-3">
    <h2 class="text-dark">PSP</h2>
    <div class="d-flex flex-wrap gap-4 mt-4">
        <?php
        if ($jabatan != 'super-admin') {
            $menus = $db->query("SELECT * FROM `menu` WHERE `jabatan` LIKE '%$jabatan%'");
        }else{
            $menus = $db->query("SELECT * FROM `menu`");
        }
        foreach ($menus as $key => $menu) : ?>
            <a href="<?=$menu['url']?>" class="bg-pink p-3 text-center rounded pointer">
                <?=$menu['icon']?>
                <p class="m-0 mt-2"><?=$menu['nama']?></p>
            </a>
        <?php endforeach ?>
    </div>
</section>
<?php include $_SERVER['DOCUMENT_ROOT'].'/components/footer/index.php';?>