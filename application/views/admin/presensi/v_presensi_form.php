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

                                        <div class="row mt-2">
                                            <div class="col-lg-12 d-flex justify-content-center">
                                                <button type="button" class="btn btn-danger" id="btnStop" style="display: none;">Batal</button>
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

    $(document).ready(function() {
        docReady(function() {
            const resultContainer = $('#qr-reader-results')
            let lastResult, countResults = 0

            function onScanSuccess(decodedText, decodedResult) {
                if (decodedText !== lastResult) {
                    ++countResults
                    lastResult = decodedText

                    console.log(`Scan result ${decodedText}`, decodedResult)
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

            $('#btnStop').on('click', function(){
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