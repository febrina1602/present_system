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
                    <th> NIP </th>
                    <th> Nama </th>
                    <th> Pendidikan </th>
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
                                    <td>${data[i]['number']}</td>
                                    <td><a href="${"<?= site_url() ?>admin/employee/form/"+data[i]['id']}">${data[i]['name']}</a></td>
                                    <td>${data[i]['education']}</td>
                                    <td>${data[i]['phone_number']}</td>
                                </tr>
                            `
                    }

                    // disini data yang sudah diolah ditampilkan ke table
                    $('#showData').html(html)
                    $('#tableData').dataTable()
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