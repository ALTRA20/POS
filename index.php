<?php
session_start();
$_SESSION['last_url'] = $_SERVER[REQUEST_URI];
function grouped($menus) {
    $groupedMenus = [];
    foreach ($menus as $menu) {
        // Cek apakah ada lebih dari satu jabatan
        if (strpos($menu['jabatan'], ',')) {
            // Jika ada koma, pecah menjadi array
            $roles = array_map('trim', explode(',', $menu['jabatan']));
        } else {
            // Jika hanya satu jabatan, masukkan sebagai array satu elemen
            $roles = [$menu['jabatan']];
        }
        
        // Masukkan menu ke dalam masing-masing jabatan
        foreach ($roles as $role) {
            $groupedMenus[$role][] = $menu;
        }
    }
    // var_dump($groupedMenus);
    return $groupedMenus;
}
?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/components/header/index.php';?>
<style>
    #menu:hover {
        background: blue!important;
    }

    /* #menu:hover > svg{
        width : 70px;
        height : 70px;
    }

    #menu:hover > p{
        font-size: 20px;
    } */
</style>
<section class="container py-3">
    <h2 class="text-dark">Dashboard</h2>
    <?php     
        if ($jabatan != 'super-admin') {
            $menus = $db->query("SELECT * FROM `menu` WHERE `jabatan` LIKE '%$jabatan%'");
        }else{
            $menus = $db->query("SELECT * FROM `menu`");
        }
        $menuGroup = grouped($menus->fetch_all(MYSQLI_ASSOC));
        
        foreach ($menuGroup as $key => $menus) : ?>
        <div class="alert alert-warning rounded p-4 my-3">
            <h5 class="text-"><?=$key?></h5>
            <div class="mb-5 d-flex flex-wrap gap-4 mt-4">
                <?php foreach ($menus as $key => $menu) : ?>
                <a href="<?=$menu['url']?>" id="menu" class="bg-pink p-3 text-center rounded pointer">
                    <?=$menu['icon']?>
                    <p class="m-0 mt-2"><?=$menu['nama']?></p>
                </a>
                <?php endforeach ?>
            </div>
        </div>
        <?php endforeach ?>
</section>
<?php include $_SERVER['DOCUMENT_ROOT'].'/components/footer/index.php';?>