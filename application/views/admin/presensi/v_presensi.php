<div class="col-lg-12">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body d-flex justify-content-center">
                        <div class="row">
                            <div class="col-lg-12 d-flex justify-content-center">
                                <label id="clock" class="control-label col-md-12" style="text-align: center;">
                                    <span id="Day"><?= date('d') ?></span>
                                    <span id="TextMonth"><?= date('F') ?></span>
                                    <span id="Month" style="display: none;"><?= date('m') ?></span>
                                    <span id="Year"><?= date('Y') ?></span>
                                    <span id="Hours"><?= date('H') ?></span>
                                    <span id="Minutes"><?= date('i') ?></span>
                                    <span id="Seconds"><?= date('s') ?></span>
                                </label>
                            </div>
                            <div class="col-lg-12 d-flex justify-content-center">
                                <div class="button-group">
                                    <a href="<?= site_url() ?>admin/presence/form/1" class="btn btn-info"> Masuk </a>
                                    <a href="<?= site_url() ?>admin/presence/form/2" class="btn btn-info"> Keluar </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $.getJSON('https://api.ipify.org?format=json', function(json) {
            $.ajax({
                url: "<?= site_url() ?>admin/presence/getTimeZoneFromIpAddress/" + json.ip,
                type: 'POST',
                success: function(data) {
                    if (data !== '') {
                        if (data == 8) {
                            const now = new Date()
                            const time8 = now.getHours() + 1
                            $('#Hours').text(time8)
                        }
                    }
                }
            })
        })

        setInterval(function() {
            let seconds = $("#Seconds").text()
            let minutes = $("#Minutes").text()
            let hours = $("#Hours").text()

            if (hours == 0 && minutes == 0 && seconds == 0) {
                $.get("<?= site_url() ?>admin/presence/getTime", function(data) {
                    $('#Clock').html(data)
                })
                return 0
            }

            seconds++
            if (seconds > 59) {
                seconds = 0
                minutes++
            }

            if (minutes > 59) {
                minutes = 0
                hours++
            }

            if (hours > 23) {
                hours = 0
            }

            seconds = seconds + ""
            if (seconds.length < 2) {
                seconds = "0" + seconds
            }

            minutes = minutes + ""
            if (minutes.length < 2) {
                minutes = "0" + minutes
            }

            hours = hours + ""
            if (hours.length < 2) {
                hours = "0" + hours
            }

            $('#Seconds').text(seconds)
            $('#Minutes').text(minutes)
            $('#Hours').text(hours)
        }, 1000)
    })
</script>