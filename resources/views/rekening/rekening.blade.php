<div class="container master-register">
    <div class="col-md-6 col-md-offset-3">
        <button class="btn btn-primary mb-3" id="listRekening">List Rekening</button>
        <h2 class="text-center">FORM REGISTER REKENING</h3>
        <hr>
        @if(session('message'))
        <div class="alert alert-success">
            {{session('message')}}
        </div>
        @endif
        <h3 id="message_rekening"></h3>
        <form action="#" id="RekeningRegisterForm" method="post">
        @csrf
            <div class="form-group">
                <label><i class="fa fa-calculator"></i> ID Register</label>
                <input type="text" name="id_rekening" id="id_rekening" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label><i class="fa fa-user"></i> Nama Bank</label>
                <input type="text" name="bank_name"  id="bank_name" class="form-control" placeholder="Nama Bank" required="">
            </div>
            <div class="form-group">
                <label><i class="fa fa-hashtag"></i> Nomor Rekening</label>
                <input type="text" name="kode_rekening" id="kode_rekening" class="form-control" placeholder="Kode Rekening">
            </div>
            <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-user"></i> Register</button>
            <hr>
        </form>
    </div>
</div>
<script>
$(document).ready(function() {
    // Set CSRF token in AJAX setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
            url: '{{ route("generate_rekening_id") }}',
            type: 'GET',
            success: function(response) {
                // Tampilkan user_id di input
                $('#id_rekening').val(response.kode_bank);
            },
            error: function(xhr, status, error) {
                console.error('Error: ' + error);
            }
    });

    // ###Submit Form
    $('#RekeningRegisterForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: '{{ route('rekening_register') }}', // Update with the actual route name
            type: 'POST',
            data: {
                name: $('#bank_name').val(),
                rekening_number: $('#kode_rekening').val(),
            },
            success: function(response) {
                $('#message_rekening').html('<p>' + response.pesan + '</p>');
                if (response.pesan === 'Register Berhasil. Rekening Anda sudah Aktif.') {
                    $('#RekeningRegisterForm')[0].reset();
                    var role = "AD";
                    $.ajax({
                            url: '{{ route("generate_rekening_id") }}',
                            type: 'GET',
                            data: { role: role },
                            success: function(response) {
                                // Tampilkan user_id di input
                                $('#id_rekening').val(response.kode_bank);
                            },
                            error: function(xhr, status, error) {
                                console.error('Error: ' + error);
                            }
                    });
                }
            },
            error: function(response) {
                 // Perbaiki error handling
                let errorMessage = 'Terjadi kesalahan';
                if (xhr.responseJSON && xhr.responseJSON.pesan) {
                    errorMessage = xhr.responseJSON.pesan;
                } else if (xhr.status === 405) {
                    errorMessage = 'Method not allowed. Pastikan form dikirim dengan method POST.';
                }
                $('#message_rekening').html('<p class="text-danger">' + errorMessage + '</p>');
            }
        });
    });
    // ========================= List Rekening ======================================
    $(document).on('click', '#listRekening', function(e) {
        e.preventDefault();
        load_add_rekening_form();
    });

    function load_add_rekening_form() {
        $.ajax({
            url: '{{ route('rekening') }}',
            type: 'GET',
            success: function(response) {
                $('.master-page').html(response);
            },
            error: function() {
                $('.master-page').html('<p>Error loading form.</p>');
            }
        });
    }
// ========================= End Of List Rekening ======================================
});
</script>
