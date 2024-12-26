<?php
$id = $_GET['i'];
$nama = $db->query("SELECT `nama` FROM `produk` WHERE `id` = '$id'")->fetch_assoc()['nama'];
?>
<div class="">
    <div class="d-flex justify-content-between p-4">
        <h5 class="">Foto Produk</h5>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Tambah Foto Produk</button>
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog text-dark">
                <form action="/components/tambah-product/tambah_foto_action.php" method="post" class="modal-content" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah produk : <?=$nama?></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" name="id_user" class="d-none" value="<?=$userId?>">
                        <input type="text" name="id_produk" class="d-none" value="<?=$id?>">
                        <label for="upload-thumbnail" class="border border-dash border-dark d-flex justify-content-center align-items-center pointer position-relative" style="width:360px; height:240px;">
                            <img src="" alt="" class="d-none position-absolute w-100 h-100" id="uploadedThumbnail">
                            <p class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center m-0" id="foto-overlay">+ Foto</p>
                            <input type="file" id="upload-thumbnail" class="d-none" name="foto" accept="image/*">
                        </label>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="d-flex flex-wrap">
        <?php 
        $fotos = $db->query("SELECT * FROM `foto` WHERE `id_produk` = '$id'");
        $is_available_foto = $fotos->num_rows > 0;
        if ($is_available_foto) :
            foreach ($fotos as $key => $foto) : ?>
            <div class="">
                <div class="p-3" data-bs-toggle="modal" data-bs-target="#produkEditModal<?=$foto['id']?>">
                    <img src="<?=$foto['file']?>" alt="" class="foto-produk">
                </div>
                <div class="modal fade d-blockk" id="produkEditModal<?=$foto['id']?>" tabindex="-1" aria-labelledby="produkEditModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content text-dark">
                            <div class="modal-header">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="position-relative">
                                    <input type="file" class="form-control d-none" id="<?=$foto['id']?>" accept="image/*">
                                    <img src="<?=$foto['file']?>" alt="" id="" class="w-100">
                                    <label for="<?=$foto['id']?>" class="position-absolute w-100 h-100 text-light justify-content-center align-items-center pointer gambar-edit" id="" style="top:0; left:0;">
                                        <div class="d-flex flex-column justify-content-center align-items-center h-100">
                                            <div class="">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                                </svg>
                                            </div>
                                            <p class="m-0 fz-25">Ubah</p>
                                        </div>
                                    </label>
                                </div>
                                <div class="d-flex align-items-center gap-2 py-2 px-4 border border-danger text-danger mt-3 rounded nav-link w-fit pointer" onclick="hapusFoto(<?=$userId?>,<?=$foto['id']?>)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                        <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                                    </svg>
                                    <p class="m-0">Hapus</p>
                                </div>
                            </div>
                            <div class="modal-footer d-none" id="form-footer<?=$foto['id']?>">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach ?>
        <?php else : ?>
            <div class="w-100 d-flex flex-column justify-content-center align-items-center" style="height: 200px;">
                <h3 class="">Belum Ada Foto Produk</h3>
                <h3 class="">Untuk <span class="text-pink"><?=$nama?></span></h3>
            </div>
        <?php endif ?>
    </div>
</div>
<script>
    document.querySelector("#upload-thumbnail").addEventListener('change', function() {
        let id = this.id;
        document.querySelector("#form-footer" + id).classList.remove("d-none");
    });

    document.getElementById('upload-thumbnail').addEventListener('change', function() {
        var input = this;
        var thumbnail = document.getElementById('uploadedThumbnail');
        
        // Pastikan ada file yang dipilih
        if (input.files && input.files[0]) {
            var file = input.files[0];
            
            // Baca file sebagai URL data
            var reader = new FileReader();
            reader.onload = function (e) {
                // Tampilkan thumbnail
                console.log(e.target.result);
                thumbnail.src = e.target.result;
                document.getElementById('foto-overlay').style.backgroundColor = "rgba(0,0,0,0.7)";
                thumbnail.classList.remove('d-none');
            };

            // Baca file sebagai URL data
            reader.readAsDataURL(file);
        }
    });
    function hapusFoto(i, ip) {
        console.log(ip);
        var dataToSend = { 
            userId : i,
            produkId : ip,
        };

        fetch('/components/tambah-product/hapus_foto_action.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(dataToSend),
        })
        .then(response => response.json())
        .then(datas => {
            alert(datas['message']);
            document.querySelector('.btn-close').click();
            window.location.href = window.location.href;
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }
</script>