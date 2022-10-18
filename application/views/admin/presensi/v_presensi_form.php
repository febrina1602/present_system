<div class="col-lg-12 ps-md-0">
    <div class="row g-0">
        <div class="col-lg-12">
            <div class="d-flex align-items-center py-5">
                <div class="container">
                    <div class="row">
                        <div class="col-md-11 col-lg-9 mx-auto">
                            <div class="card shadow-lg rounded-4">
                                <div class="card-header text-center">
                                    <h3 class="card-title">Pindai QR</h3>
                                </div>
                                <div class="card-body">
                                    <form id="frmPresence">
                                        <div class="qrcode-form">
                                            <div class="row">
                                                <div class="col-lg-12 d-flex justify-content-center">
                                                    <button type="button" class="btn btn-info" id="btnScan">Pindai Sekarang</button>
                                                    <div class="spinner-border text-info" role="status" id="loading" style="display: none;">
                                                        <span class="sr-only">Loading...</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-lg-12 d-flex flex-column align-items-center">
                                                    <div id="qr-reader" style="width:500px"></div>
                                                    <div id="qr-reader-results"></div>
                                                </div>
                                            </div>

                                            <div class="d-none">
                                                <input type="text" name="longitude" id="longitude">
                                                <input type="text" name="latitude" id="latitude">
                                                <input type="text" name="functionKey" id="functionKey" value="<?= $functionkey ?>">
                                            </div>

                                            <div class="row mt-2">
                                                <div class="col-lg-12 d-flex justify-content-center">
                                                    <button type="button" class="btn btn-danger" id="btnStop" style="display: none;">Batal</button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="photo-form" style="display: none">
                                            <div class="row mt-2">
                                                <input type="file" accept="image/*;capture=camera">
                                            </div>
                                            <div class="row mt-2">
                                                <button type="submit" class="d-none btn btn-danger" id="btnSave" style="display: none;">Batal</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= site_url() ?>assets/qrcodescanner/qrcode-scanner.js" type="text/javascript"></script>

<script>
    function docReady(fn) {
        if (document.readyState === "complete" || document.readyState === "interactive") {
            setTimeout(fn, 1)
        } else {
            document.addEventListener("DOMContentLoaded", fn)
        }
    }

    function success(position) {
        const coordinates = position.coords
        console.log(coordinates)

        // console.log(`${coordinates.latitude} - ${coordinates.longitude}`)
    }

    function error(err) {
        console.warn(`${err.code}: ${err.message}`)
    }

    const options = {
        enableHighAccuracy: true,
        timeout: 5000,
        maximumAge: 0
    }

    $(document).ready(function() {
        const currentLatitude = -6.203072698048657
        const currentLongitude = 107.01945306759832

        navigator.geolocation.getCurrentPosition(success, error, options)

        docReady(function() {
            const resultContainer = $('#qr-reader-results')
            let lastResult, countResults = 0

            function onScanSuccess(decodedText, decodedResult) {
                if (decodedText !== lastResult) {
                    ++countResults
                    lastResult = decodedText

                    const longlat = decodedResult.decodedText.split(', ')
                    const latitude = longlat[0]
                    const longitude = longlat[1]

                    const x = 111.12 * (latitude - currentLatitude)
                    const y = 111.12 * (longitude - currentLongitude) * Math.cos(latitude / 92.215)
                    const coordinates = Math.sqrt((x * x) + (y * y))

                    if (parseFloat(coordinates) > parseFloat(0.01)) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Anda diluar radius SMKN 5'
                        })
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses'
                        })

                        $('#longitude').val(longitude)
                        $('#latitude').val(latitude)

                        $('.qrcode-form').hide()
                        $('.photo-form').show()
                        $('#btnStop').click()
                    }
                    // console.log(`Scan result ${decodedText}`, decodedResult)

                    /*
                        x = 111.12 * (latwo::float - p_latitude);
                        y = 111.12 * (lonwo::float - p_longitude) * cos(latwo::float / 92.215);
                        geoloc := sqrt(x * x + y * y);
                    */
                }
            }

            const html5Qrcode = new Html5Qrcode("qr-reader")


            $('#btnScan').on('click', async function(e) {
                $(this).hide()
                $('#loading').show()

                await html5Qrcode.start({
                    facingMode: "user"
                }, {
                    fps: 10,
                    qrbox: 250
                }, onScanSuccess)

                $('#loading').hide()
                $('#btnStop').show()
            })

            $('#btnStop').on('click', function() {
                html5Qrcode.stop()
                $(this).hide()
                $('#btnScan').show()
            })

            // const html5QrcodeScanner = new Html5QrcodeScanner("qr-reader", {
            //     fps: 10,
            //     qrbox: 250
            // })

            // html5QrcodeScanner.render(onScanSuccess)
            // $('#qr-reader__camera_permission_button').addClass('btn').addClass('btn-info')

        })
    })
</script>