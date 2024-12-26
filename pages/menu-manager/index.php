<?php include $_SERVER['DOCUMENT_ROOT'].'/components/header/index.php'; 
if(!isset($_SESSION["username"])){
    echo "<script>window.location.href = '/pages/login.php'</script>";
}
?>
<?php
$_SESSION['last_url'] = $_SERVER[REQUEST_URI];
if (isset($_POST['btn-add-menu'])) {
    $url = $_POST['url'];
    $nama = $_POST['nama'];
    $icon = $_POST['icon'];
    $jabatan = $_POST['jabatan'];
    $insert = $db->query("INSERT INTO `menu`(`nama`, `url`, `icon`, `jabatan`) VALUES ('$nama','$url','$icon','$jabatan')");
    echo '<script>window.location.href = "/pages/menu-manager/"</script>';
}   
?>
<script>
    document.title = 'History GS';
</script>
<div class="bg-success">
    <section class="container h-vh-100 p-5">
        <div class="d-flex align-items-center justify-content-between">
            <h3 class="">Menu Manager</h3>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">+</button>
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="" method="post" class="modal-content text-dark">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Add Menu</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                                <div class="my-3">
                                    <input type="text" name="nama" class="form-control" placeholder="Nama Menu" autocomplete="off">
                                </div>
                                <div class="my-3">
                                    <input type="text" name="url" class="form-control" placeholder="Url Menu" autocomplete="off">
                                </div>
                                <div class="my-3">
                                    <textarea name="icon" id="" cols="30" rows="10" class="form-control" placeholder="Icon Menu" autocomplete="off"></textarea>
                                </div>
                                <div class="my-3">
                                    <select class="form-select" name="jabatan" aria-label="Default select example">
                                        <?php 
                                        $jabatans = $db->query("SELECT DISTINCT(`jabatan`) FROM `user`");
                                        foreach ($jabatans as $key => $jabatan) :
                                        ?>                                        
                                        <option value="<?=$jabatan['jabatan']?>"><?=$jabatan['jabatan']?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="btn-add-menu" class="btn btn-primary">Tambahkan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-4">Nama</div>
            <div class="col-4">Url</div>
            <div class="col-2">Icon</div>
            <div class="col-2">Jabatan</div>
            <?php
            $menus = $db->query("SELECT * FROM `menu`");
            foreach ($menus as $key => $menu) : ?>
                <div class="col-4"><?=$menu['nama']?></div>
                <div class="col-4"><?=$menu['url']?></div>
                <div class="col-2"><?=$menu['icon']?></div>
                <div class="col-2"><?=$menu['jabatan']?></div>
            <?php endforeach ?>
        </div>
    </section>
</div>
<?php include $_SERVER['DOCUMENT_ROOT'].'/components/footer/index.php';  ?>