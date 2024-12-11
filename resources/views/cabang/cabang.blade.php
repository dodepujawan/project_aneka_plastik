
<div class="container master-cabang"><br>
    <div class="col-md-6 col-md-offset-3">
        <h2 class="text-center">Form Create Cabang</h3>
        <hr>
        <h3 id="message_cabang"></h3>
        <form action="#" id="cabang_form" method="post">
        @csrf
            <div class="form-group">
                <label><i class="fa fa-user"></i> ID Cabang</label>
                <input type="text" name="id_cabang" id="id_cabang" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label><i class="fa fa-user"></i> Nama Cabang</label>
                <input type="text" name="nama_cabang"  id="nama_cabang" class="form-control" placeholder="Nama Cabang" required="">
            </div>
            <div class="form-group">
                <label><i class="fa fa-key"></i> Alamat</label>
                <input type="text" name="alamat_cabang" id="alamat_cabang" class="form-control" placeholder="Alamat" required="">
            </div>
            <div class="form-group">
                <label><i class="fa fa-key"></i> Nomor Telepon</label>
                <input type="text" name="telp_cabang" id="telp_cabang" class="form-control" placeholder="Telepon Number" required="">
            </div>
            <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-user" id="submit_cabang"></i> Register</button>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // ### Panggil  Cabang Id
    generate_cabang_id();
    function generate_cabang_id(){
        $.ajax({
                url: '{{ route("generate_cabang_id") }}',
                type: 'GET',
                success: function(response) {
                    console.log('test:' + response)
                    // Tampilkan user_id di input
                    $('#id_cabang').val(response.cabang_id);
                },
                error: function(xhr, status, error) {
                    console.error('Error: ' + error);
                }
        });
    }

    // ### Submit Cabang
    $('#cabang_form').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: '{{ route("store_cabang") }}',
            type: 'POST',
            data: {
                nama_cabang: $('#nama_cabang').val(),
                alamat_cabang: $('#alamat_cabang').val(),
                telp_cabang: $('#telp_cabang').val(),
                cabang_id: $('#id_cabang').val() // Pastikan untuk mengirim cabang_id di sini
            },
            success: function(response) {
                $('#message_cabang').html('<p>' + response.pesan + '</p>');
                if (response.pesan === 'Register Berhasil. Cabang Baru sudah Aktif.') {
                    $('#cabang_form')[0].reset();
                    generate_cabang_id();
                }
            },
            error: function(response) {
                console.error(response.responseJSON.pesan);
                $('#message_cabang').html('<p>' + response.responseJSON.pesan + '</p>');
            }
        });
    });

});
</script>

