<style>
    .modal-me{
        width: 400px;
        background: white;
        color: black;
        border-radius: 5px;
    }
    .modal-me .modal-header{
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px;
        border-radius: 5px 5px 0 0;
    }
    .modal-me .modal-footer{
        padding: 15px;
        border-radius: 5px 5px 0 0;
        gap: 4px;
    }
    ..modal-me .modal-header .btn-close{
        padding: 6px;
        border-radius: 7px;
    }
    ..modal-me .modal-header .btn-close:hover{
        background-color: red;
        color: white;
    }
</style>
<section class="container bg-lightgray py-5">
    <div class="w-100 d-flex justify-content-between">
        <h2 class="">Userman</h2>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary h-fit" data-bs-toggle="modal" data-bs-target="#exampleModal">Tambah User</button>
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php include $_SERVER['DOCUMENT_ROOT'].'/components/register/index.php';?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex flex-wrap gap-3">
        <?php
        $users = $db->query("SELECT * FROM `user`");
        foreach ($users as $key => $user) : ?>
        <div class="row gap- border border-dark <?= ($user['is_active'] == 1) ? 'bg-success' : 'bg-danger text-light' ?> m-0 py-3 rounded" id="userCard<?=$user['id']?>" style="width:400px">
            <div class="col-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor" class="bi bi-person-fill text-light" viewBox="0 0 16 16">
                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                </svg>
            </div>
            <div class="col-6">
                <h5 class="text-light pointer" id="username_display<?=$user['id']?>" data-bs-toggle="modal" data-bs-target="#user<?=$user['id']?>"><?=$user['username']?></h5>
                <h5 class="text-light" id="jabatan_display<?=$user['id']?>"><?=$user['jabatan']?></h5>
            </div>
            <div class="col-3 d-flex align-items-center">
                <form action="/components/login/action.php" method="post" class="">
                    <input type="text" name="username" value="<?=$user['username']?>" class="d-none">
                    <input type="text" name="password" value="<?=$user['password_real']?>" class="d-none">
                    <button class="btn btn-primary" name="btn-login">Login</button>
                </form>
            </div>
        </div>
        <div class="modal fade" id="user<?=$user['id']?>" tabindex="-1" aria-labelledby="user<?=$user['id']?>Label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit - <?=$user['username']?></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="my-3">
                            <label for="username" class="">Username</label>
                            <input autocomplete="off" type="text" id="username<?=$user['id']?>" class="form-control text-dark border border-dark" value="<?=$user['username']?>">
                        </div>
                        <div class="my-3">
                            <label for="jabatan" class="">jabatan</label>
                            <input autocomplete="off" type="text" id="jabatan<?=$user['id']?>" class="form-control text-dark border border-dark" value="<?=$user['jabatan']?>">
                        </div>
                        <div class="my-3">
                            <label for="password_real" class="">password_real</label>
                            <input autocomplete="off" type="text" id="id<?=$user['id']?>" class="form-control text-dark border border-dark d-none" value="<?=$user['id']?>">
                            <input autocomplete="off" type="text" id="password_real<?=$user['id']?>" class="form-control text-dark border border-dark" value="<?=$user['password_real']?>">
                        </div>
                        <div class="my-3">
                            <?php $active = ($user['is_active'] == 1) ? 'delist' : 'list' ?>
                            <div class="btn btn-danger" onclick="delist(<?=$user['id']?>, '<?=$active?>')">Delist</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="changeUserEdit(<?=$user['id']?>)">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach ?>
    </div>
</section>
<script>
    
    function delist(id, status) {
        var dataToSend = { 
            id: id,
            status: status
        };
        // console.log(dataToSend);
        fetch('/components/userman/delistUser.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(dataToSend),
        })
        .then(response => response.text())
        .then(datas => {
            console.log(document.getElementById("userCard"+id).classList.contains("bg-success"));
            if (document.getElementById("userCard"+id).classList.contains("bg-success")) {
                document.getElementById("userCard"+id).classList.remove("bg-success");
                document.getElementById("userCard"+id).classList.add("bg-danger");
            }else{
                document.getElementById("userCard"+id).classList.add("bg-success");
                document.getElementById("userCard"+id).classList.remove("bg-danger");
            }
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }
    function success() {
        let element = document.createElement('section');
        element.classList.add('w-100', 'h-100', 'position-fixed', 'top-0', 'd-flex', 'align-items-center', 'justify-content-center');
        element.style.zIndex = '9999999';
        element.style.background = 'white';
        element.innerHTML = `
            <div class="p-4 rounded text-center bg-light text-dark shadow-lg" style="width:350px; background:rgba(255,255,255,0.3)">
                <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor" class="bi bi-check-circle-fill text-success" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                </svg>
                <h2 class="">Success mengupdate data</h2>
            </div>
        `;
        document.querySelector("body").appendChild(element);
    }
    function changeUserEdit(id) {
        let username = document.querySelector("#username"+id).value;
        let jabatan = document.querySelector("#jabatan"+id).value;
        let idUser = document.querySelector("#id"+id).value;
        let password_real = document.querySelector("#password_real"+id).value;
        
        document.querySelector("#username_display"+id).innerHTML = username;
        document.querySelector("#jabatan_display"+id).innerHTML = jabatan;

        var dataToSend = { 
            idUser: idUser,
            username: username,
            jabatan: jabatan,
            password_real: password_real
        };

        fetch('/components/userman/edit.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(dataToSend),
        })
        .then(response => response.text())
        .then(datas => {
            success();  
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }
</script>