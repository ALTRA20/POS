<section class="container text-dark py-5">
    <h2 class="">Customer List</h2>
    <div class="d-flex flex-wrap gap-3">
        <?php
        $customers = $db->query("SELECT * FROM `customer`");
        foreach ($customers as $key => $customer) : ?>
        <div class="row gap-4 border border-dark bg-success m-0 py-3 rounded pointer" style="width:400px" data-bs-toggle="modal" data-bs-target="#user<?=$customer['id']?>">
            <div class="col-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor" class="bi bi-person-fill text-light" viewBox="0 0 16 16">
                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                </svg>
            </div>
            <div class="col-8">
                <h5 class="text-light"><?=$customer['nama']?></h5>
                <h5 class="text-light"><?=$customer['alamat']?></h5>
                <h5 class="text-light"><?=$customer['wa']?></h5>
            </div>
        </div>
        <div class="modal fade" id="user<?=$customer['id']?>" tabindex="-1" aria-labelledby="user<?=$customer['id']?>Label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel"><?=$customer['nama']?></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="my-3">
                            <label for="nama" class="">nama</label>
                            <input type="text" id="nama" class="form-control text-dark border border-dark" value="<?=$customer['nama']?>">
                        </div>
                        <div class="my-3">
                            <label for="alamat" class="">alamat</label>
                            <input type="text" id="alamat" class="form-control text-dark border border-dark" value="<?=$customer['alamat']?>">
                        </div>
                        <div class="my-3">
                            <label for="wa" class="">wa</label>
                            <input type="text" id="id" class="form-control text-dark border border-dark d-none" value="<?=$customer['id']?>">
                            <input type="text" id="wa" class="form-control text-dark border border-dark" value="<?=$customer['wa']?>">
                        </div>
                        <a href="/components/customer/delete.php?id=<?=$customer['id']?>" class="btn border border-danger">Hapus</a>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach ?>
    </div>
</section>