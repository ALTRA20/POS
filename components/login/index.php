<section class="d-flex align-items-center justify-content-center w-100 h-vh-100">
    <form action="/components/login/action.php" method="post" class="bg-light text-dark p-4 rounded shadow-xl" style="width:370px">
        <h2 class="text-center mb-3">Login</h2>
        <div class="mb-4">
            <label for="username" class="" id="">Username</label>
            <input type="text" class="form-control border-dark" name="username" id="username">
        </div>
        <div class="mb-4">
            <label for="password" class="" id="">Password</label>
            <input type="password" class="form-control border-dark" name="password" id="password">
        </div>
        <button type="submit" name="btn-login" class="btn bg-pink w-100">Login</button>
    </form>
</section>