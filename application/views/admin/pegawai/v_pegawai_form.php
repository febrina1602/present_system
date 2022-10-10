<div class="col-lg-12">
    <div class="card">
        <div class="card-header">
            <h3><?= $title ?></h3>
        </div>
        <!-- awal form pegawai -->
        <form class="form-horizontal" id="frmModal">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3">
                        <label class="col-form-label"> ID Pegawai </label>
                    </div>
                    <div class="col-lg-9">
                        <input type="hidden" name="method" id="method" class="form-control" value="add" readonly>
                        <input type="text" name="id" id="id" class="form-control" readonly>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-lg-3">
                        <label class="col-form-label"> Nama </label>
                    </div>
                    <div class="col-lg-9">
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-lg-3">
                        <label class="col-form-label"> NIP </label>
                    </div>
                    <div class="col-lg-9">
                        <input type="text" name="number" id="number" class="form-control" required>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-lg-3">
                        <label class="col-form-label"> Jenis Kelamin </label>
                    </div>

                    <div class="col-lg-9">
                        <select name="gender" id="gender" class="form-control" required>
                            <option value="">- pilih jenis kelamin -</option>
                            <option value="Pria"> Pria </option>
                            <option value="Wanita"> Wanita </option>
                        </select>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-lg-3">
                        <label class="col-form-label"> Tanggal Lahir </label>
                    </div>

                    <div class="col-lg-3">
                        <input type="date" name="birthDate" id="birthDate" class="form-control" required>
                    </div>

                    <div class="col-lg-3">
                        <label class="col-form-label"> No HP </label>
                    </div>

                    <div class="col-lg-3">
                        <div class="input-group">
                            <div class="input-group-append">
                                <span class="input-group-text">+62</span>
                            </div>
                            <input type="text" name="phoneNumber" id="phoneNumber" class="form-control">
                        </div>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-lg-3">
                        <label class="col-form-label"> Pendidikan Saat Ini </label>
                    </div>

                    <div class="col-lg-9">
                        <input type="text" name="education" id="education" class="form-control">
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-lg-3">
                        <label class="col-form-label"> Tingkatan </label>
                    </div>

                    <div class="col-lg-9">
                        <select name="level" id="level" class="form-control" required>
                            <option value="">- pilih level -</option>
                            <option value="IIIA"> IIIA </option>
                            <option value="IVA"> IVA </option>
                        </select>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-lg-3">
                        <label class="col-form-label"> Alamat </label>
                    </div>

                    <div class="col-lg-9">
                        <textarea name="address" id="address" cols="30" rows="3" class="form-control"></textarea>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <div class="row">
                    <div class="col-lg-12">
                        <button class="btn btn-primary float-right" id="btnSave" type="submit"> Simpan </button>
                    </div>
                </div>
            </div>
        </form>
        <!-- akhir form pegawai -->
    </div>
</div>

<script>
    // fungsi untuk mendapatkan id pegawai
    const getEmployeeId = () => {
        $.ajax({
            url: "<?= site_url() ?>admin/employee/generateId",
            type: 'GET',
            dataType: 'JSON',
            success: function(data){
                // jika sukses mengenerate id pegawai
                if(data.status === 'success'){
                    $('#id').val(data.id)
                }
            }
        })
    }

    $(document).ready(function(){
        /*
            jika id tidak kosong maka semua datanya diset ke form
            data didapatkan dari controllers employee method form
            id didapatkan dari controllers employee dengan variable $data['id']
        */
        if("<?= $id ?>" !== ''){
            const employeeData = <?= $employeeData ?>

            $('#id').val(employeeData.id)
            $('#name').val(employeeData.name)
            $('#number').val(employeeData.number)
            $('#gender').val(employeeData.gender)
            $('#birthDate').val(employeeData.birthDate)
            $('#phoneNumber').val(employeeData.phoneNumber)
            $('#education').val(employeeData.education)
            $('#level').val(employeeData.level)
            $('#address').val(employeeData.address)
            $('#method').val('edit')
        }else{
            // menjalankan fungsi generate id
            getEmployeeId()
        }

        // menyimpan data ketika tombol di click
        $('#btnSave').on('click', function(e){
            $('#frmModal').validate({
                submitHandler: function(){
                    // untuk mencegah window tereload ketika tombol di click
                    e.preventDefault()

                    $.ajax({
                        url: "<?= site_url() ?>admin/employee/saveData",
                        type: 'POST',
                        dataType: 'JSON',
                        data: $('#frmModal').serialize(),
                        success: function(data){
                            // jika sukses menginput data
                            if(data.status === 'success'){
                                Swal.fire({
                                    icon: 'success',
                                    title: data.message
                                }).then(() => {
                                    window.location.replace('<?= site_url() ?>admin/employee')
                                })
                            }else{
                                Swal.fire({
                                    icon: 'warning',
                                    title: data.message
                                })
                            }
                        }, error: function(jqXHR, textStatus, errorThrown){
                            Swal.fire({
                                icon: 'warning',
                                title: 'Ups..ada masalah pada server!'
                            })

                            console.error(`${textStatus}, error message: ${errorThrown}`)
                        }
                    })
                }
            })
        })
    })
</script>