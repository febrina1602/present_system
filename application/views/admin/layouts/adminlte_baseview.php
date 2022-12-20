<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Present System </title>

    <?php $this->load->view('admin/layouts/adminlte_header') ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <div class="preloader flex-column justify-content-center align-items-center">
            <img src="<?= site_url() ?>assets/adminlte/dist/img/AdminLTELogo.png" alt="AdminLTELogo" class="animation__shake" height="60" width="60">
        </div>

        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <?php $this->load->view('admin/layouts/adminlte_navbar') ?>
        </nav>

        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <?php $this->load->view('admin/layouts/adminlte_sidebar') ?>
        </aside>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0"><?= $title ?></h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <?php $this->load->view($content) ?>
                </div>
            </section>
        </div>

        <footer class="main-footer">
            <strong>Copyright &copy; 2022 <a href="https://dynasis.web.id">wtfychxx</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 3.2.0
            </div>
        </footer>

        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
    </div>
</body>

</html>