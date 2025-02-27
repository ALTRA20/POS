<?php include $_SERVER['DOCUMENT_ROOT'].'/components/header/index.php';
if(!isset($_SESSION["username"])){
  echo "<script>window.location.href = '/pages/login.php'</script>";
}
$_SESSION['last_url'] = $_SERVER[REQUEST_URI];
$s = 1;
if (isset($_GET['s'])) {
  $s = $_GET['s'];
}
?>
<div class="container py-5">
  <ul class="d-flex align-items-center gap-4 ps-0">
    <li class="nav-link">
      <a href="?s=1" class="<?= ($s == 1) ? 'btn btn-primary' : ''?>">Show</a>
    </li>
    <li class="nav-link">
      <a href="?s=0" class="<?= ($s == 0) ? 'btn btn-primary' : ''?>">Hiden</a>
    </li>
  </ul>
  <?php
  if (isset($_POST['id'])) {
    $idp = $_POST['id'];

    if (isset($_POST['hide'])) {
      $update = $db->query("UPDATE `produk` SET `is_tampil_top100` = 0 WHERE `id` = '$idp'");
    }else if (isset($_POST['Show'])) {
      $idp = $_POST['id'];
      $update = $db->query("UPDATE `produk` SET `is_tampil_top100` = 1 WHERE `id` = '$idp'");
    }
    echo "<script>window.location.href = window.location.href</script>";
  }
  $totals = $db->query("SELECT produk.*, produk_id, SUM(CAST(jumlah AS UNSIGNED)) AS total_terjual
  FROM pesanan_detail
  JOIN produk ON produk.id = pesanan_detail.produk_id
  WHERE `produk_id` IS NOT NULL AND produk.is_tampil_top100 = $s
  GROUP BY produk_id
  ORDER BY total_terjual DESC
  LIMIT 100");
  foreach ($totals as $key => $total) :?>
  <?php
  
  $id = $total['id'];
  $foto = $db->query("SELECT * FROM `foto` WHERE `id_produk` = '$id' AND `is_cover` = 1");
  if ($foto->num_rows > 0) {
    $idFoto = $foto->fetch_assoc()['id'];
  }else{
    $foto = $db->query("SELECT * FROM `foto` WHERE `id_produk` = '$id' LIMIT 1");
    $idFoto = $foto->fetch_assoc()['id'];
  }
  $namaxx = $total['nama'];
  $idxx = $total['id_barcode'];
  $kelaso = "alert alert-success";
  if ($total['id_barcode'] == "null" || empty($total['id_barcode'])){
    $kelaso = "alert alert-danger";
    $idxx = "#".$total['id'];
  }
  if($idFoto) {
    $foto = $_SERVER["DOCUMENT_ROOT"].'/public/foto/md/'.$idFoto.'.jpg';
    if (file_exists($foto)) {
      $foto = '/public/foto/md/'.$idFoto.'.jpg';
    }else{
      $foto = '/public/404.png';
    }
  }else{
    $foto = '/public/404.png';
  }
  ?>
  <div class="<?=$kelaso;?> d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center gap-3">
      <img src="<?=$foto?>" alt="" class="p-0 m-0" style="width:45px;height:45px">
      <h5 class=""><?=$idxx?></h5>
      <span class="">;;</span>
      <h5 class=""><?=$namaxx?></h5>
    </div>
    <form action="" method="POST" class="">
      <input type="text" class="d-none" name="id" value="<?=$id?>">
      <?php if ($s == 1) : ?>
        <button type="submit" name="hide" class="btn btn-danger">Hide</button>
      <?php else : ?>
        <button type="submit" name="Show" class="btn btn-success">Show</button>
      <?php endif ?>
    </form>
  </div>
  <?php endforeach ?>
</div>
<?php include $_SERVER['DOCUMENT_ROOT'].'/components/footer/index.php';  ?>