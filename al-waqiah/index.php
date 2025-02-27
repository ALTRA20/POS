<?php include $_SERVER['DOCUMENT_ROOT'].'/components/header/index.php';?>
<div class="bg-dark p-5">
  <div class="d-none">
    <audio id="audioPlayer" controls>
      Your browser does not support the audio tag.
    </audio>
  </div>
  <div class="my-5">
    <div class="" style="height:60vh">
      <div class="d-flex align-items-center justify-content-between">
        <div class="col-2">
          <select class="form-select w-fit" aria-label="Default select example">
            <option value="1" selected>Al - Waqiah</option>
          </select>
        </div>
        <div class="col-8">
          <h5 class="text-center fs-3 mb-3" id="judul">HALoooooooooooo</h5>
        </div>
        <div class="col-2 d-flex align-items-center justify-content-between gap-2">
          <select class="form-select w-fit" id="awal" aria-label="Default select example">
            
          </select>
          <span class="">-</span>
          <select class="form-select w-fit" id="akhir" aria-label="Default select example">
            <option value="1" selected>Al - Waqiah</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Slider untuk mengontrol lagu -->
    <input type="range" id="seekBar" value="0" min="0" step="1" class="w-100">

    <div class="d-flex align-items-center justify-content-between px-5 mt-3">
      <div class="pointer">
        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-skip-backward-fill text-info" viewBox="0 0 16 16" onclick="playTrack(currentTrack - 1)">
          <path d="M.5 3.5A.5.5 0 0 0 0 4v8a.5.5 0 0 0 1 0V8.753l6.267 3.636c.54.313 1.233-.066 1.233-.697v-2.94l6.267 3.636c.54.314 1.233-.065 1.233-.696V4.308c0-.63-.693-1.01-1.233-.696L8.5 7.248v-2.94c0-.63-.692-1.01-1.233-.696L1 7.248V4a.5.5 0 0 0-.5-.5"/>
        </svg>
      </div>
      <input type="hidden" id="status" value="0">
      <div class="pointer" id="btn-play" onclick="changeStatus()"></div>
      <div class="pointer" onclick="playNext()">
        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-skip-forward-fill text-info" viewBox="0 0 16 16">
          <path d="M15.5 3.5a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-1 0V8.753l-6.267 3.636c-.54.313-1.233-.066-1.233-.697v-2.94l-6.267 3.636C.693 12.703 0 12.324 0 11.693V4.308c0-.63.693-1.01 1.233-.696L7.5 7.248v-2.94c0-.63.693-1.01 1.233-.696L15 7.248V4a.5.5 0 0 1 .5-.5"/>
        </svg>
      </div>
    </div>
  </div>
</div>

<script>
  // Daftar lagu
  const playlist = ["1.mp3", "2.mp3", "3.mp3"];
  let playListOptionsAwal = ``;
  let playListOptionsAkhir = ``;
  playlist.forEach((py, index) => {
    let judul = py.split('.')[0];
    playListOptionsAwal += `<option value="${index}" ${(index == 0) ? 'selected' : ''}>Ayat ${judul}</option>`;
    playListOptionsAkhir += `<option value="${index}" ${(index == playlist.length - 1) ? 'selected' : ''}>Ayat ${judul}</option>`;
  });
  document.getElementById("awal").innerHTML = playListOptionsAwal;
  document.getElementById("akhir").innerHTML = playListOptionsAkhir;

  let currentTrack = 0;
  const audio = document.getElementById("audioPlayer");
  const seekBar = document.getElementById("seekBar");

  // Fungsi untuk memuat dan memutar lagu
  function playTrack(index) {
    if (index >= playlist.length) index = 0;
    if (index < 0) index = playlist.length - 1;
    
    currentTrack = index;
    audio.src = playlist[currentTrack];
    let judul = playlist[currentTrack].split('.')[0];
    document.getElementById("judul").innerHTML = "Al - Waqiah Ayat " + judul;
    audio.play();
  }

  // Saat lagu selesai, mainkan lagu berikutnya
  audio.addEventListener("ended", () => playTrack(currentTrack + 1));

  // Fungsi untuk tombol "Next"
  function playNext() {
    playTrack(currentTrack + 1);
  }

  // Update slider sesuai progress lagu
  audio.addEventListener("timeupdate", function () {
    seekBar.max = audio.duration || 100; // Set max sesuai durasi lagu
    seekBar.value = audio.currentTime;   // Update slider
  });

  // Saat user geser slider, ubah posisi lagu
  seekBar.addEventListener("input", function () {
    audio.currentTime = seekBar.value;
  });

  // Fungsi Play/Pause
  function changeStatus() {
    const status = document.getElementById("status");
    if (status.value == "0") {
      document.getElementById("btn-play").innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="70" height="70" fill="currentColor" class="bi bi-play-circle-fill text-info" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M6.79 5.093A.5.5 0 0 0 6 5.5v5a.5.5 0 0 0 .79.407l3.5-2.5a.5.5 0 0 0 0-.814z"/>
      </svg>`;
      status.value = "1";
      audio.pause();
    } else {
      document.getElementById("btn-play").innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="70" height="70" fill="currentColor" class="bi bi-pause-circle-fill text-info" viewBox="0 0 16 16">
      <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M6.25 5C5.56 5 5 5.56 5 6.25v3.5a1.25 1.25 0 1 0 2.5 0v-3.5C7.5 5.56 6.94 5 6.25 5m3.5 0c-.69 0-1.25.56-1.25 1.25v3.5a1.25 1.25 0 1 0 2.5 0v-3.5C11 5.56 10.44 5 9.75 5"/>
      </svg>`;
      status.value = "0";
      audio.play();
    }
  }

  // Inisialisasi pertama kali
  playTrack(currentTrack);
  changeStatus();
</script>
<?php include $_SERVER['DOCUMENT_ROOT'].'/components/footer/index.php';?>