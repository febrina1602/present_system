<a href="<?= site_url() ?>admin/dashboard" class="brand-link">
    <img src="<?= site_url() ?>assets/adminlte/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">SMKN 5 Presensi</span>
</a>

<div class="sidebar">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <img src="<?= site_url() ?>assets/adminlte/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
            <a href="#" class="d-block"><?= $this->session->userdata('username') ?></a>
        </div>
    </div>

    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
                <a class="nav-link  <?= ($this->uri->segment(2) === 'dashboard') ? 'active' : '' ?>" href="<?= site_url() ?>admin/dashboard">
                    <i class="nav-icon fas fa-home"></i>
                    <p>
                        Home
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link  <?= ($this->uri->segment(2) === 'employee') ? 'active' : '' ?>" href="<?= site_url() ?>admin/employee">
                    <i class="nav-icon fas fa-user"></i>
                    <p>
                        Data Pegawai
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link  <?= ($this->uri->segment(2) === 'presence') ? 'active' : '' ?>" href="#">
                    <i class="nav-icon fas fa-book"></i>
                    <p>
                        Presensi
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="<?= site_url() ?>admin/presence" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                           <p> Kehadiran </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= site_url() ?>admin/history" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                           <p> Riwayat </p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= site_url() ?>admin/dashboard/logout">
                    <i class="nav-icon fas fa-power-off"></i>
                    <p>
                        Keluar
                    </p>
                </a>
            </li>
        </ul>
    </nav>
</div>