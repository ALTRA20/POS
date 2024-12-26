<?php $_SESSION['last_url'] = $_SERVER[REQUEST_URI]; ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/components/header/index.php'; 
$id = $_GET['i'];
$produks = $db->query("SELECT * FROM `produk` WHERE `id` = '$id'");
foreach ($produks as $key => $produk) :
?>
<section class="container text-dark py-5">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/components/tambah-product/foto.php'; ?>
    <div class="col-12">
        <div class="">
            <input type="text" class="form-control d-none" id="i" value="<?=$produk['id']?>">
            <label for="namaEdit" class="fw-bold">Nama</label>
            <input type="text" class="form-control border border-dark text-dark mb-4" id="namaEdit" value="<?=$produk['nama']?>" autofocus autocomplete="off">
        </div> 
        <div class="">
            <h5 class="mt-3">Kategori Produk</h5>
            <select class="form-select" aria-label="Default select example" id="kategori_produkEdit" name="kategori_produkEdit">
                <?php $kategoris = $db->query("SELECT DISTINCT `kategori` FROM `produk` WHERE `kategori` IS NOT NULL AND `kategori` != ''"); ?>
                <?php foreach ($kategoris as $kategori) : ?>
                    <option value="<?=$kategori['kategori']?>" <?=($produk['kategori'] == $kategori['kategori'] ? 'selected' : '')?>><?=$kategori['kategori']?></option>
                <?php endforeach ?>
            </select>
            <div class="mb-3" id="addFiturAddMenu">
                <span class="me-2">ingin menambahkan kategori menu baru?</span>
                <span class="text-primary pointer" onclick="addFiturAddMenuEdit()">Klik disini</span>
            </div>
            <div class="tambahKategoriMenuEdit d-none my-2">
                <div class="d-flex gap-2">
                    <input type="text" class="form-control border-dark text-dark" placeholder="masukkan kategori menu yang baru disini" autocomplete="off">
                    <button type="button" class="btn btn-primary" onclick="addSelectEdit()">Tambah</button>
                </div>
                <p class="d-none p-2 alert-fade mt-2 rounded w-fit" id="alertSuccessAddKategoriMenuEdit">Kategori menu baru udah ditambahkan</p>
            </div>
        </div>
        <div class="mb-4">
            <div class="" id="hargaJualEdit">
                <p class="fw-bold m-0">Harga Jual</p>
                <?php $harga_juals = json_decode($produk['harga_jual']);
                foreach ($harga_juals as $key => $harga_jual) : ?>
                <div class="row" id="harga_juals_<?=$key + 1?>">
                    <div class="col-11">
                        <div class="row">
                            <div class="col-6">
                                <label for="jumlahBarang" class="">Jumlah Barang</label>
                                <input type="number" class="form-control border border-dark text-dark mb-4" id="jumlahBarangEdit" autocomplete="off" value="<?=$harga_jual->jumlah?>" autofocus>
                            </div>
                            <div class="col-6">
                                <label for="hargaBarang" class="">Harga</label>
                                <input type="text" class="form-control border border-dark text-dark mb-4" id="hargaBarangEdit" value="<?=$harga_jual->harga?>" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="col-1 d-flex justify-content-center align-items-center">
                        <div class="bg-danger text-light p-3 rounded pointer" onclick="removeHargaJuals('harga_juals_<?=$key + 1?>')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                            <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <?php endforeach ?>
            </div>
            <div class="w-100 d-flex justify-content-end mt-3">
                <button type="button" class="btn btn-primary" onclick="tambahJualEdit()">Tambah Harga Jual</button>
            </div>
        </div>
        <div class="">
            <label for="hargaBeliEdit" class="fw-bold">Harga Beli</label>
            <input type="text" class="form-control border border-dark text-dark mb-4" id="hargaBeliEdit" value="<?=$produk['harga_beli']?>"  autocomplete="off">
        </div>
        <div class="">
            <label for="dimensiEdit" class="fw-bold">Dimensi</label>
            <input type="text" class="form-control border border-dark text-dark mb-4" id="dimensiEdit" value="<?=$produk['dimensi']?>"    autocomplete="off">
        </div>
        <div class="">
            <label for="beratEdit" class="fw-bold">Berat</label>
            <input type="text" class="form-control border border-dark text-dark mb-4" id="beratEdit" value="<?=$produk['berat']?>"  autocomplete="off">
        </div>
        <div class="">
            <label for="stockEdit" class="fw-bold">Stock</label>
            <input type="text" class="form-control border border-dark text-dark mb-4" id="stockEdit" value="<?=$produk['stock']?>"  autocomplete="off">
        </div>
        <div class="d-flex justify-content-end">
            <div class="w-100 btn bg-pink" onclick="submitFormEdit()">Ubah</div>
        </div>
    </div>
</section>
<?php endforeach ?>
<script>
    function removeHargaJuals(id) {
        document.getElementById(id).remove();
    }
    const addFiturAddMenuEdit = (element) => {
        document.getElementById('addFiturAddMenu').classList.add('d-none');
        document.getElementsByClassName('tambahKategoriMenuEdit')[0].classList.toggle('d-none');
    }
    const addSelectEdit = () => {
        // Get the select element by its ID
        var selectElement = document.getElementById('kategori_produkEdit');

        // Create a new option element
        var optionElement = document.createElement('option');
        let inputan = document.querySelector('.tambahKategoriMenuEdit input');
        const alertElement = document.getElementById('alertSuccessAddKategoriMenuEdit');
        if (inputan.value != '') {
            optionElement.value = inputan.value;
            optionElement.textContent = inputan.value;
            // Append the option element to the select element
            selectElement.appendChild(optionElement);
            alertElement.innerHTML = "Kategori menu baru udah ditambahkan";
            alertElement.classList.remove('d-none');
            alertElement.classList.remove('text-light');
            alertElement.classList.remove('bg-danger'); 
            alertElement.classList.add('text-pink');
            alertElement.classList.add('bg-light');
            document.querySelector('.tambahKategoriMenuEdit input').classList.toggle('d-none');
            document.querySelector('.tambahKategoriMenuEdit button').classList.toggle('d-none');
            inputan.value = '';
        }else{
            alertElement.classList.remove('text-pink');
            alertElement.classList.remove('bg-light');
            alertElement.classList.add('text-light');
            alertElement.classList.add('bg-danger');
            alertElement.innerHTML = "Tidak boleh kosong";
            document.querySelector('.tambahKategoriMenuEdit input').classList.toggle('d-none');
            document.querySelector('.tambahKategoriMenuEdit button').classList.toggle('d-none');
        }
        // Function to show the element with animation
        function showAlert() {
            alertElement.classList.remove('d-none');
            alertElement.classList.add('alert-show');
        }

        // Function to hide the element with animation
        function hideAlert() {
            alertElement.classList.remove('alert-show');
            alertElement.classList.add('alert-fade');
            setTimeout(() => {
                alertElement.classList.add('d-none');
                alertElement.classList.remove('alert-fade');
                document.querySelector('.tambahKategoriMenuEdit input').classList.toggle('d-none');
                document.querySelector('.tambahKategoriMenuEdit button').classList.toggle('d-none');
            }, 300); // Adjust the timing to match your transition duration
        }
        // Show the alert
        showAlert();

        // Hide the alert after 3 seconds
        setTimeout(hideAlert, 3000);
    }
    function tambahJualEdit(edit) {
        let id = "#hargaJualEdit";
        let borderColor = "border-light text-light";
        
        let hargaJual = '<div class="row"><div class="col-6"><label for="jumlahBarang" class="">Jumlah Barang</label><input type="number" class="form-control border '+borderColor+' mb-4" id="jumlahBarangEdit"></div><div class="col-6"><label for="harga" class="">Harga</label><input type="text" class="form-control border '+borderColor+' mb-4" id="hargaBarangEdit"></div></div>';
        document.querySelector(id).insertAdjacentHTML('beforeend', hargaJual);
    } 

    function submitFormEdit() {
        let id = document.querySelector("#i").value;
        let nama = document.querySelector("#namaEdit").value;
        let kategoriProduk = document.getElementById('kategori_produkEdit').value;
        let jumlahBarangs = [];
        let hargaBeli = document.querySelector("#hargaBeliEdit").value;
        let dimensi = document.querySelector("#dimensiEdit").value;
        let berat = document.querySelector("#beratEdit").value;
        let stock = document.querySelector("#stockEdit").value;
    
        document.querySelectorAll("#jumlahBarangEdit").forEach((jumlahBarang, index) => {
            let jumlahBarangValue = jumlahBarang.value;
            jumlahBarangs.push({ jumlah: jumlahBarangValue });
        });

        document.querySelectorAll("#hargaBarangEdit").forEach((hargaBarang, index) => {
            let hargaBarangValue = hargaBarang.value;
            jumlahBarangs[index].harga = hargaBarangValue;
        });
        // console.log(jumlahBarangs);
        // Mengonversi objek menjadi JSON
        jumlahBarangs = JSON.stringify(jumlahBarangs);

        var dataToSend = { 
            id: id,
            nama: nama.toLowerCase(),
            kategoriProduk: kategoriProduk,
            hargaJual: jumlahBarangs,
            hargaBeli: hargaBeli,
            dimensi: dimensi,
            berat: berat,
            stock: stock,
        };

        fetch('/components/tambah-product/edit.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(dataToSend),
        })
        .then(response => response.json())
        .then(datas => {
            window.location.href = window.location.href;
            document.querySelector('.btn-close').click();
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }
</script>
<?php include $_SERVER['DOCUMENT_ROOT'].'/components/footer/index.php'; ?>