<?php
    
    if (isset($_POST['btn-register'])) {
        if (!empty($_POST['username'])) {
            $username = $_POST['username'];
            $password = $_POST['username'].rand(0,9).rand(0,9).rand(0,9).rand(0,9);
            // $password = 'root';
            $password_hash = md5($password);
            $insert = $db->query("INSERT INTO `user`(`username`, `password_real`, `password`, `jabatan`, `created_at`, `updated_at`) VALUES ('$username','$password','$password_hash','cs',CURRENT_TIMESTAMP(),CURRENT_TIMESTAMP())");
            if ($insert) {
                $id = $db->query("SELECT * FROM `user` WHERE `username` = '$username' AND `password` = '$password_hash'")->fetch_assoc()['id'];
                echo "<script>window.location.href = '/pages/account.php?i=$id'</script>";
            }
        }else{
            echo "<script>alert('massukan username')</script>";
        }
    }
?>
<section class="d-flex align-items-center justify-content-center w-100 h-vh-100">
    <form action="" method="post" class="bg-light text-dark p-4 rounded shadow-xl" style="width:370px">
        <h2 class="text-center mb-3">Register</h2>
        <div class="mb-4">
            <label for="username" class="" id="">Username</label>
            <input type="text" class="form-control border-dark" name="username" autocomplete="off" id="username">
        </div>
        <button name="btn-register" class="btn bg-pink w-100">Buat Akun</button>
    </form>
</section>