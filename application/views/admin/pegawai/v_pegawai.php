<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="<?= site_url() ?>/assets/bootstrap-4.6.2/css/bootstrap.min.css">
</head>
<body>
    
    <div class="container">
        <div class="row">
            <div class="col-lg-12 mt-2">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <!-- <a class="navbar-brand" href="#">Presensi</a> -->
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Home <span class="sr-only"></span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url() ?>admin/employee"> Data Pegawai </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"> Kehadiran </a>
                    </li>
                    </ul>
                </div>
            </nav>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th> NIP </th>
                                <th> Nama </th>
                                <th> Pendidikan </th>
                                <th> Nomor HP </th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach($employee as $row){ ?>
                                <tr>
                                    <td><?= $row['number'] ?></td>
                                    <td><a href="<?= site_url() ?>admin/employee/form/<?= $row['id'] ?>"><?= $row['name'] ?></a></td>
                                    <td><?= $row['education'] ?></td>
                                    <td><?= $row['phone_number'] ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    

    <script src="<?= site_url() ?>/assets/bootstrap-4.6.2/css/bootstrap.min.css"></script>
</body>
</html>