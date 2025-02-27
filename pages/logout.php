<?php
session_start();
unset($_SESSION['username']);
echo "<script>window.location.href = '/pages/login.php'</script>";
?>
<script>
    function getKeranjang(idUser) {
        function cardCart(datas) {
            let cards = '<div>';
            let hargaPesanan = 0;
            let hargaRequest = 0;
            datas.forEach(data => {
                console.log(datas);
                let quantity = data['jumlah'];
                let listHarga = data['harga_jual'];
                let hargaBarang = menentukanHarga(quantity,listHarga);
                let hargaSementara = parseInt(hargaBarang) * quantity;
                hargaPesanan += parseInt(hargaBarang) * quantity;
                let requestElement = '';
                if (JSON.parse(data['request']) != null) {
                    requestElement += '<div><p class="m-0">Request</p>';
                    JSON.parse(data['request']).forEach((request,index) => {
                        requestElement += `<div class="row">
                            <p class="col m-0">${request[1]}</p>
                            <p class="col m-0">${request[0]}</p>
                            <p class="col m-0">Rp ${request[2]}</p>
                        </div>`
                        hargaSementara += request[0] * request[2];
                        hargaRequest += request[0] * request[2];
                    });
                    requestElement += '</div>';
                }
                const hargaSementaraConvert = hargaSementara.toLocaleString('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                });
                cards += `<div class=" pb-3 border-bottom mb-3">
                    <div class="d-flex gap-2 mb-2">
                        <img src="${(data['foto'] != null) ? '/public/foto/md/'+data['foto']+'.jpg' : '/public/404.png'}" alt="" class="" style="width:100px; height:80px; object-fit:cover;">
                        <div class="w-80 position-relative">
                            <div class="d-flex justify-content-end">
                                <div class="w-fit pointer animasi p-2" onclick="hapusDariKeranjang(${data['id']},<?=$userId?>)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                    <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                                    </svg>
                                </div>
                            </div>
                            <p class="m-0 d-none" id="ip">#${data['product_id']}</p>
                            <p class="m-0">${data['nama']}<span class="ms-2">(<span id="hj">${hargaBarang}</span>)</span></p>
                            <p class="m-0 d-none" id="i">${data['id']}</p>
                            <div class="w-100 d-flex border">
                                <div class="w-20 d-flex align-items-center justify-content-center pointer border-end" onclick="aritmatika(this,'k',${data['id']},<?=$userId?>)" target="valueJumlahKeranjang">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-dash" viewBox="0 0 16 16">
                                        <path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8"/>
                                    </svg>
                                </div>
                                <div class="w-60 p-0">
                                    <input autocomplete="off" type="text" class="form-control text-light border-0 outline-0 text-center" name="qty" id="valueJumlahKeranjang${data['id']}" value="${data['jumlah']}" readonly>
                                </div>
                                <div class="w-20 d-flex align-items-center justify-content-center pointer border-start" onclick="aritmatika(this,'t',${data['id']},<?=$userId?>)" target="valueJumlahKeranjang">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    ${requestElement}
                    <div class="d-flex align-items-center justify-content-between fw-bold gap-2 mt-2">
                        <p class="m-0">Subtotal</p>
                        <p class="m-0">${hargaSementaraConvert}</p>
                    </div>
                </div>`;
            });
            let hargaYangHarusDibayar = hargaPesanan + hargaRequest;
            const formattedPrice = hargaYangHarusDibayar.toLocaleString('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            });
            document.querySelector("#priceDisplay").innerHTML = formattedPrice;
            document.querySelector("#price").innerHTML = hargaYangHarusDibayar;
            cards += '</div>';
            return cards;
        }
        var dataToSend = { 
            idUser: idUser
        };
        fetch('/components/keranjang/getKeranjang.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(dataToSend),
        })
        .then(response => response.json())
        .then(datas => {
            if (datas.length > 0) {
                document.querySelector("#namaCustomer").innerHTML = datas[0]['nama'] + ' | ' + datas[0]['alamat'] + ' | ' + datas[0]['wa'];
                document.querySelector("#catatan").innerHTML = datas[0]['note'];
                document.querySelector("#catatanTextarea").innerHTML = datas[0]['note'];
                if (datas[0][2]) {
                    document.querySelector("#ic").innerHTML = datas[0]['id_customer'];
                    document.querySelector("#btn-lanjut").classList.remove("d-none");
                }
                document.querySelector('#cart-produk').innerHTML = '';
                document.querySelector('#cart-produk').innerHTML = cardCart(datas);
            }else{
                document.querySelector('#cart-produk').innerHTML = 'Tidak ada produk di keranjang'
            }
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }
</script>