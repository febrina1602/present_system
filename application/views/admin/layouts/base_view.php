<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMKN 5 Presensi</title>
    <!-- meload semua asset yang dibutuhkan -->
    <?php $this->load->view('admin/layouts/header')  ?>
</head>

<body>
    <!-- meload navbar -->
    <?php $this->load->view('admin/layouts/navbar'); ?>
    
    <div class="container-fluid">
        <!-- meload content -->
        <section>
            <div class="row mt-3">
                <?php $this->load->view($content) ?>
            </div>
        </section>
    </div>

</body>

</html>