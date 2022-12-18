<div class="col-lg-12">
    <!-- tombol add -->
    <div class="row">
        <div class="col-lg-6">
            <h3><?= $title ?></h3>
        </div>
        <div class="col-lg-6">
            <a href="<?= site_url() ?>admin/employee/form" class="btn btn-primary float-right"> Tambah </a>
        </div>
    </div>

    <div class="table-responsive mt-4">
        <!-- ini akan tampil ketika data yang diterima kosong -->
        <div class="alert alert-danger error-message">Maaf, belum ada data</div>
        <!-- ini akan tampil ketika data yang diterima tidak kosong -->
        <table class="table table-striped" id="tableData" style="display: none">
            <thead>
                <tr>
                    <th>ID Pegawai</th>
                    <th> NIP </th>
                    <th> Nama </th>
                    <th> NIK </th>
                    <th> Nomor KK </th>
                    <th> Jenis Kelamin </th>
                    <th> Tempat Lahir </th>
                    <th> Tanggal Lahir </th>
                    <th> Pendidikan </th>
                    <th> Golongan </th>
                    <th> Alamat </th>
                    <th> Nomor HP </th>

                </tr>
            </thead>

            <tbody id="showData">

            </tbody>
        </table>
    </div>
</div>

<script>
    // mengambil data pegawai dari controller via AJAX
    const getData = () => {
        $.ajax({
            url: "<?= site_url() ?>admin/employee/dataPut",
            type: 'GET',
            dataType: 'JSON',
            success: function(data) {
                let html = ''

                if(data.length > 0){
                    $('#tableData').show()
                    $('.error-message').hide()

                    for (let i = 0; i < data.length; i++) {
                        html += `
                                <tr>
                                    <td>${data[i]['id']}</td>
                                    <td>${data[i]['number']}</td>
                                    <td><a href="${"<?= site_url() ?>admin/employee/form/"+data[i]['id']}">${data[i]['name']}</a></td>
                                    <td>${data[i]['nik']}</td>
                                    <td>${data[i]['kk']}</td>
                                    <td>${data[i]['gender']}</td>
                                    <td>${data[i]['birthPlace']}</td>
                                    <td>${data[i]['birth_date']}</td>
                                    <td>${data[i]['education']}</td>
                                    <td>${data[i]['level']}</td>
                                    <td>${data[i]['address']}</td>
                                    <td>${data[i]['phone_number']}</td>
                                </tr>
                            `
                    }

                    // disini data yang sudah diolah ditampilkan ke table
                    $('#showData').html(html)
                    $('#tableData').dataTable({
                        dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
                            "<'row'<'col-sm-12'tr>>" +
                            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                        // dom: 'Bfrtip',
                        buttons: [
                            //'copy',
                            {
                                extend: 'excel',
                                title: 'Daftar Pegawai SMK Negeri 5 Kota Bekasi',
                                // messageTop: 'The information in this table is copyright to Sirius Cybernetics Corp.'
                            }
                        ]
                    })

                    $('.dt-button').addClass('btn').addClass('btn-success')
                }else{
                    $('#tableData').hide()
                    $('.error-message').show()
                }

            }
        })
    }

    $(document).ready(function() {
        getData()
    })
</script>