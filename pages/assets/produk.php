<?php
session_start();
if (isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    }else{
        echo "<script>window.location.href = '/pages/assets/'</script>";
    }
}else{
    echo "<script>window.location.href = '/pages/assets/'</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Batch File Upload</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <style>
        #preview img {
            width: 100px; /* Atur lebar gambar */
            margin: 5px;
        }
        canvas { 
            border: 1px solid black; 
            cursor: crosshair; 
            margin: auto;
        }
        #textInput{
            display: none;
            border: none;
            overflow: auto;
            outline: none;
            width: fit-content;
            height: fit-content;
            -webkit-box-shadow: none;
            -moz-box-shadow: none;
            box-shadow: none;

            resize: none; /*remove the resize handle on the bottom right*/
        }
        textarea::placeholder {
            color: white; /* Ganti dengan warna yang diinginkan */
            opacity: 1; /* Pastikan terlihat dengan jelas */
        }
        #textInput::-webkit-scrollbar {
            display: none;
        }
        #palette {
            position: absolute;
            background: white;
            border: 1px solid #ccc;
            padding: 5px;
            display: flex;
            gap: 5px;
        }

        #palette.hidden {
            display: none;
        }

        #palette>button {
            width: 20px;
            height: 20px;
            border: none;
            cursor: pointer;
        }

        #fw {
            background-color: black;
            position: absolute;
            padding: 8px;
            /* border: 1px solid #ccc; */
            display: flex;
            gap: 5px;
        }

        #fw.hidden {
            display: none;
        }

        #fw>button {
            width: 30px;
            height: 30px;
            border: none;
            cursor: pointer;
        }

        .color-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        .color-box {
            width: 200px;
            height: 100px;
            margin: 20px auto;
            border-radius: 10px;
            border: 2px solid #000;
        }
        .slider-text {
            -webkit-appearance: none;
            width: 80%;
            height: 15px;
            border-radius: 10px;
            outline: none;
            opacity: 0.9;
            transition: opacity 0.2s;
        }
        
        #hueSlider {
            background: linear-gradient(to right, red, orange, yellow, green, cyan, blue, violet);
        }
        #lightnessSlider {
            background: linear-gradient(to right, black, gray, white);
        }
        
        .slider-text::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 20px;
            height: 20px;
            background: white;
            border: 2px solid black;
            border-radius: 50%;
            cursor: pointer;
        }
        .slider-text::-moz-range-thumb {
            width: 20px;
            height: 20px;
            background: white;
            border: 2px solid black;
            border-radius: 50%;
            cursor: pointer;
        }

    </style>
</head>
<body>
    <?php include $_SERVER['DOCUMENT_ROOT'].'/components/header/navbar2.php'; ?>
    <div class="container p-5" style="width:90%;">
        <?php
        include $_SERVER['DOCUMENT_ROOT'].'/function/db.php';
        if (isset($_POST['upload-barcode'])) {
            $id = $_POST['id'];
            $barcode = $_POST['barcode'];
            $nama = $_POST['nama'];
            $update = $db->query("UPDATE `produk` SET `id_barcode` = '$barcode', `nama` = '$nama' WHERE `id` = '$id'");
            echo "<script>window.location.href = window.location.href</script>";
        }
        $produk = $db->query("SELECT *  FROM `produk` WHERE `id` = '$id'")->fetch_assoc();
        $nama = $produk['nama'];
        $deskripsi = $produk['talking_point'];
        $fotos = $db->query("SELECT * FROM `foto` WHERE `id_produk` = '$id' AND `is_active` = 1 ORDER BY `id` DESC");
        ?>
        <div class="d-flex justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <a href="/pages/assets/" class="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-chevron-double-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8.354 1.646a.5.5 0 0 1 0 .708L2.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0"/>
                        <path fill-rule="evenodd" d="M12.354 1.646a.5.5 0 0 1 0 .708L6.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0"/>
                    </svg>
                </a>
                <h1 class="m-0">Upload Foto Dan Video <span id="namaEdit<?=$id?>" class="text-success"><?=$nama?></span></h1>
            </div>
            <input type="range" class="w-fit form-control border border-dark" id="controlWidth" oninput="controlWidth('potoAwal',this)" onchange="controlWidth('potoAwal',this)" style="width:fit-content; height:fit-content;" value="200" min="200" max="300">
        </div>
        <h3 class="">Foto Produk</h3>
        <div class="row">
            <?php if ($fotos->num_rows > 0) : ?>
            <div class="col-7 d-flex align-items-center flex-wrap gap-5 mb-5">
                <?php foreach ($fotos as $key => $foto) : ?>
                    <?php 
                    $is_cover = $foto['is_cover'];
                    if($foto['is_video'] == 1) :
                    ?>
                    <video controls style="width:200px; height:200px;">
                        <source src="/public/foto/lg/<?=$foto['id']?>.mp4" type="video/mp4">
                    </video>
                    <?php else : ?>
                        <?php 
                        $idGambar = $foto['id'];
                        $is_cover = $foto['is_cover'];
                        ?>
                        <div class="position-relative">
                            <div class="position-absolute z-100 bg-success text-light rounded-circle pointer" style="top:-10px; right:-10px; cursor:pointer;" onclick="deletePoto(<?=$foto['id']?>)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                                </svg>
                            </div>
                            <img src="/public/foto/lg/<?=$foto['id']?>.jpg" alt="" id="potoAwal" class="" style="width:200px;">
                            <div class="position-absolute w-fit p-1 bg-warning pointer" onclick="isFavorite(<?=$idGambar?>,<?=$id?>)" style="bottom:0px; right:0px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-heart-fill <?=($is_cover == 1) ? 'text-danger' : ''?>" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314"/>
                                </svg>
                            </div>
                        </div>
                    <?php endif ?>
                <?php endforeach; ?>
            </div>
            <?php else : ?>
            <div class="w-full bg-dark text-danger p-3 mb-5">
                <h5 class="">Tidak ada foto atau video produk</h5>
            </div>
            <?php endif; ?>
        </div>
        <div class="">
            <div class="d-flex justify-content-between">
                <h3>Uploaded Images:</h3>
                <input type="range" class="w-fit form-control border border-dark" id="controlWidth" oninput="controlWidth('potoPrev',this)" onchange="controlWidth('potoPrev',this)" style="width:fit-content; height:fit-content;" value="100" min="100" max="400">
            </div>
            <div class="d-flex gap-2" id="preview"></div>
            
            <div id="response"></div>
            <form id="uploadForm" enctype="multipart/form-data">
                <input type="text" class="form-control d-none" name="idProduk" value="<?=$id?>">
                <input type="text" class="form-control d-none" name="idUser" value="<?=$userId?>">
                <input type="file" class="form-control" id="fileInput" name="files[]" multiple accept="*" />
                <div class="d-flex justify-content-end mt-3" style="width:100%">
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
            <div class="position-absolute d-flex flex-column justify-content-center align-items-center bg-danger top-0 start-0 end-0 bottom-0 d-none" id="canvasOverlay" style="z-index:1021;">
                <canvas id="drawingCanvas" width="1200" height="1200" class="border border-dark"></canvas>
                <div class="position-absolute d-flex flex-column align-items-center">
                    <textarea class="text-light fw-bold p-3 bg-transparent border border-0" name="" id="textInput" rows="" placeholder="Tulis teks..." style="font-size:25px;"></textarea>
                    <!-- <button class="btn btn-success mb-5" id="addText">Add Text</button> -->
                </div>
                <button class="btn btn-success mb-5" id="saveBtn">Save as Image</button>
                <div id="fw" class="">
                    <button class="bg-light text-dark" style="font-weight:bold;" onclick="document.querySelector('#text-color-pallete').classList.toggle('d-none')">
                        <img src="/public/icon/fontColor.png" alt="">
                    </button>
                    <button class="bg-light text-dark" style="font-weight:bold;" onclick="changeFW('bold')">
                        <span class="">B</span>
                    </button>
                    <button class="bg-light text-dark" style="font-weight:normal;" onclick="changeFW('normal')">
                        <span class="">N</span>
                    </button>
                    <button class="bg-light text-dark" style="font-style:italic;" onclick="changeFW('italic')">
                        <span class="">I</span>
                    </button>
                    <button class="bg-light text-dark" style="text-decoration:underline;" onclick="changeFW('underline')">
                        <span class="">U</span>
                    </button>
                    <button class="bg-light text-dark" style="" onclick="changeFW('uppercase')">
                        <span class="">aA</span>
                    </button>
                </div>
                <div class="position-absolute start-0 p-4 h-100 bg-light d-none" id="text-color-pallete" style="width:30%;">
                <div class="color-container">
                    <h5 class="">Text Color</h5>
                    <label for="hueSlider">Hue</label>
                    <input type="range" id="hueSlider" min="0" max="360" value="0" class="slider-text w-100">
                    <label for="lightnessSlider">Lightness</label>
                    <input type="range" id="lightnessSlider" min="0" max="100" value="50" class="slider-text w-100">
                    <p id="colorCode">#FF0000</p>
                </div>
                </div>
                <div id="palette" class="hidden">
                    <button style="background-color: white;" onclick="changeColor('white')"></button>
                    <button style="background-color: black;" onclick="changeColor('black')"></button>
                    <button style="background-color: red;" onclick="changeColor('red')"></button>
                    <button style="background-color: blue;" onclick="changeColor('blue')"></button>
                    <button style="background-color: green;" onclick="changeColor('green')"></button>
                    <button style="background-color: yellow;" onclick="changeColor('yellow')"></button>
                    <button style="background-color: pink;" onclick="changeColor('pink')"></button>
                    <button style="background-color: #a13206;" onclick="changeColor('#a13206')"></button>
                </div>
            </div>

            <script>
                const canvas = document.getElementById('drawingCanvas');
                const ctx = canvas.getContext('2d');
                ctx.strokeStyle = 'red';
                const palette = document.getElementById("palette");
                const fw = document.getElementById("fw");
                let drawing = false;

                function resizeCanvas(width, height) {
                    canvas.width = width;
                    canvas.height = height;
                }

                document.addEventListener('paste', (event) => {
                    const items = (event.clipboardData || event.originalEvent.clipboardData).items;
                    for (const item of items) {
                        if (item.type.indexOf('image') !== -1) {
                            const blob = item.getAsFile();
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                const img = new Image();
                                img.onload = () => {
                                    // Atur ukuran canvas tetap
                                    const fixedWidth = 600;
                                    const fixedHeight = 600;
                                    canvas.width = fixedWidth;
                                    canvas.height = fixedHeight;
                                    // Gambar ulang background sesuai ukuran
                                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                                    ctx.drawImage(img, 0, 0, fixedWidth, fixedHeight);
                                };
                                img.src = e.target.result;
                                document.querySelector("#canvasOverlay").classList.remove("d-none");
                            };
                            reader.readAsDataURL(blob);
                        }
                    }
                });

                canvas.addEventListener('mousedown', (event) => {
                    drawing = true;
                    ctx.beginPath();
                    ctx.moveTo(event.offsetX, event.offsetY);
                });

                canvas.addEventListener('mousemove', (event) => {
                    if (!drawing) return;
                    ctx.lineTo(event.offsetX, event.offsetY);
                    ctx.lineWidth = 10;
                    ctx.lineCap = 'round';
                    ctx.stroke();
                });

                canvas.addEventListener('mouseup', () => {
                    drawing = false;
                });

                canvas.addEventListener('mouseleave', () => {
                    drawing = false;
                });

                // Event untuk menekan Alt + T agar memunculkan text input
                document.addEventListener('keydown', (event) => {
                    if (event.altKey && event.key.toLowerCase() === 't') {
                        showTextInput();
                    }
                });

                function showTextInput() {
                    // Posisi input di tengah canvas
                    const canvasRect = canvas.getBoundingClientRect();
                    textInput.style.left = (canvasRect.left + canvas.width / 2 - 50) + 'px';
                    textInput.style.top = (canvasRect.top + canvas.height / 2 - 10) + 'px';
                    textInput.style.display = 'block';
                    textInput.focus();
                }

                // Auto-resize text area sesuai isinya
                textInput.addEventListener("input", () => {
                    textInput.style.height = "auto";
                    textInput.style.height = textInput.scrollHeight + "px";
                });

                function resetFont() {
                    document.querySelector("#textDiv").style.fontWeight = "normal";
                    document.querySelector("#textDiv").style.fontStyle = "normal";
                    document.querySelector("#textDiv").style.textDecoration = "none";
                }

                function changeFW(fw) {
                    if (document.querySelector("#textDiv")) {
                        let textDiv = document.querySelector("#textDiv").style;
                        
                        if (fw === 'italic') {
                            textDiv.fontStyle = (textDiv.fontStyle === 'italic') ? 'normal' : 'italic';
                        } else if (fw === 'underline') {
                            textDiv.textDecoration = (textDiv.textDecoration === 'underline') ? 'none' : 'underline';
                        } else if (fw === 'bold') {
                            textDiv.fontWeight = (textDiv.fontWeight === 'bold') ? 'normal' : 'bold';
                        } else if (fw === 'uppercase') {
                            textDiv.textTransform = (textDiv.textTransform === 'uppercase') ? 'lowercase ' : 'uppercase';
                        }else{
                            resetFont();
                        }

                        textDiv.width = "fit-content";
                        textDiv.height = "fit-content";
                    } else {
                        alert("Text belum dibuat, buat text terlebih dahulu!"); 
                    }
                }


                textInput.addEventListener("keydown", (event) => {
                    if (event.key === "Enter") {
                        if (event.shiftKey) {   
                            // Shift+Enter untuk baris baru
                        } else {
                            event.preventDefault();
                            const text = textInput.value.trim();
                            if (text !== "") {
                                // Buat elemen teks overlay yang draggable
                                const lines = text.split("\n");
                                const lineHeight = 30;
                                // Gunakan canvas sementara untuk mengukur lebar teks
                                const tempCanvas = document.createElement('canvas');
                                const tempCtx = tempCanvas.getContext('2d');
                                tempCtx.font = "27px Arial";
                                const maxWidth = Math.max(...lines.map(line => tempCtx.measureText(line).width));
                                const boxWidth = maxWidth + 20;
                                const boxHeight = lines.length * lineHeight + 10;
                                
                                // Hitung posisi awal berdasarkan canvas (menggunakan getBoundingClientRect)
                                const canvasRect = canvas.getBoundingClientRect();
                                const x = canvasRect.left + canvas.width / 2.2 - boxWidth / 2;
                                const y = canvasRect.top + canvas.height / 2.2 - 10;
                                
                                const textDiv = document.createElement('div');
                                textDiv.setAttribute("id", "textDiv");
                                textDiv.classList.add('draggable-text');
                                textDiv.style.position = 'absolute';
                                textDiv.style.left = x + 'px';
                                textDiv.style.top = y + 'px';
                                textDiv.style.width = boxWidth + 'px';
                                textDiv.style.height = boxHeight + 'px';
                                textDiv.style.backgroundColor = 'black';
                                textDiv.style.color = 'white';
                                textDiv.style.font = "27px Arial";
                                textDiv.style.padding = '5px 10px';
                                textDiv.style.cursor = 'move';
                                textDiv.style.zIndex = '1022';
                                textDiv.style.userSelect = 'none';
                                textDiv.innerHTML = text.replace(/\n/g, '<br>');
                                
                                document.body.appendChild(textDiv);
                                
                                // Tambahkan fungsi drag & drop
                                dragElement(textDiv);
                            }
                            // Sembunyikan input teks setelah selesai
                            textInput.style.display = "none";
                            textInput.value = "";
                        }
                    }
                });

                // Fungsi untuk membuat elemen menjadi draggable
                function dragElement(elmnt) {
                    let pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
                    elmnt.onmousedown = dragMouseDown;

                    function dragMouseDown(e) {
                        e = e || window.event;
                        e.preventDefault();
                        // Simpan posisi awal kursor
                        pos3 = e.clientX;
                        pos4 = e.clientY;
                        document.onmouseup = closeDragElement;
                        document.onmousemove = elementDrag;
                    }

                    function elementDrag(e) {
                        e = e || window.event;
                        e.preventDefault();
                        // Hitung pergeseran kursor
                        pos1 = pos3 - e.clientX;
                        pos2 = pos4 - e.clientY;
                        pos3 = e.clientX;
                        pos4 = e.clientY;
                        // Update posisi elemen
                        elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
                        elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
                    }

                    function closeDragElement() {
                        document.onmouseup = null;
                        document.onmousemove = null;
                    }
                }

                document.addEventListener("keydown", (event) => {
                    if (event.altKey && event.key.toLowerCase() === "p") {
                        const x = window.innerWidth / 2;
                        const y = window.innerHeight / 2;
                        palette.style.left = `${x}px`;
                        palette.style.top = `${y}px`;
                        palette.classList.remove("hidden");
                    }
                    if (event.altKey && event.key.toLowerCase() === "b") {
                        fw.classList.toggle("hidden");
                    }
                });

                document.addEventListener("click", (event) => {
                    if (!canvas.contains(event.target) && !palette.contains(event.target)) {
                        palette.classList.add("hidden");
                    }
                });

                function changeColor(color) {
                    ctx.strokeStyle = color;
                    ctx.fillStyle = color;
                    palette.classList.add("hidden");
                }

                document.getElementById('saveBtn').addEventListener('click', () => {
                    // Dapatkan posisi canvas di layar
                    const canvasRect = canvas.getBoundingClientRect();
                    
                    // Iterasi setiap elemen teks overlay
                    document.querySelectorAll('.draggable-text').forEach(textDiv => {
                        const textRect = textDiv.getBoundingClientRect();
                        // Hitung posisi relatif overlay terhadap canvas
                        const x = textRect.left - canvasRect.left;
                        const y = textRect.top - canvasRect.top;
                        
                        // Ambil gaya komputasi dari elemen overlay
                        const style = window.getComputedStyle(textDiv);
                        const bgColor = style.backgroundColor; // misal: "black"
                        const textColor = style.color;         // misal: "white"
                        const font = style.font;               // misal: "27px Arial"
                        
                        // Gambar background overlay ke canvas
                        ctx.fillStyle = bgColor;
                        ctx.fillRect(x, y, textRect.width, textRect.height);
                        
                        // Siapkan gaya teks
                        ctx.font = font;
                        ctx.fillStyle = textColor;
                        // Ambil teks dari overlay dan pisahkan per baris
                        const lines = textDiv.innerText.split("\n");
                        // Tentukan tinggi baris; Anda bisa sesuaikan jika perlu
                        const lineHeight = 30; 
                        
                        // Gambar tiap baris teks di canvas, dengan margin misal 10px dari kiri & 5px dari atas
                        lines.forEach((line, index) => {
                            ctx.fillText(line, x + 10, y + 25 + index * lineHeight);
                        });
                    });

                    // Sekarang canvas sudah menggabungkan gambar dan teks overlay,
                    // Lakukan penyimpanan gambar dari canvas
                    canvas.toBlob((blob) => {
                        const formData = new FormData(document.getElementById('uploadForm'));
                        formData.append('files[]', blob, 'canvas-drawing.png');
                        fetch('upload.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then((data) => {
                            // Setelah selesai, misalnya refresh halaman atau tampilkan pesan sukses
                            window.location.href = window.location.href;
                        })
                        .catch(error => console.error('Error:', error));
                    }, 'image/png');
                });
            </script>

            <script>
                const hueSlider = document.getElementById("hueSlider");
                const lightnessSlider = document.getElementById("lightnessSlider");
                const colorCode = document.getElementById("colorCode");

                function updateColor(status = 'text') {
                    const hue = hueSlider.value;
                    const lightness = lightnessSlider.value;
                    const color = `hsl(${hue}, 100%, ${lightness}%)`;
                    colorCode.textContent = color;
                    
                    if (document.getElementById("textDiv")) {
                        document.getElementById("textDiv").style.color = color;
                    }
                }

                hueSlider.addEventListener("input", updateColor);
                lightnessSlider.addEventListener("input", updateColor);
            </script>

            
            <form class="col-5"  method="POST" action="">
                <input type="text" class="form-control" name="nama" value="<?=$nama?>" oninput="document.querySelector('#upload-barcode').classList.remove('d-none')" autocomplete="off">
                <label for="" class="">Barcode</label>
                <div class="d-flex gap-2">
                    <?php
                        $barcode = $db->query("SELECT * FROM `produk` WHERE `id` = $id")->fetch_assoc()['id_barcode'];
                    ?>
                    <input type="text" class="d-none" name="id" value="<?=$id?>">
                    <input type="text" class="form-control border border-dark" name="barcode" oninput="document.querySelector('#upload-barcode').classList.remove('d-none')" value="<?=($barcode) ? $barcode : ''?>" autocomplete="off">
                    <button class="btn btn-primary d-none" id="upload-barcode" name="upload-barcode">Submit</button>
                </div>
            </form>

            <form action="/pages/produk/ubahDeskripsi.php" method="POST" class="col-5 d-none">
                <h5 class="">Talking Point</h5>
                <input type="text" class="d-none" value="<?=$_GET['id'] ?>" name="id">
                <textarea name="deskripsi" id="" cols="" rows="10" class="form-control border-dark"><?=$deskripsi?></textarea>
                <div class="d-flex justify-content-end mt-3" style="width:100%">
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function isFavorite(idGambar, idProduk) {
            let judul = document.querySelector("#namaEdit"+idProduk).innerHTML;
            datas = {
                idGambar : idGambar,
                idProduk : idProduk
            }
            fetch('/components/tambah-product/is_favorit.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(datas),
            })
            .then(response => response.json())
            .then(datas => {
                window.location.href = window.location.href;
            })
            .catch((error) => {
                console.error('Error:', error);
            });
        }
        
        function deletePoto(id) {
            let konfirmasi = confirm("Apakah anda yakin ingin menghapus foto ini?");
            if (konfirmasi) {
                window.location.href = "deletePoto.php?idPoto="+id+'&idProduk='+<?=$id?>;
            }
        }

        function controlWidth(id, input) {
            document.querySelectorAll('#'+id).forEach(element => {
                element.style.width = input.value+'px';
            });
        }

        document.getElementById('fileInput').addEventListener('change', function(event) {
            const preview = document.getElementById('preview');
            preview.innerHTML = ''; // Kosongkan preview sebelumnya

            for (let i = 0; i < event.target.files.length; i++) {
                const file = event.target.files[i];

                // Jika file adalah gambar, tampilkan pratinjau
                if (file.type.startsWith('image/')) {
                    const imgContainer = document.createElement('div');
                    imgContainer.className = 'position-relative';
                    imgContainer.style = 'width: 100px; margin: 5px;';

                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    img.style = 'width: 100px; margin: 5px;';

                    const deleteButton = document.createElement('button');
                    deleteButton.className = 'btn btn-danger btn-sm position-absolute z-index-100 top-0 end-0';
                    deleteButton.style = 'margin-left: 5px;';
                    deleteButton.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                            <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                        </svg>
                    `;

                    // Event listener untuk menghapus file
                    deleteButton.addEventListener('click', function() {
                        const dataTransfer = new DataTransfer();
                        
                        // Salin file yang tersisa ke dataTransfer
                        for (let j = 0; j < event.target.files.length; j++) {
                            if (j !== i) {
                                dataTransfer.items.add(event.target.files[j]);
                            }
                        }

                        event.target.files = dataTransfer.files; // Update input file
                        preview.removeChild(imgContainer); // Hapus pratinjau
                    });

                    imgContainer.appendChild(img);
                    imgContainer.appendChild(deleteButton);
                    preview.appendChild(imgContainer);
                }
            }
        });

        document.getElementById('uploadForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const submitButton = document.querySelector('button[type="submit"]');
            submitButton.disabled = true; // Disable button to prevent double submission

            const formData = new FormData(this);
            
            fetch('upload.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                document.getElementById('response').innerText = `Success: ${data.message}`;
                window.location.href = window.location.href; // Refresh page
            })
            .catch(error => {
                document.getElementById('response').innerText = `Error: ${error.message}`;
                submitButton.disabled = false; // Re-enable button on error
            });
        });

        // Memuat gambar yang telah diunggah sebelumnya (misalnya dari server)
        // function loadPreviousImages() {
        //     // Contoh: Anda dapat memuat gambar dari server
        //     const previousImages = [
        //     ];
        //     const preview = document.getElementById('preview');

        //     previousImages.forEach(imagePath => {
        //         const img = document.createElement('img');
        //         img.src = imagePath; // Ganti dengan path yang sesuai
        //         preview.appendChild(img);
        //     });
        // }

        // // Panggil fungsi untuk memuat gambar sebelumnya
        // loadPreviousImages();
    </script>
</body>
</html>
