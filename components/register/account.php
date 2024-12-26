<?php
$id = $_GET['i'];
$account = $db->query("SELECT * FROM `user` WHERE `id` = '$id'")->fetch_assoc();
?>
<section class="d-flex justify-content-center align-items-center w-100 h-vh-100">
    <div class="d-flex flex-column align-items-center bg-white text-dark p-4 rounded" style="width:390px;">
        <h1 class="mb-3">Data Login</h1>
        <div class="bg-danger text-light p-3 mb-3">
            Simpan username dan password ini, karena username dan password ini dugunakan untuk mengakses halaman login
        </div>
        <div class="d-flex gap-2">
            <h5 class="">Username : </h5>
            <h5 class=""><?=$account['username']?></h5>
        </div>
        <div class="d-flex gap-2">
            <h5 class="">Password : </h5>
            <h5 class=""><?=$account['password_real']?></h5>
        </div>
        <form action="/components/login/action.php" method="post" class="bg-light text-dark p-4 rounded shadow-xl" style="width:370px">
            <div class="d-none">
                <h2 class="text-center mb-3">Login</h2>
                <div class="mb-4">
                    <label for="username" class="" id="">Username</label>
                    <input type="text" class="form-control border-dark" name="username" id="username" value="<?=$account['username']?>">
                </div>
                <div class="mb-4">
                    <label for="password" class="" id="">Password</label>
                    <input type="text" class="form-control border-dark" name="password" id="password" value="<?=$account['password_real']?>">
                </div>
            </div>
            <button type="submit" name="btn-login" class="btn bg-pink w-100">Login</button>
        </form>
    </div>
</section>