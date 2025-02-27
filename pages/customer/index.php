<?php include $_SERVER['DOCUMENT_ROOT'].'/components/header/index.php'; ?>
<script>
    document.title = 'Customerman GS';
</script>
<?php $_SESSION['last_url'] = $_SERVER[REQUEST_URI]; ?>
<?php
if (isset($_POST['tambah-customer'])) {
    $nama = strtolower($_POST['nama']);
    $alamat = $_POST['alamat'];
    $wa = $_POST['wa'];
    $is_available = $db->query("SELECT * FROM `customer` WHERE `nama` = '$nama'")->num_rows > 0;
    if ($is_available) {
        echo "<script>alert('Nama ini sudah pernah diinput')</script>";
        echo "<script>window.location.href = window.location.href</script>";
    } else {
        $insert = $db->query("INSERT INTO `customer`(`nama`, `alamat`, `wa`) VALUES ('$nama','$alamat','$wa')");
        if ($insert) {
            echo "<script>window.location.href = window.location.href</script>";
        }
    }
}
if (isset($_POST['edit-customer'])) {
    $id = $_POST['id'];
    $nama = strtolower($_POST['nama']);
    $alamat = $_POST['alamat'];
    $wa = $_POST['wa'];
    $update = $db->query("UPDATE `customer` SET `nama`='$nama',`alamat`='$alamat',`wa`='$wa' WHERE `id` = '$id'");
    if ($update) {
        echo "<script>window.location.href = window.location.href</script>";
    }
}
?>
<section class="container bg-lightgray py-5">
    <div class="d-flex justify-content-end mb-4">
        <button type="button" class="btn bg-dark text-light" data-bs-toggle="modal" data-bs-target="#tambahCustomer">Tambah Customer</button>
        <div class="modal fade" id="tambahCustomer" tabindex="-1" aria-labelledby="tambahCustomerLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="" method="post" class="modal-content bg-lightgray">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="tambahCustomerLabel">Tambah Customer</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="">
                            <label for="nama">nama</label>
                            <input type="text" id="nama" name="nama" class="form-control text-dark border-dark mb-3">
                        </div>
                        <div class="">
                            <label for="alamat">alamat</label>
                            <input type="text" id="alamat" name="alamat" class="form-control text-dark border-dark mb-3">
                        </div>
                        <div class="">
                            <label for="wa">wa</label>
                            <input type="text" id="wa" name="wa" class="form-control text-dark border-dark mb-3">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="tambah-customer">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php 
    $searchNama = (isset($_GET['n'])) ? $_GET['n'] : '';
    $costumers = $db->query("SELECT * FROM `customer` WHERE `nama` LIKE '%$searchNama%'");
    foreach ($costumers as $key => $costumer) :
    ?>
        <div class="row <?=($key == 0) ? 'border-top border-dark' : ''?> border-bottom border-dark py-3">
            <div class="col-5">
                <?=$costumer['nama']?>
            </div>
            <div class="col-3">
                <?=$costumer['alamat']?>
            </div>
            <div class="col-2">
                <?=$costumer['wa']?>
            </div>
            <div class="col-2">
                <button type="button" class="btn bg-pink" data-bs-toggle="modal" data-bs-target="#edit<?=$costumer['id']?>">Edit Customer</button>
                <div class="modal fade" id="edit<?=$costumer['id']?>" tabindex="-1" aria-labelledby="edit<?=$costumer['id']?>Label" aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="" method="post" class="modal-content bg-lightgray">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="edit<?=$costumer['id']?>Label">Edit Customer</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="">
                                    <input type="text" id="id" name="id" class="form-control d-none text-dark border border-dark mb-3" value="<?=$costumer['id']?>">
                                    <label for="nama">nama</label>
                                    <input type="text" id="nama" name="nama" class="form-control text-dark border border-dark mb-3" value="<?=$costumer['nama']?>">
                                </div>
                                <div class="">
                                    <label for="alamat">alamat</label>
                                    <input type="text" id="alamat" name="alamat" class="form-control text-dark border border-dark mb-3" value="<?=$costumer['alamat']?>">
                                </div>
                                <div class="">
                                    <label for="wa">wa</label>
                                    <input type="text" id="wa" name="wa" class="form-control text-dark border border-dark mb-3" value="<?=$costumer['wa']?>">
                                </div>
                                <a href="/components/customer/delete.php?id=<?=$costumer['id']?>" class="py-2 px-4 nav-link border border-danger rounded" id="" style="width:fit-content;">Hapus</a>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success" name="edit-customer">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach ?>
</section>
<?php include $_SERVER['DOCUMENT_ROOT'].'/components/footer/index.php'; ?>