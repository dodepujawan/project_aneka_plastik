<style>
    #qrcode { margin-top: 20px; }

    #previewKamera {
            width: 100%;
            max-width: 300px;
            height: auto;
            border: 1px solid #ccc;
            display: none;
    }
    #pilihKamera {
        max-width: 400px;
        width: 100%;
        padding: 8px;
        margin: 10px 0;
    }
    #hasilscan {
        width: 100%;
        max-width: 400px;
        padding: 8px;
        margin: 10px 0;
    }
    .scanner {
        padding: 10px 15px;
        margin: 5px;
        cursor: pointer;
    }
    .hide-important {
        display: none !important;
    }
</style>
<div id="master_qr" class="container">
    <div id="qr_code" class="hide-important d-flex flex-column align-items-center">
        <h2>Generate QR Code</h2>
        <div id="qrcode"></div>
    </div>


    <div id="qr_scanner" class="hide-important d-flex flex-column align-items-center">
        <h1>QR Code Scanner</h1>
        <video id="previewKamera"></video>
        <br>
        <select id="pilihKamera"></select>
        <br>
        <input type="text" id="hasilscan" placeholder="Hasil scan akan muncul di sini">
        <br>
        <div>
            <button id="startBtn" class="scanner btn btn-success">Mulai Scan</button>
            <button id="stopBtn" class="scanner btn btn-danger" disabled>Stop Scan</button>
            <button id="generateBtn" class="scanner btn btn-primary">Cek Kode</button>
        </div>
    </div>
</div>


<script>
$(document).ready(function() {
    let user_role_select = @json(Auth::user()->roles);
    let user_kode_select = @json(Auth::user()->user_kode);
    let user_nama_select = @json(Auth::user()->name);
    let user_id_select = @json(Auth::user()->user_id);
    // console.log('test :' + user_role_select)
    // ========================== QR Code Conventer =================================
    if(user_role_select == 'customer'){
        $("#qr_code").removeClass("hide-important");
        let qr;

        const today = new Date().toISOString().split('T')[0]; // Format YYYY-MM-DD
        const qrCodeText = `${user_id_select}|${today}`;
        $('#qrcode').empty(); // Hapus QR lama
        qr = new QRCode(document.getElementById("qrcode"), {
            text: qrCodeText,
            width: 200,
            height: 200,
        });
    }
    // ======================== End of QR Code Conventer ====================================
    // =========================== QR Scanner ======================================
    if(user_role_select != 'customer'){
        $("#qr_scanner").removeClass("hide-important");
        let selectedDeviceId = null;
        let isScanning = false;
        const codeReader = new ZXing.BrowserMultiFormatReader();
        const $sourceSelect = $("#pilihKamera");
        const $preview = $("#previewKamera");
        const $hasilscan = $("#hasilscan");
        const $startBtn = $("#startBtn");
        const $stopBtn = $("#stopBtn");

        // Fungsi untuk memulai scanner
        function startScanner() {
            if (isScanning) return;

            codeReader.decodeFromVideoDevice(selectedDeviceId, 'previewKamera', (result, err) => {
                if (result) {
                    const plainText = result.text;
                    const encodedBase64 = btoa(plainText); // Encode ke Base64

                    console.log("Base64:", encodedBase64);
                    $hasilscan.val(encodedBase64);
                    // HasilScan Biasa
                    // console.log(result.text);
                    // $hasilscan.val(result.text);

                    // Untuk scan berulang, tidak perlu stop scanner
                    // Hanya contoh: kosongkan hasil setelah 2 detik
                    // setTimeout(() => {
                    //     $hasilscan.val('');
                    // }, 2000);
                }
                if (err && !(err instanceof ZXing.NotFoundException)) {
                    console.error(err);
                }
            }).then(() => {
                isScanning = true;
                $preview.show();
                $startBtn.prop('disabled', true);
                $stopBtn.prop('disabled', false);
                console.log("Scanner started");
            }).catch(err => {
                console.error("Failed to start scanner:", err);
                alert("Gagal memulai scanner: " + err);
            });
        }

        // Fungsi untuk menghentikan scanner
        function stopScanner() {
            if (!isScanning) return;

            codeReader.reset();
            isScanning = false;
            $preview.hide();
            $startBtn.prop('disabled', false);
            $stopBtn.prop('disabled', true);
            console.log("Scanner stopped");
        }

        // Inisialisasi kamera
        function initCameras() {
            codeReader.listVideoInputDevices()
                .then(videoInputDevices => {
                    if (videoInputDevices.length > 0) {
                        $sourceSelect.empty();

                        videoInputDevices.forEach((device, index) => {
                            $sourceSelect.append(
                                $('<option></option>')
                                    .val(device.deviceId)
                                    .text(device.label || `Kamera ${index + 1}`)
                            );
                        });

                        // Default pilih kamera belakang jika ada
                        const backCamera = videoInputDevices.find(device =>
                            device.label && device.label.toLowerCase().includes('back')
                        );

                        selectedDeviceId = backCamera ? backCamera.deviceId : videoInputDevices[0].deviceId;
                        $sourceSelect.val(selectedDeviceId);

                        // Aktifkan tombol start
                        $startBtn.prop('disabled', false);
                    } else {
                        alert("Tidak ada kamera yang ditemukan!");
                    }
                })
                .catch(err => {
                    console.error("Error accessing cameras:", err);
                    alert("Tidak bisa mengakses kamera: " + err);
                });
        }

        // Event listener untuk tombol
        $startBtn.on('click', startScanner);
        $stopBtn.on('click', stopScanner);

        // Event listener untuk ganti kamera
        $sourceSelect.on('change', function() {
            selectedDeviceId = $(this).val();
            if (isScanning) {
                stopScanner();
                startScanner();
            }
        });

        // Inisialisasi saat halaman dimuat
        $(document).ready(function() {
            if (navigator.mediaDevices) {
                initCameras();
            } else {
                alert('Browser tidak mendukung akses kamera');
            }
        });

        // Membersihkan scanner saat halaman ditutup
        $(window).on('beforeunload', function() {
            if (isScanning) {
                stopScanner();
            }
        });
    }
    // ============================== End of QR Scanner ============================
    // ============================== Submit QR Code ============================
    $('#generateBtn').on('click', function () {
        const base64Scan = $('#hasilscan').val();

        if (!base64Scan.trim()) {
            alert("Silakan scan QR code terlebih dahulu.");
            return;
        }

        let decoded;
        try {
            decoded = atob(base64Scan); // Decode Base64
        } catch (e) {
            alert("QR code tidak valid (bukan Base64).");
            return;
        }

        const parts = decoded.split('|');

        if (parts.length !== 2) {
            alert("Format QR code tidak dikenali.");
            return;
        }

        const kodeUser = parts[0];       // contoh: AD0001
        const tanggalQR = parts[1];      // contoh: 2025-05-17
        const today = new Date().toISOString().split('T')[0];
        console.log('kode User:' + kodeUser);
        if (tanggalQR !== today) {
            alert("QR code sudah kedaluwarsa.");
            return;
        }

        // AJAX ke server
        $.ajax({
            url: '{{ route('cek_user_qr') }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                kode: kodeUser
            },
            success: function (res) {
                if (res.status === 'ok') {
                    $('#hasilscan').val("");
                    stopScanner();
                    alert("✅ User valid: " + res.user_id);
                    // lanjutkan proses jika perlu...
                } else {
                    alert("❌ User tidak ditemukan.");
                }
            },
            error: function () {
                alert("Terjadi kesalahan saat menghubungi server.");
            }
        });
    });
    // ========================= End of Submit QR Code =====================================
});
</script>
