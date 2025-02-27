<?php $currentURL = $_SERVER[REQUEST_URI]; ?>
<nav class="navbar navbar-expand-lg bg-success sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand text-light d-none d-md-block" href="/">Navbar</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <div class="w-100 d-md-flex justify-content-between">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active text-light" aria-current="page" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active text-light" href="/pages">Jelajah</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active text-light" id="history" href="/pages/history/">History</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <span class="me-2 fw-bold"><?=$username?></span>    
                        <a href="/pages/logout.php" class="btn btn-danger">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>