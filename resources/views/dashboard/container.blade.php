@extends('dashboard.index')
@section('content')
<div class="main-master">
    <div class="master-page">
        <h1>Main Page</h1>
        <h2>Aneka Plastik</h2>
        <div>Toko Online Aneka Plastik</div>
    </div>
</div>
@endsection
@section('footer')
<script>
$(document).ready(function() {
// ####################### NAVBAR ROOM ###########################################
// ========================= Edit Register ======================================
$(document).on('click', '.dropdown-item.edit-register', function(e) {
        e.preventDefault();
        loadEditRegisterForm();
    });

    function loadEditRegisterForm() {
        $.ajax({
            url: '{{ route('editregister') }}', // Route to load the form
            type: 'GET',
            success: function(response) {
                $('.master-page').html(response);
            },
            error: function() {
                $('.master-page').html('<p>Error loading form.</p>');
            }
        });
    }
// ========================= End Of Edit Register ======================================

// ======================== List Register ============================================
    $(document).on('click', '.dropdown-item.list-register', function(e) {
        e.preventDefault();
        loadListRegisterForm();
    });

    function loadListRegisterForm(){
        $.ajax({
            url: '{{ route('listregister') }}',
            success: function(response) {
                $('.master-page').html(response);
            },
            error: function() {
                $('.master-page').html('<p>Error loading form.</p>');
            }
        });
    };
// ========================= End Of List Register ======================================
// ========================= Create Cabang ======================================
$(document).on('click', '.dropdown-item.cabang-create', function(e) {
        e.preventDefault();
        load_create_cabang_form();
    });

    function load_create_cabang_form() {
        $.ajax({
            url: '{{ route('index_cabang') }}', // Route to load the form
            type: 'GET',
            success: function(response) {
                $('.master-page').html(response);
            },
            error: function() {
                $('.master-page').html('<p>Error loading form.</p>');
            }
        });
    }
// ========================= End Of Create Cabang ======================================
// ========================= List Cabang ======================================
$(document).on('click', '.dropdown-item.cabang-list', function(e) {
        e.preventDefault();
        load_list_cabang_form();
    });

    function load_list_cabang_form() {
        $.ajax({
            url: '{{ route('index_list_cabang') }}',
            type: 'GET',
            success: function(response) {
                $('.master-page').html(response);
            },
            error: function() {
                $('.master-page').html('<p>Error loading form.</p>');
            }
        });
    }
// ========================= End Of List Cabang ======================================
// ========================= Update Pajak ======================================
    $(document).on('click', '.dropdown-item.edit-pajak', function(e) {
        e.preventDefault();
        loadDataPajak();
        function loadDataPajak(){
            $.ajax({
                url: '{{ route('get_pajak') }}',
                type: 'GET',
                success: function(response) {
                    let nilai_ppn = response.data.ppn;
                    $('#modal-ppn').val(nilai_ppn);
                },
                error: function() {
                    $('#modal-ppn').val('Error Loading');
                }
            });
        }
        $('#pajakModal').modal('show');
    });
    // ### Submit Pajak ###
    $('#submit_pajak').on('click', function (e) {
    e.preventDefault();
    let ppn_pajak = $('#modal-ppn').val();
        $.ajax({
            url: '{{ route('update_pajak') }}', // Ganti sesuai route di Laravel kamu
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'), // CSRF token
                ppn: ppn_pajak
            },
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses!',
                    text: response.message || 'PPN berhasil disimpan!',
                    timer: 2000,
                    showConfirmButton: false
                });
                $('#pajakModal').modal('hide');
            },
            error: function (xhr) {
                console.error('Gagal:', xhr.responseText);
                alert('Gagal menyimpan PPN');
            }
        });
    });
// ========================= End Of Update Pajak ======================================
// ########################### End Of NAVBAR ROOM #####################################
// ########################### SIDEBAR ROOM #####################################
// ========================= Main Transaksi ======================================
    $(document).on('click', '.main-sidebar #main_transaksi_link', function(e) {
            e.preventDefault();
            loadMainTransaksilink();
        });

    function loadMainTransaksilink() {
        $.ajax({
            url: '{{ route('index_transaksi') }}',
            type: 'GET',
            success: function(response) {
                $('.master-page').html(response);
            },
            error: function() {
                $('.master-page').html('<p>Error loading form.</p>');
            }
        });
    }
// ========================= End Of Main Transaksi ======================================
// ========================= Edit Transaksi ======================================
$(document).on('click', '.main-sidebar #edit_transaksi_link', function(e) {
            e.preventDefault();
            load_edit_transaksilink();
        });

    function load_edit_transaksilink() {
        $.ajax({
            url: '{{ route('index_edit_transaksi') }}',
            type: 'GET',
            success: function(response) {
                $('.master-page').html(response);
            },
            error: function() {
                $('.master-page').html('<p>Error loading form.</p>');
            }
        });
    }
// ========================= End Of Edit Transaksi ======================================
// ========================= Transaksi Approved ======================================
$(document).on('click', '.main-sidebar #approved_transaksi_link', function(e) {
            e.preventDefault();
            load_approved_transaksilink();
        });

    function load_approved_transaksilink() {
        $.ajax({
            url: '{{ route('approved_transaksi') }}',
            type: 'GET',
            success: function(response) {
                $('.master-page').html(response);
            },
            error: function() {
                $('.master-page').html('<p>Error loading form.</p>');
            }
        });
    }
// ========================= End Of Transaksi Approved ======================================
// ========================= Transaksi Sukses ======================================
$(document).on('click', '.main-sidebar #success_transaksi_link', function(e) {
            e.preventDefault();
            load_approved_transaksilink();
        });

    function load_approved_transaksilink() {
        $.ajax({
            url: '{{ route('success_transaksi') }}',
            type: 'GET',
            success: function(response) {
                $('.master-page').html(response);
            },
            error: function() {
                $('.master-page').html('<p>Error loading form.</p>');
            }
        });
    }
// ========================= End Of Transaksi Sukses ======================================
// ========================= Main Transaksi Mobile ======================================
    $(document).on('click', '.main-sidebar #main_transaksi_link_mobile', function(e) {
        e.preventDefault();
        loadMainTransaksilinkMobile();
    });

    function loadMainTransaksilinkMobile() {
        $.ajax({
            url: '{{ route('index_mobile') }}',
            type: 'GET',
            success: function(response) {
                $('.master-page').html(response);
            },
            error: function() {
                $('.master-page').html('<p>Error loading form.</p>');
            }
        });
    }
// ========================= End Of Main Transaksi Mobile ======================================
// ========================= Edit Transaksi Mobile ======================================
    $(document).on('click', '.main-sidebar #edit_transaksi_link_mobile', function(e) {
        e.preventDefault();
        loadEditTransaksilinkMobile();
    });

    function loadEditTransaksilinkMobile() {
        $.ajax({
            url: '{{ route('index_edit_transaksi_mobile') }}',
            type: 'GET',
            success: function(response) {
                $('.master-page').html(response);
            },
            error: function() {
                $('.master-page').html('<p>Error loading form.</p>');
            }
        });
    }
// ========================= End Of Edit Transaksi Mobile ======================================
// ========================= Transaksi Approved Mobile ======================================
$(document).on('click', '.main-sidebar #approved_transaksi_link_mobile', function(e) {
            e.preventDefault();
            load_approved_transaksilink();
        });

    function load_approved_transaksilink() {
        $.ajax({
            url: '{{ route('approved_transaksi') }}',
            type: 'GET',
            success: function(response) {
                $('.master-page').html(response);
            },
            error: function() {
                $('.master-page').html('<p>Error loading form.</p>');
            }
        });
    }
// ========================= End Of Transaksi Approved Mobile ======================================
// ========================= Transaksi Sukses Mobile ======================================
$(document).on('click', '.main-sidebar #success_transaksi_link_mobile', function(e) {
            e.preventDefault();
            load_approved_transaksilink();
        });

    function load_approved_transaksilink() {
        $.ajax({
            url: '{{ route('success_transaksi') }}',
            type: 'GET',
            success: function(response) {
                $('.master-page').html(response);
            },
            error: function() {
                $('.master-page').html('<p>Error loading form.</p>');
            }
        });
    }
// ========================= End Of Transaksi Sukses Mobile ======================================
// ========================= List Harga ======================================
$(document).on('click', '.main-sidebar #list_harga_link', function(e) {
            e.preventDefault();
            loadListHargalink();
        });

    function loadListHargalink() {
        $.ajax({
            url: '{{ route('list_harga') }}',
            type: 'GET',
            success: function(response) {
                $('.master-page').html(response);
            },
            error: function() {
                $('.master-page').html('<p>Error loading form.</p>');
            }
        });
    }
// ========================= End Of List Harga ======================================
// ########################### End Of SIDEBAR ROOM #####################################

});
</script>

<script type="module">
    window.Laravel = {!! json_encode(['userId' => auth()->user()->id]) !!};

if (window.Echo) {

    // Pastikan nama channel sama persis dengan yang ada di server
    let channelName = `private-user.${window.Laravel.userId}`;

    console.log(channelName);
    window.Echo.private(channelName)
        .listen('.pdf.done', (e) => {  // Perhatikan titik di depan nama event
            console.log("Event diterima:", e);
            alert(e.message || 'PDF Selesai');
        });

    // window.Echo.channel('public-pdf-notifications')
    // .listen('.pdf.done', (e) => {
    //     console.log("Event diterima:", e);
    //     alert(e.message || 'PDF Selesai');
    // });
}
</script>

{{-- <script>
    window.Laravel = {!! json_encode(['userId' => auth()->id()]) !!};
</script> --}}


@endsection
