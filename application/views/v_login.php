<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="<?= site_url() ?>assets/bootstrap-4.6.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= site_url() ?>assets/bootstrap-4.6.2/css/floating-labels.css">
    <link rel="stylesheet" href="<?= site_url() ?>assets/sweetalert2/sweetalert2.min.css">
</head>

<body>

    <div class="container ps-md-0">
        <div class="row g-0">
            <div class="col-lg-12">
                <div class="d-flex align-items-center py-5">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-11 col-lg-9 mx-auto">
                                <!-- Mulai Form Login -->
                                <div class="card shadow-lg rounded-4">
                                    <div class="card-body">
                                        <h2 class="my-5 text-center font-bold"> EMS </h2>

                                        <form class="d-flex flex-column align-items-center justify-content-center" id="frmLogin">
                                            <div class="col-lg-11">
                                                <div class="form-label-group">
                                                    <input type="text" class="form-control" name="username" id="floatingInput" placeholder="Masukan NIK Anda" required>
                                                    <label for="floatingInput">Masukan NIK Anda</label>
                                                </div>
                                            </div>

                                            <div class="col-lg-11 mt-3">
                                                <div class="form-label-group">
                                                    <input type="password" class="form-control" name="password" id="floatingPassword" placeholder="Masukan Kata Sandi" required>
                                                    <label for="floatingPassword">Masukan Kata Sandi</label>
                                                </div>
                                            </div>

                                            <div class="d-grid col-lg-11 mb-5">
                                                <button type="submit" class="btn btn-primary btn-block fw-bold mb-2 p-3" id="btnLogin"> Masuk </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- Selesai Form Login -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

<script src="<?= site_url() ?>assets/jquery/jquery-3.6.1.min.js"></script>
<script src="<?= site_url() ?>assets/jquery/jquery-validate/jquery.validate.min.js"></script>
<script src="<?= site_url() ?>assets/bootstrap-4.6.2/js/bootstrap.min.js"></script>
<script src="<?= site_url() ?>assets/sweetalert2/sweetalert2.min.js"></script>

<script>
    $(document).ready(function() {
        // click button untuk login
        $('#btnLogin').on('click', function(e) {
            $('#frmLogin').validate({
                submitHandler: function() {
                    // ini agar form tidak kereload ketika tombol di click
                    e.preventDefault()

                    $.ajax({
                        url: "<?= site_url() ?>/home/authCheck",
                        type: 'POST',
                        dataType: 'JSON',
                        data: $('#frmLogin').serialize(),
                        success: function(data) {
                            // jika username dan password benar
                            if (data.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: data.message
                                }).then(() => {
                                    // disini akan berjalan ketika tombol oke di click

                                    // redirect page ke dashboard
                                    window.location.replace("<?= site_url() ?>/admin/dashboard")
                                })
                            } else {
                                Swal.fire({
                                    icon: 'warning',
                                    title: data.message
                                })
                            }
                        }
                    })
                },
                errorElement: "div",
                errorPlacement: function(error, element) {
                    error.addClass("invalid-feedback");
                    error.insertAfter(element);
                },
                messages: {
                    username: "Username wajib diisi!",
                    password: "Kata sandi wajib diisi!"
                },
                highlight: function(element) {
                    $(element).removeClass('is-valid').addClass('is-invalid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid').addClass('is-valid');
                }
            })
        })
    })
</script>

</html>