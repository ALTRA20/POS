<?php include $_SERVER['DOCUMENT_ROOT'].'/components/header/index.php'; 
if(!isset($_SESSION["username"])){
    echo "<script>window.location.href = '/pages/login.php'</script>";
}
$i = $_GET['i'];
$sql = "SELECT *,pesanan.id AS id FROM `pesanan` JOIN pesanan_detail ON pesanan_detail.pesanan_id = pesanan.id JOIN customer ON customer.id = pesanan.customer_id WHERE pesanan_detail.produk_id = '$i' ORDER BY pesanan.id DESC";
$itemsPerPage = 4;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $itemsPerPage;
$pesanans = $db->query("$sql LIMIT $offset, $itemsPerPage");
$jumlah_total = $db->query($sql)->num_rows;
?>
<?php
$_SESSION['last_url'] = $_SERVER[REQUEST_URI];
?>
<script>
    document.title = 'History GS';
</script>
<div class="bg-success">
  <section class="container p-5">
    <!-- <p class="p-0 m-0">Jumlah pesanan : <?=$jumlah_total?></p> -->
    <?php 
        foreach ($pesanans as $key => $pesanan) : 
        $level = $pesanan['level'];
        $backgroundColor = '';
        
        if ($level == 0) {
            $backgroundColor = 'danger';
        }else if ($level == 1) {
            $backgroundColor = 'danger';
        }else if ($level == 2) {
            $backgroundColor = 'warning';
        }else if ($level == 3) {
            $backgroundColor = 'light';
        }

        $bcBtn = "bg-danger text-light";
        if ($level == 3) {
            $bcBtn = "bg-primary text-light";
        }
        ?>
        <div class="my-4">
            <div class="row alert alert-<?=$backgroundColor?> p-3 rounded">
            <div class='col-md-2'>
            <?php 
            $x = $pesanan['created_at'];
            $id_pesanan = $pesanan['id'];
            
            $barangs = [];
            $sql = "SELECT `pesanan_detail`.*, `produk`.nama, `produk`.id AS produkId 
            FROM `pesanan_detail`
            LEFT JOIN `produk` ON `produk`.id = `pesanan_detail`.`produk_id`
            WHERE `pesanan_id` = '$id_pesanan'
            ORDER BY 
                CASE 
                WHEN `pesanan_detail`.`komentar` IS NOT NULL THEN 1 
                ELSE 0 
                END, 
                `pesanan_detail`.`id` ASC";
            $pesanan_details = $db->query($sql);
            $total = 0;
            // var_dump($sql);
            $jumlah_pesanan = $pesanan_details->num_rows;
            foreach ($pesanan_details as $key => $pesanan_detail) {
                if(intVal($pesanan_detail['markup']) > 0) {
                    $is_markup = $pesanan_detail['markup'];
                }
                $barangs [$key]["produk_id"] = $pesanan_detail['produk_id'];
                $barangs [$key]["nama"] = $pesanan_detail['nama'];
                $barangs [$key]["foto"] = $pesanan_detail['foto'];
                $barangs [$key]["markup"] = $pesanan_detail['markup'];
                echo "<div class='hr'></div>";
                $total += ($pesanan_detail['harga_jual'] + $pesanan_detail['markup']) * $pesanan_detail['jumlah'];
            }
            // var_dump($total);
            // die();
            echo $x;
            ?>
            </div>
                <div class="col-md-2">
                    <div class="bg-primary text-light text-center py-2 px-4 pointer rounded" id="btnGS<?=$pesanan['id']?>" data-bs-toggle="modal" data-bs-target="#GS<?=$pesanan['id']?>">GS <?=$pesanan['id']?></div>
                    <div class="mx-2">
                        <h5 class=""><?=$pesanan['nama']?> - <?=$pesanan['alamat']?></h5>
                        <p class="m-0"><?=$pesanan['wa']?></p>
                        <p class="m-0"><?=$pesanan['username']?></p>
                    </div>
                </div>
                <div class="col-md-1 col-6 mt-2 mt-md-0 d-flex justify-md-content-center">
                    <button class="btn <?=$bcBtn?> h-fit" data-bs-toggle="modal" data-bs-target="#level<?=$pesanan['id']?>">Level <?=$level?></button>
                </div>
                <div class="col-md-2 col-6 mt-2 mt-md-0 d-flex flex-column justify-content-center align-items-center">
                    <p class="m-0"><?=rupiah($total)?></p>
                    <?php 
                    if ($total - $nominalBayar > 0) : ?>
                        <p class="btn btn-danger m-0"><span class="">Kurang </span><?=format_rupiah($total - $nominalBayar)?></p>
                    <?php elseif($total - $nominalBayar < 0): ?>
                        <p class="m-0"><span class="text-success">Lebihan </span><?=format_rupiah($nominalBayar - $total)?></p>
                    <?php endif ?>
                </div>
                <div class="col-md-5 d-flex flex-wrap">
                    <?php if ($is_markup > 0) : ?>
                        <div class="position-absolute bg-danger text-light p-2" style="right:-20px;top:45%;font-size:22px;">[^]</div>
                    <?php endif ?>
                    <?php
                    $barangs = $db->query("SELECT `pesanan_detail`.*, `produk`.nama, `produk`.id AS produkId 
                    FROM `pesanan_detail`
                    LEFT JOIN `produk` ON `produk`.id = `pesanan_detail`.`produk_id`
                    WHERE `pesanan_id` = '$id_pesanan'
                    ORDER BY 
                        CASE 
                        WHEN `pesanan_detail`.`komentar` IS NOT NULL THEN 1 
                        ELSE 0 
                        END, 
                        `pesanan_detail`.`id` ASC");
                    foreach ($barangs as $key => $barang) : ?>
                        <?php if ($barang['produk_id']): ?>
                            <?php 
                            $id_produk = $barang['produk_id'];
                            // var_dump($id_produk);
                            $foto = $db->query("SELECT * FROM `foto` WHERE `id_produk` = '$id_produk' AND is_cover = 1");
                            if ($foto && $foto->num_rows > 0): 
                                $foto_data = $foto->fetch_assoc();
                            ?>
                                <a href="/pages/history/produk/?i=<?=$id_produk?>" class=""><img src="/public/foto/md/<?= htmlspecialchars($foto_data['id']) ?>.jpg" alt="<?=($barang['produk_id']) ? $barang['nama'] : 'custom-produk'?>" id="img-produk" class="rounded-circle <?=($barang['markup'] > 0) ? 'border border-success border-5' : ''?> mx-1" style="width:70px;height:70px;"></a>
                            <?php else: ?>
                                <?php 
                                $foto = $db->query("SELECT * FROM `foto` WHERE `id_produk` = '$id_produk' ORDER BY `id` DESC LIMIT 1")->fetch_assoc()['id']; 
                                if ($foto) {
                                    $foto = "/public/foto/md/$foto.jpg";
                                }else{
                                    $foto = "/public/404.png";
                                }
                                ?>
                                <a href="/pages/history/produk/?i=<?=$id_produk?>" class=""><img src="<?=$foto?>" alt="<?=($barang['produk_id']) ? $barang['nama'] : 'custom-produk'?>" id="img-produk" class="rounded-circle <?=($barang['markup'] > 0) ? 'border border-success border-5' : ''?> mx-1" style="width:70px;height:70px;"></a>
                            <?php endif; ?>
                        <?php else: ?>
                            <img src="<?=($barang['foto']) ? '/public/foto/temp/'.$barang['foto'] : '/public/foto/md/custom.jpg'?>" alt="<?=($barang['produk_id']) ? $barang['nama'] : 'custom-produk'?>" id="img-produk" class="rounded-circle <?=($barang['markup'] > 0) ? 'border border-success border-5' : ''?> mx-1" style="width:70px;height:70px;">
                        <?php endif; ?>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    <?php endforeach ?>
    <ul class="w-full d-flex justify-content-center pagination d-flex justify-content-center flex-wrap mt-5 gap-3">
        <?php
        $totalPages = ceil($jumlah_total / $itemsPerPage);
        
        // Mendapatkan query string yang sudah ada kecuali 'page'
        $queryString = $_GET;
        unset($queryString['page']); // Hapus parameter page dari query string
        $queryString = http_build_query($queryString); // Bangun ulang query string

        if ($page <= 4) {
            $startPage = 1;
            $endPage = min(8, $totalPages); // Jangan melebihi total halaman
        } else {
            $startPage = $page - 3;
            $endPage = min($page + 4, $totalPages); // Jangan melebihi total halaman
        }
        if ($startPage > 1) {
            // Tampilkan tombol "Previous" jika ada halaman tersembunyi sebelumnya
            ?>
            <li class="page-item"><a class="page-link" href="?<?=$queryString?>&page=<?= $startPage - 1 ?>">Previous</a></li>
            <?php
        }
        for ($pageNumber = $startPage; $pageNumber <= $endPage; $pageNumber++) :
            $isActive = ($pageNumber === $page) ? 'active' : '';
            ?>
            <li class="page-item <?=$isActive?>"><a class="page-link" href="?<?=$queryString?>&page=<?= $pageNumber ?>"><?=$pageNumber ?></a></li>
            <?php
        endfor;
        if ($endPage < $totalPages) {
            // Tampilkan tombol "Next" jika ada halaman tersembunyi setelahnya
            ?>
            <li class="page-item"><a class="page-link" href="?<?=$queryString?>&page=<?= $endPage + 1 ?>">Next</a></li>
            <?php
        }
        ?>
    </ul>
  </section>
</div>
<?php include $_SERVER['DOCUMENT_ROOT'].'/components/footer/index.php';  ?>