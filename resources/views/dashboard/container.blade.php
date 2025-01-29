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
// ========================= Transaksi Sukses ======================================
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
// ========================= End Of Transaksi Sukses ======================================
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
@endsection
