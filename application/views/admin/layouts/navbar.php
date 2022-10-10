<nav class="navbar navbar-expand-lg navbar-dark bg-success">
    <!-- <a class="navbar-brand" href="#">Presensi</a> -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item <?= ($this->uri->segment(2) === 'dashboard') ? 'active' : '' ?>">
                <a class="nav-link" href="<?= site_url() ?>admin/dashboard">Home <span class="sr-only"></span></a>
            </li>
            <li class="nav-item <?= ($this->uri->segment(2) === 'employee') ? 'active' : '' ?>">
                <a class="nav-link" href="<?= site_url() ?>admin/employee"> Data Pegawai </a>
            </li>
            <li class="nav-item <?= ($this->uri->segment(2) === 'presence') ? 'active' : '' ?>">
                <a class="nav-link" href="<?= site_url() ?>admin/presence"> Kehadiran </a>
            </li>
        </ul>
    </div>
</nav>