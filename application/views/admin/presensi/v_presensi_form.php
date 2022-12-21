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
                                                    <button type="button" class="btn" style="background-color:#476788;color: #fff" id="btnScan">Pindai Sekarang</button>
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
                                                <input type="file" accept="image/*;capture=camera" id="photo">
                                            </div>

                                            <div class="row mt-2">
                                                <img src="" class="img-fluid" id="imageFrame">
                                            </div>

                                            <div class="row mt-2">
                                                <button type="button" class="d-none btn btn-success" id="btnSave" style="display: none;">Save</button>
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

        locations.longitude = coordinates.longitude
        locations.latitude = coordinates.latitude
        // alert(`${coordinates.latitude} - ${coordinates.longitude}`)

        // console.log(`${coordinates.latitude} - ${coordinates.longitude}`)
        // console.log(locations)   
    }

    function error(err) {
        console.warn(`${err.code}: ${err.message}`)
    }

    const options = {
        enableHighAccuracy: true,
        timeout: 10000,
        maximumAge: 5000
    }

    function getPosition(options){
        return new Promise((resolve, reject) => {
            navigator.geolocation.getCurrentPosition(resolve, reject, options)
        })
    }

    $(document).ready(async function() {
        // const currentLatitude = -6.203072698048657
        // const currentLongitude = 107.01945306759832

        const position = await getPosition(options)
        const locations = {
            longitude: position.coords.longitude,
            latitude: position.coords.longitude
        }
        
        docReady(function() {
            const currentLatitude = locations.latitude
            const currentLongitude = locations.longitude

            console.log(locations)
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

            Html5Qrcode.getCameras().then(devices => {
                if (devices && devices.length) {
                    var cameraId = devices[0]
                }
            }).catch(err => {
                console.error(err)
                alert(`Don't have any access to the cameras`)
            })

            // console.log(cameraId)


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

        $('#photo').on('change', function(e) {
            const files = e.target.files[0]
            console.log(console.log(files.type))
            const allowedFile = ['image/png', 'image/jpg', 'image/jpeg']
            let isValid = false

            allowedFile.map((entry, i) => {
                if (entry === files.type) {
                    isValid = true
                }
            })

            if (!isValid) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Format file yang anda unggah tidak didukung'
                }).then(() => {
                    $(this).val('')
                })

                return
            }

            const reader = new FileReader()
            reader.onload = function(e) {
                $('#imageFrame').attr('src', e.target.result)
            }

            reader.readAsDataURL(files)

            $('#btnSave').removeClass('d-none')
            $('#btnSave').show()
        })

        $('#btnSave').on('click', function(e) {
            console.log(e)
            const formData = new FormData()
            formData.append('functionKey', $('#functionKey').val())
            formData.append('latitude', currentLatitude)
            formData.append('longitude', currentLongitude)
            formData.append('photo', document.getElementById('photo').files[0])

            console.log(formData)

            $.ajax({
                url: "<?= site_url() ?>" + 'admin/presence/saveAttendance',
                type: 'POST',
                dataType: 'JSON',
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                success: function(data) {
                    if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: data.message
                        }).then(() => {
                            window.location.replace("<?= site_url() ?>" + 'admin/history');
                        })
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: data.message
                        })
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error(`Couldn't get the posts: ${textStatus}, message: ${errorThrown}`)
                    Swal.fire({
                        icon: 'error',
                        title: 'Ada masalah saat menghubungi server!'
                    })
                }
            })
        })
    })
</script>