<div class="col-lg-12">
    <div class="container-fluid">
        <div class="row">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr align="center">
                            <th colspan="2"> Absensi Hari Ini </th>
                        </tr>

                        <tr align="center">
                            <th> Absen Masuk </th>
                            <th> Absen Keluar </th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr align="center">
                            <?php if (isset($todayPresence)) { ?>
                                <?php if (!is_null($todayPresence->timeIn)) { ?>
                                    <td class="<?= ($todayPresence->timeIn > $todayPresence->shiftStart) ? 'text-danger' : 'text-success' ?>"><?= $todayPresence->timeIn ?></td>
                                <?php } else { ?>
                                    <td><a href="<?= site_url() ?>admin/presence/form/1" class="btn btn-primary"> Absen </a></td>
                                <?php } ?>

                                <?php if (!is_null($todayPresence->timeOut)) { ?>
                                    <td class="<?= ($todayPresence->timeOut < $todayPresence->shiftEnd) ? 'text-danger' : 'text-success' ?>"><?= $todayPresence->timeOut ?></td>
                                <?php } else { ?>
                                    <td><a href="<?= site_url() ?>admin/presence/form/2" class="btn btn-primary"> Absen </a></td>
                                <?php } ?>
                            <?php } else { ?>
                                <td><a href="<?= site_url() ?>admin/presence/form/1" class="btn btn-primary"> Absen </a></td>
                                <td><a href="<?= site_url() ?>admin/presence/form/2" class="btn btn-primary"> Absen </a></td>
                            <?php } ?>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <hr />
        <div class="row">
            <h3><?= $title ?></h3>
        </div>

        <div class="row mt-4">
            <div class="col-lg-12">
                <form id="frmHistory">
                    <div class="row">
                        <div class="col-lg-2">
                            <label class="col-form-label"> Periode Mulai </label>
                        </div>
                        <div class="col-lg-2">
                            <input type="date" name="startDate" id="startDate" class="form-control">
                        </div>

                        <div class="col-lg-2">
                            <label class="col-form-label"> Periode Selesai </label>
                        </div>
                        <div class="col-lg-2">
                            <input type="date" name="endDate" id="endDate" class="form-control">
                        </div>

                        <div class="col-lg-1">
                            <button class="btn btn-primary" id="btnGo" type="submit"> Mulai </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mt-3" id="contentReplace" style="display: none">
            <div class="table-responsive">
                <table class="table table-bordered" id="tableHistory">
                    <thead>
                        <th> Tanggal </th>
                        <th> Jam Masuk </th>
                        <th> Jam Keluar </th>
                        <th> Shift Masuk </th>
                        <th> Shift Keluar </th>
                        <th> Status </th>
                    </thead>

                    <tbody id="showData">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#btnGo').on('click', function(e) {
            $('#frmHistory').validate({
                submitHandler: function() {
                    e.preventDefault()

                    $.ajax({
                        url: "<?= site_url() ?>" + "admin/presence/dataPut",
                        type: 'POST',
                        dataType: 'JSON',
                        data: $('#frmHistory').serialize(),
                        success: function(data) {
                            let html = ''

                            const isDataTable = $.fn.DataTable.isDataTable('#contentReplace');
                            if (isDataTable) {
                                $('#contentReplace').DataTable().clear().destroy();
                            }

                            if(data.length > 0){
                                for(let i = 0; i < data.length; i++){
                                    html += `<tr>
                                        <td>${data[i].date}</td>
                                        <td>${data[i].timeIn}</td>
                                        <td>${data[i].timeOut}</td>
                                        <td>${data[i].shiftIn}</td>
                                        <td>${data[i].shiftOut}</td>
                                        <td>${data[i].status}</td>
                                    </tr>`
                                }
                            }else{
                                html += `<tr>
                                    <td colspan="6">Maaf, belum ada data</td>
                                </tr>`
                            }

                            $('#showData').html(html)
                            $('#contentReplace').show()
                        }
                    })
                }
            })
        })
    })
</script>