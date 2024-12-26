<?php include $_SERVER['DOCUMENT_ROOT'].'/components/header/index.php'; 
if(!isset($_SESSION["username"])){
    echo "<script>window.location.href = '/pages/login.php'</script>";
}
?>
<?php
$_SESSION['last_url'] = $_SERVER[REQUEST_URI];
?>
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
<style>
    .customerCard {
            /*font-family: 'Orbitron', sans-serif;*/
    }
    .hr {
        border: none;
        height: 1px;
        background-color: white;
        margin: 20px 0;
    }
</style>
<script>
    document.title = 'Cash In';
</script>
<div class="text-dark">
    
    <div class="row justify-content-center h-vh-90 p-1 text-light">
        <div class="h-100 col-4 bg-dark p-4">
            <div class="w-100 h-5 d-flex justify-content-between mb-3">
                <h2 class="text-center">Cash In Today</h2>
                <input type="date" id="tanggal" class="w-fit bg-light form-control" onchange="getCash()" value="<?=Date("Y-m-d")?>">
            </div>
            <div class="h-95">
                <div class="h-90 overflow-auto" id="todayList">
                    
                </div>
                <div class="h-10 d-flex align-items-center justify-content-end border-top border-light">
                    <h5 class="m-0">Jumlah Hari ini : <span id="jumlah">40.000</span></h5>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function toIdr(number) {
        let IDR = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });
        return IDR.format(number);
    }
    function getCash() {
        let tanggal = document.querySelector("#tanggal").value;
        // tanggal = "2024-06-21"
        var datas = { 
            tanggal: tanggal,
            page: 1
        };
        fetch('/pages/cash/in/getCash.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(datas),
        })
        .then(response => response.json()) // Parse the JSON response
        .then(response => {
            let cashIn = '';
            let jumlah = 0;
            let datas = response;
            if (datas.length > 0) {
                datas.forEach(data => {
                    jumlah += parseInt(data['nominal']);
                    cashIn += `<div class="w-100 border border-light p-2 my-2 customerCard d-flex justify-content-between">
                        <small class="m-0">${data['tanggal']}</small>
                        <p class="m-0">${data['nama']}</p>
                    <p class="m-0">${rupiah(data['nominal'])}</p>
                    </div>`;
                });
            }else{
                cashIn += `<div class="w-100 h-100 d-flex align-items-center justify-content-center">
                        <h5 class="">Tidak Ada Data Hari ini</h5>
                    </div>`;
            }
            document.querySelector("#todayList").innerHTML = cashIn;
            document.querySelector("#jumlah").innerHTML = toIdr(jumlah);
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }
    getCash();
</script>