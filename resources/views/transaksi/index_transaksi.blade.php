{{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" /> --}}
<style>
/* .product-info {
  display: flex;
  gap: 20px; / Adds space between the items /
}
.info-item {
  flex: 1; / Makes each item take up equal space /
}
h5 {
  margin: 0; / Removes default margin from h5 /
} */

/* table scroll */
.table-container {
    max-height: 300px; /* Sesuaikan tinggi maksimum sesuai kebutuhan */
    overflow-y: auto;  /* Tambahkan scroll vertikal jika konten melebihi tinggi maksimum */
    width: 100%;       /* Pastikan lebar kontainer sesuai dengan tabel */
    border: 1px solid #ddd; /* Opsional: tambahkan border untuk kontainer tabel */
}

.table-container table {
    width: 100%; /* Pastikan tabel mengambil lebar penuh dari kontainer */
    border-collapse: collapse; /* Menghindari jarak antara border sel */
}

.table-container th, .table-container td {
    padding: 8px; /* Opsional: tambahkan padding untuk sel tabel */
    text-align: left; /* Opsional: sesuaikan perataan teks */
}
/* end of table scroll */
/* Styling Select2 */
/* .select2-result-barang {
    padding: 4px;
}
.select2-result-barang__kode {
    font-size: 16px;
    color: #333;
}
.select2-result-barang__info {
    font-size: 14px;
    color: #666;
} */
/* End Of Styling Select2 */
/* Product info */
.product-info {
    background-color: white;
    border-radius: 8px; /* Ukuran lebih kecil */
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Lebih ringan */
    padding: 15px; /* Padding lebih kecil */
    margin-top: 15px; /* Margin atas lebih kecil */
}

.info-item {
    text-align: center;
    margin-bottom: 10px; /* Jarak antar item lebih kecil */
}

.info-item h5 {
    color: #6c757d;
    font-size: 0.9rem; /* Ukuran font lebih kecil */
    margin-bottom: 0;
    display: inline; /* Ubah ke inline untuk sejajar */
    padding-bottom: 2px;
    border-bottom: 2px solid transparent;
    transition: border-bottom-color 0.3s ease;
}

.info-item:hover h5 {
    border-bottom-color: #6c757d;
}

.info-item span {
    color: black;
    font-weight: bold;
    display: inline; /* Pastikan sejajar dengan h5 */
    margin-left: 5px; /* Jarak kecil antara teks dan span */
}

.underline {
    width: 100%;
    border-bottom: 1px solid #dee2e6;
    margin: 10px 0; /* Margin lebih kecil */
}

@media (max-width: 768px) {
    .product-info {
        padding: 10px; /* Padding lebih kecil untuk layar kecil */
    }
    .info-item {
        margin-bottom: 8px; /* Jarak antar item lebih kecil */
    }
    .info-item h5 {
        font-size: 0.85rem; /* Font lebih kecil untuk layar kecil */
    }
}
/* End of Product Info */

.title_page {
    color: #1976d2; /* biru SBAdmin */
    font-weight: 600;
    border-bottom: 3px solid #90caf9;
    padding-bottom: 8px;
    margin-bottom: 20px;
}
</style>
<h3 class="title_page">Halaman Input PO</h3>
<div class="mt-3 mb-3">
    <button type="button" class="btn btn-success" id="generate_new_po">+ New PO</button>
</div>
<div class="container master_customer_select">
    <div class="row">
        <div class="form-group col-lg-4 col-md-12 col-sm-12 mb-3">
            <input type="text" name="kode_user_trans" id="kode_user_trans" class="form-control" placeholder="Kode Customer" required="" readonly>
        </div>
        <div class="form-group col-lg-4 col-md-12 col-sm-12 mb-3">
            <input type="text" name="nama_user_trans" id="nama_user_trans" class="form-control" placeholder="Nama Customer" required="" readonly>
        </div>
        <div class="form-group col-lg-4 col-md-12 col-sm-12 mb-3">
            <select name="select_user_trans" id="select_user_trans" class="form-control">
                <option value="">Pilih Customer</option>
            </select>
        </div>
    </div>
</div>

<div class="container master_transaksi">
    <div class="product-info">
        <input type="hidden" value="" id="kd_barang" readonly>
        <!-- Informasi Barang -->
        <div class="row">
            <div class="col-md-3 info-item">
                <h5>Nama: <span id="nama_barang">-</span></h5>
            </div>
            <div class="col-md-3 info-item">
                <h5>Harga: <span id="harga_barang">-</span></h5>
            </div>
            <div class="col-md-3 info-item">
                <h5>Isi: <span id="unit_barang">-</span></h5>
            </div>
            <div class="col-md-3 info-item">
                <h5>Stok: <span id="stok_barang">-</span></h5>
            </div>
            {{-- <div class="col-md-3 info-item">
                <h5>Satuan: <span id="satuan_barang">-</span></h5>
            </div> --}}
        </div>

        {{-- <!-- Garis Pembatas -->
        <div class="underline"></div>

        <!-- Informasi Harga dan Stok -->
        <div class="row mt-3">
            <div class="col-md-6 info-item">
                <h5>Harga Barang: <span id="harga_barang">-</span></h5>
            </div>
            <div class="col-md-6 info-item">
                <h5>Stok Barang: <span id="stok_barang">-</span></h5>
            </div>
        </div> --}}
    </div>
</div>
{{-- ### Form Inputan ### --}}
<form action="" id="form_transaksi" class="mt-3">
    <div class="row">
        <div class="col-lg-4 col-md-12 col-sm-12 mb-3">
            <div class="d-flex align-items-center">
                <input type="text" id="nomor_po" class="form-control" readonly>
            </div>
        </div>
        <div class="col-lg-4 col-md-12 col-sm-12 mb-3">
            <div class="d-flex align-items-center">
                <select name="select_gudang" id="select_gudang" class="form-control">
                    <option></option>
                    <!-- Options untuk select dropdown -->
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 col-md-12 col-sm-12 mb-3">
            <div class="d-flex align-items-center">
            <button type="button" id="clear_select" class="btn btn-secondary btn-sm me-2 mr-2">
                <i class="fa fa-eraser" aria-hidden="true"></i>
            </button>
            <select name="select_barang" id="select_barang" class="form-control">
                <option></option>
                <!-- Options untuk select dropdown -->
            </select>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
            <select name="select_barang_satuan" id="select_barang_satuan" class="form-control">
                <option value="">Pilih Satuan</option>
            </select>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
            <input type="number" id="jumlah_trans" class="form-control" placeholder="Jumlah barang">
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
            <div class="d-flex align-items-center">
                @php
                    $user = Auth::user();
                    $allowed_roles = ['customer'];
                    $is_customer = in_array($user->roles, $allowed_roles);
                @endphp
            <input type="number" id="diskon_barang" class="form-control" placeholder="Diskon %" {{ $is_customer ? 'readonly' : '' }}>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
            <div class="d-flex align-items-center">
                @php
                    $user = Auth::user();
                    $allowed_roles = ['customer'];
                    $is_customer = in_array($user->roles, $allowed_roles);
                @endphp
            <input type="number" id="diskon_barang_rp" class="form-control" placeholder="Diskon RP" {{ $is_customer ? 'readonly' : '' }}>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
            <div class="d-flex align-items-center" style="gap: 10px;">
                <label for="ppn_trans" class="mb-0">PPN</label>
                <input type="number" id="ppn_trans" class="form-control form-control-md" style="width: 150px;" placeholder="0" disabled>
                <button type="submit" class="btn btn-success btn-md">Simpan</button>
            </div>
        </div>
    </div>
</form>
{{-- ### Table ###  --}}
<div class="mt-3 table-container table-responsive">
    <table id="transaksi_table" class="display table table-bordered mb-2">
        <thead>
            <tr>
                <th>No</th>
                <th>KD Barang</th>
                <th>Nama</th>
                <th>Harga</th>
                <th>Isi</th>
                <th>Satuan</th>
                <th>Jumlah</th>
                <th>Diskon %</th>
                <th>Diskon Rp</th>
                <th>PPN</th>
                <th>Total</th>
                <th>del</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="10" class="text-right"><strong>Grand Total:</strong></td>
                <td id="grand_total">0</td>
            </tr>
            <tr>
                <td colspan="10" class="text-right"><strong>DPP :</strong></td>
                <td id="grand_total_dpp">0</td>
            </tr>
            <tr>
                <td colspan="10" class="text-right"><strong>PPN :</strong></td>
                <td id="grand_total_ppn">0</td>
            </tr>
        </tfoot>
        <tbody>
            <!-- Data akan diisi oleh DataTables -->
        </tbody>
    </table>
</div>
{{-- <button type="submit" class="btn btn-primary mt-2" id="save_table_transaksi" style="float: right;"><i class="fas fa-save"> Proses</i></button> --}}
{{-- modal cetak transaksi --}}
{{-- <!-- Tombol untuk buka modal -->
<button class="btn btn-primary" data-toggle="modal" data-target="#payment_modal">
  Bayar
</button> --}}

<!-- Modal Pembayaran Struk-->
<div class="modal fade" id="payment_modal" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Pembayaran</h5>
      </div>
      <div class="modal-body">

        <!-- Total Harga -->
        <div class="form-group">
          <label>Total Harga</label>
          <input type="text" id="total_harga_modal" class="form-control" readonly>
        </div>

        <!-- Metode Pembayaran -->
        <div class="form-group">
          <label>Metode Pembayaran</label>
          <select id="paymentMethod" class="form-control">
            <option value="">-- Pilih --</option>
            <option value="cash">Cash</option>
            <option value="transfer">Transfer</option>
            <option value="bon">Bon</option>
          </select>
        </div>

        <!-- Input jika Cash -->
        <div id="cashSection" class="d-none">
          <div class="form-group">
            <label>Nominal Uang</label>
            <input type="number" id="cashAmount" class="form-control">
          </div>
          <div class="form-group">
            <label>Kembalian</label>
            <input type="text" id="changeAmount" class="form-control" readonly>
          </div>
        </div>

        <!-- Input jika Transfer -->
        <div id="transferSection" class="d-none">
          <div class="form-group">
            <label>Nama Bank</label>
            <input type="text" id="bankName" class="form-control">
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" id="cetak_faktur" class="btn btn-success">Cetak</button>
        <button type="button" class="btn btn-secondary" id="cancel_btn_cetak" data-dismiss="modal">Batal</button>
      </div>
    </div>
  </div>
</div>
{{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script> --}}

<script>
$(document).ready(function(){
// ========================= New Transaksi ======================================
    $('#generate_new_po').on('click', function(e) {
            e.preventDefault();
            loadMainTransaksilink1();
        });

    function loadMainTransaksilink1() {
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
// ========================= End Of New Transaksi ======================================
// ================================= Select User ===========================================
    let user_role_select = @json(Auth::user()->roles);
    let user_kode_select = @json(Auth::user()->user_kode);
    let user_nama_select = @json(Auth::user()->name);
    // console.log('kode:' + user_role_select);
    // console.log('nama:' + user_nama_select);
    if(user_role_select == 'customer'){
        $("#kode_user_trans").val(user_kode_select);
        $("#nama_user_trans").val(user_nama_select);
        $(".master_customer_select").hide();
    }else{
        select_user_list();
    }
    // select_user_list();
    function select_user_list(){
        $('#select_user_trans').select2({
            // tags: true,
            theme: 'bootstrap4',
            width: '100%',
            ajax: {
                url: '{{ route('get_users') }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term // Kirim parameter pencarian ke server
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.map(function(user) {
                            // Potong nama barang jika terlalu panjang
                            let nama_user_cut = user.kd_customer + ' / ' + user.nama_cust || '';
                            if (nama_user_cut.length > 15) {
                                nama_user_cut = nama_user_cut.substring(0, 20) + '...'; // Potong teks
                            }
                            return {
                                id: user.id,
                                text: nama_user_cut,
                                kd_customer: user.kd_customer,
                                nama: user.nama_cust,
                            };
                        })
                    };
                },
                cache: true
            },
            placeholder: 'Pilih Customer',
            minimumInputLength: 1,
            templateResult: formatUser
        });
        // ### fungsi untuk format text select2
        function formatUser(user) {
            if (!user.id) {
                return user.text;
            }
            var $user = $(
                '<div class="select2-result-user">' +
                    '<div class="select2-result-user__kode"><strong>' + user.kd_customer + '</strong></div>' +
                    '<div class="select2-result-user__info">' +
                        (user.nama ? user.nama : '') +
                        // (barang.kemasan ? ' / ' + barang.kemasan : '') +
                    '</div>' +
                '</div>'
            );
            return $user;
        }

        // ### Event listener saat item dipilih
        $('#select_user_trans').on('select2:select', function(e) {
            var data = e.params.data; // Data yang dipilih
            $('#kode_user_trans').val(data.kd_customer);
            $('#nama_user_trans').val(data.nama);
        });
    };
// ================================= End Of Select User ===========================================
// ================================= Tampil No PO ===========================================
    // panggil generate kode PO otomatis
    $.ajax({
        url: "{{ route('generate_kode_po') }}",
        type: "GET",
        success: function(response) {
            $('#nomor_po').val(response.kode_po); // tampilkan ke input
        },
        error: function(xhr) {
            console.error(xhr.responseText);
            alert('Gagal generate kode PO');
        }
    });
// ================================= End Of Tampil No PO ===========================================
// ===================================== Select Gudang =============================================
    $.ajax({
        url: "{{ route('get_kode_gudang') }}",
        type: "GET",
        success: function(data) {
            let select = $('#select_gudang');
            select.empty();
            // select.append('<option value="">-- Pilih Gudang --</option>');
            $.each(data, function(index, value) {
                select.append('<option value="' + value.cabang_id + '">' + value.nama + '</option>');
            });
        }
    });
// ================================= End of Select Gudang ===========================================
// ================================= Trigger Select Gudang ===========================================
$('#select_gudang').on('change', function() {
    let kode_gudang = $(this).val(); // ambil gudang aktif
    let kd_barang = $('#kd_barang').val(); // ambil barang aktif dari Select2

    if (kd_barang) {
        console.log('Gudang berubah, refresh data barang:', kd_barang, 'Gudang:', kode_gudang);
        get_barang_satuan(kd_barang);
    }
});
// ================================= End of Triger Select Gudang ===========================================
// ================================= Select Barang ===========================================
    $('#select_barang').select2({
        // tags: true,
        theme: 'bootstrap4',
        width: '100%',
        ajax: {
            url: '{{ route('get_barangs') }}',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    q: params.term // Kirim parameter pencarian ke server
                };
            },
            processResults: function(data) {
                return {
                    results: data.map(function(barang) {
                        // Potong nama barang jika terlalu panjang
                        let nama_barang_cut = barang.kd_barang + ' / ' + barang.nama_barang || '';
                        if (nama_barang_cut.length > 15) {
                            nama_barang_cut = nama_barang_cut.substring(0, 20) + '...'; // Potong teks
                        }
                        return {
                            id: barang.id,
                            text: nama_barang_cut,
                            kd_barang: barang.kd_barang,
                            nama: barang.nama_barang,
                        };
                    })
                };
            },
            cache: true
        },
        placeholder: 'Pilih Barang',
        minimumInputLength: 1,
        templateResult: formatBarang
    });
    // ### fungsi untuk format text select2
    function formatBarang(barang) {
        if (!barang.id) {
            return barang.text;
        }
        var $barang = $(
            '<div class="select2-result-barang">' +
                '<div class="select2-result-barang__kode"><strong>' + barang.kd_barang + '</strong></div>' +
                '<div class="select2-result-barang__info">' +
                    (barang.nama ? barang.nama : '') +
                    // (barang.kemasan ? ' / ' + barang.kemasan : '') +
                '</div>' +
            '</div>'
        );
        return $barang;
    }

    // ### Event listener saat item dipilih
    $('#select_barang').on('select2:select', function(e) {
        var data = e.params.data; // Data yang dipilih
        $('#kd_barang').val(data.kd_barang);
        get_barang_satuan(data.kd_barang);
    });

    // ### Clear Selected Barangs
    $('#clear_select').on('click', function() {
        $('#select_barang').val(null).trigger('change'); // Kosongkan pilihan
        $('#kd_barang').val("").trigger('change');
        $('#nama_barang').text('-').trigger('change');
        $('#unit_barang').text('-').trigger('change');
        $('#stok_barang').text('-').trigger('change');
        $('#select_barang_satuan').empty().trigger('change');
        $('#select_barang_satuan').append('<option value="">Pilih Satuan</option>').trigger('change');
        $('#harga_barang').text('-').trigger('change');
    });

    $(document).on('select2:open', () => {
        document.querySelector('.select2-search__field').focus();
    });

    // === fungsi enter next di form ===
    // ### Mencegah submit ketika Enter kecuali pada tombol submit (fungsi enter jadi next)
    $('form').on('keydown', 'input, select', function(e) {
        if (e.keyCode === 13) {
            e.preventDefault(); // Mencegah form langsung submit
            // Dapatkan semua elemen fokusable (yang terlihat, tidak disabled, tidak readonly)
            var focusable = $('form').find('input, select, button')
                .filter(':visible:not([disabled]):not([readonly])');

            var nextIndex = focusable.index(this) + 1;

            if (nextIndex < focusable.length) {
                focusable.eq(nextIndex).focus();
            } else {
                // Submit form kalau sudah sampai elemen terakhir
                $('form').submit();
            }
        }
    });

    // ### Tangani default enter di select2
    $('#select_barang').on('select2:select', function(e) {
        // Pindahkan fokus ke input selanjutnya ketika item dipilih
        var focusable = $('form').find('input, select, button').filter(':visible');
        var nextIndex = focusable.index(this) + 1;
        if (nextIndex < focusable.length) {
            focusable.eq(nextIndex).focus();
        }
    });
     // Mencegah Enter pada select2 agar tidak trigger submit
    $('#select_barang').on('keypress', function(e) {
        if (e.keyCode == 13) {
            e.preventDefault(); // Mencegah select2 dari trigger submit ketika tekan Enter
        }
    });
     // === end of fungsi enter next di form ===
// ================================= End Of Select Barang ===========================================
// ======================= Trigger Select Satuan Barang When kd_barang change =============================
    function get_barang_satuan(kd_barang){
        // console.log('test :' + kd_barang)
        let kode_gudang = $('#select_gudang').val();
        if (kd_barang) {
            $.ajax({
                url: '{{ route("get_barang_satuan") }}',
                type: 'GET',
                data: {
                    kd_barang: kd_barang,
                    kode_gudang: kode_gudang
                 },
                success: function (response) {
                    // alert(response[0].satuan);
                    $('#select_barang_satuan').empty();
                    response.forEach(function (item) {
                        console.log('Satuan:', item.satuan);
                        $('#select_barang_satuan').append(
                            `<option value="${item.satuan}">${item.satuan}</option>`
                        );
                    });
                    console.log('nemu' + response[0].NAMA_BRG);
                    $('#nama_barang').text(response[0].NAMA_BRG);
                    let data_harga = response[0].hj1;
                    $('#harga_barang').text(format_ribuan(data_harga));
                    // menghapus nilani decimal dari dbase
                    let isi = response[0].isi.replace(/,/g, '');
                    let formatted_value = isi.replace(/\./g, ''); // Menghapus titik
                    let final_value = parseInt(formatted_value / 1000);
                    $('#unit_barang').text(final_value);
                    // menghapus nilani decimal dari dbase
                    let isi_gudang = response[0].stok_gudang.replace(/,/g, '');
                    let formatted_value_gudang = isi_gudang.replace(/\./g, ''); // Menghapus titik
                    let final_value_gudang = parseInt(formatted_value_gudang / 100);
                    $('#stok_barang').text(final_value_gudang);
                },
                error: function (xhr) {
                    console.error('Error:', xhr.responseText);
                }
            });
        } else {
            // Kosongkan opsi jika input kosong
            $('#select_barang_satuan').empty();
            $('#select_barang_satuan').append('<option value="">Pilih Satuan</option>');
        }
    };
// =================== End of Trigger Select Satuan Barang When kd_barang change ==========================
// ==================== Trigger Select Satuan Barang When select_barang_satuan change ============================
    $('#select_barang_satuan').on('change', function() {
        const satuan_barang = $(this).val();
        const kd_barang = $('#kd_barang').val();
        const kode_gudang = $('#select_gudang').val();
        if (satuan_barang) {
            $.ajax({
                url: '{{ route("get_barang_selected") }}',
                type: 'GET',
                data: { satuan_barang: satuan_barang, kd_barang: kd_barang, kode_gudang: kode_gudang },
                success: function(response) {
                    let data_harga = response.hj1;
                    $('#harga_barang').text(format_ribuan(data_harga));
                    let isi = response.isi.replace(/,/g, '');
                    let formatted_value = isi.replace(/\./g, ''); // Menghapus titik
                    let final_value = parseInt(formatted_value / 1000);
                    $('#unit_barang').text(final_value);
                    // menghapus nilani decimal dari dbase
                    let isi_gudang = response.stok_gudang.replace(/,/g, '');
                    let formatted_value_gudang = isi_gudang.replace(/\./g, ''); // Menghapus titik
                    let final_value_gudang = parseInt(formatted_value_gudang / 100);
                    $('#stok_barang').text(final_value_gudang);
                },
                error: function(xhr) {
                    console.error('Error:', xhr.responseText);
                }
            });
        } else {
            console.log('Tidak ada pilihan yang dipilih.');
        }
    });

// =================== End of Trigger Select Satuan Barang When select_barang_satuan change ==========================
// =================== Pajak PPN ==========================
    loadInputPajak();
    function loadInputPajak(){
        $.ajax({
            url: '{{ route('get_pajak') }}',
            type: 'GET',
            success: function(response) {
                let nilai_ppn = response.data.ppn;
                $('#ppn_trans').val(nilai_ppn);
            },
            error: function() {
                $('#ppn_trans').val('Error Loading');
            }
        });
    }
// =================== End Of Pajak PPN ==========================
// ================================= Simpan Barang To DB ===========================================
    $('#form_transaksi').on('submit', function(e) {
        e.preventDefault();

        // fungsi hanya untuk cek nilai kosong
        let kode_userCek = $('#kode_user_trans').val();
        let kdBarangCek = $('#kd_barang').val();
        let namaBarangCek = $('#nama_barang').text();

        let hargaBarang = parseFloat(hapus_format($('#harga_barang').text())) || 0;
        let jumlahTrans = parseInt($('#jumlah_trans').val()) || 0;
        let diskonBarang = parseFloat($('#diskon_barang').val()) || 0; // persen
        let diskonBarangRp = parseFloat($('#diskon_barang_rp').val()) || 0; // nominal
        let ppnBarang = parseFloat($('#ppn_trans').val()) || 0;
        // Hitung diskon dalam uang
        let diskon_dalam_uang = (diskonBarang / 100) * hargaBarang;
        // Harga setelah diskon
        let harga_setelah_diskon = hargaBarang - diskon_dalam_uang - diskonBarangRp;
        // Total kotor (sudah termasuk PPN)
        let total = harga_setelah_diskon * jumlahTrans;
        // Hitung PPN dan DPP (kalau perlu tampilkan di tabel nanti)
        let dpp = Math.round(total / (1 + ppnBarang / 100));
        let total_ppn = total - dpp;
        // Grand total (karena total sudah termasuk PPN)
        let gtotal = total;

            // Pengecekan untuk nilai kosong
        if (!kdBarangCek || !namaBarangCek || !kode_userCek || hargaBarang === 0 || jumlahTrans === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Submit Failed',
                text: 'Mohon lengkapi semua data sebelum submit!',
                showConfirmButton: false,
                timer: 2000 // Durasi tampil dalam milidetik
            });
            // alert('Mohon lengkapi semua data sebelum submit!');
            return; // Hentikan proses jika salah satu input kosong
        }

        let data = {
            _token: '{{ csrf_token() }}',
            nomor_po: $('#nomor_po').val(),
            select_gudang: $('#select_gudang').val(),
            kode_user: $('#kode_user_trans').val(),
            product: {
                kd_barang: $('#kd_barang').val(),
                nama: $('#nama_barang').text(),
                harga: hargaBarang,
                jumlah: jumlahTrans,
                unit: parseFloat(hapus_format($('#unit_barang').text())) || 0,
                satuan: $('#select_barang_satuan option:selected').text(),
                diskon: diskonBarang,
                diskon_rp: diskonBarangRp,
                total: gtotal,
                ppn_trans: ppnBarang
            }
        };

        $.ajax({
            url: "{{ route('save_products') }}",
            type: "POST",
            data: data,
            success: function(res) {
                let id = res.id;
                console.log(res.message);
                submit_to_table(id);
            },
            error: function(err) {
                console.error(err.responseJSON.error);
            }
        });
    });
// ================================= End Of Simpan Barang To DB ===========================================
// ================================= Input Barang To Table ===========================================
    let grandTotal = 0;
    let grandTotalDpp = 0;
    let grandTotalPpn = 0;
    function submit_to_table(id){
        event.preventDefault();

        let kdBarang = $('#kd_barang').val();
        let namaBarang = $('#nama_barang').text();
        let hargaBarangText = $('#harga_barang').text();
        let hargaBarang = parseFloat(hapus_format(hargaBarangText)) || 0;
        let unitBarang = $('#unit_barang').text();
        let satuanBarang = $('#select_barang_satuan').val();
        let jumlahTrans = parseFloat($('#jumlah_trans').val()) || 0;
        let diskonBarang = parseFloat($('#diskon_barang').val()) || 0;
        let diskonBarangRp = parseFloat($('#diskon_barang_rp').val()) || 0;
        let ppnBarang = parseFloat($('#ppn_trans').val().replace(',', '.')) || 0;

        // rumus diskon
        let diskon_dalam_uang = (diskonBarang / 100) * hargaBarang;
        let harga_setelah_diskon = hargaBarang - diskon_dalam_uang - diskonBarangRp;
        let total = harga_setelah_diskon * jumlahTrans;

        // PPN dihitung mundur (seperti di Excel)
        let dpp = Math.round(total / (1 + ppnBarang / 100));
        let total_ppn = total - dpp;

        let gtotal = total; // karena total sudah termasuk PPN
        // let formatted_total = total.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });-> jika butuh pembulatan .00
        // let formatted_total = total;

        // Validasi user untuk menonaktifkan diskon edit
        let user_role_diskon = @json(Auth::user()->roles);
        let newRow = `
            <tr>
                <td class="row-number"></td>
                <td>${kdBarang}</td>
                <td>${namaBarang}</td>
                <td>${hargaBarang}</td>
                <td>${unitBarang}</td>
                <td>${satuanBarang}</td>
                <td class="editable" contenteditable="true" style="background-color: #fff8dc;">${jumlahTrans}</td>
                <td class="editable" ${user_role_diskon === 'customer' ? '' : 'contenteditable="true"'}>${diskonBarang}</td>
                <td class="editable" ${user_role_diskon === 'customer' ? '' : 'contenteditable="true"'}>${diskonBarangRp}</td>
                <td class="editable" ${user_role_diskon !== 'customer' && user_role_diskon !== 'staff' ? 'contenteditable="true"' : ''}>${ppnBarang}</td>
                <td>${format_ribuan(gtotal)}</td>
                <td class="d-none dpp-val">${dpp}</td>
                <td class="d-none ppn-val">${total_ppn}</td>
                <td>
                <button type="button" class="btn btn-primary btn-sm edit-row" data-id="${id}"><i class="fa fa-save" aria-hidden="true"></i></button>
                <button type="button" class="btn btn-danger btn-sm delete-row" data-id="${id}"><i class="fa fa-trash" aria-hidden="true"></i></button>
                </td>
            </tr>
        `;
        // d-none dpp-val dan ppn-val untuk mengakali akumulasi jumlah dpp dan ppn
        $('#transaksi_table tbody').append(newRow);
        updateRowNumbers();

        grandTotal += gtotal;
        $('#grand_total').text(format_ribuan(grandTotal));
        grandTotalDpp += dpp;
        $('#grand_total_dpp').text(format_ribuan(grandTotalDpp));
        grandTotalPpn += total_ppn;
        $('#grand_total_ppn').text(format_ribuan(grandTotalPpn));

        // this.reset();
        $('#select_barang').val(null).trigger('change');
        $('#nama_barang').text('-');
        $('#harga_barang').text('-');
        $('#unit_barang').text('-');
        $('#stok_barang').text('-');
        $('#select_barang_satuan').empty();
        $('#select_barang_satuan').append('<option value="">Pilih Satuan</option>');
        $('#jumlah_trans').val('');
        $('#diskon_barang').val('');
        $('#diskon_barang_rp').val('');
        loadInputPajak();

        setTimeout(function() {
            $('#select_barang').select2('open');
            document.querySelector('.select2-search__field').focus();
        }, 0);

    };

    function updateRowNumbers() {
        $('#transaksi_table tbody tr').each(function(index) {
            $(this).find('.row-number').text(index + 1);
        });
    }

    // ### Editing Baris Table
    $('#transaksi_table').on('input', '.editable', function() {
        let row = $(this).closest('tr');
        let hargaBarang = parseFloat(row.find('td:eq(3)').text()) || 0;
        let newJumlah = parseFloat(row.find('td:eq(6)').text()) || 0;
        let diskonBarang = parseFloat(row.find('td:eq(7)').text()) || 0;
        let diskonBarangRp = parseFloat(row.find('td:eq(8)').text()) || 0;
        let ppnBarang = parseFloat(row.find('td:eq(9)').text()) || 0;

        let oldTotal = parseFloat(hapus_format(row.find('td:eq(10)').text())) || 0;
        let oldDpp = parseFloat(row.find('.dpp-val').text()) || 0;
        let oldPpn = parseFloat(row.find('.ppn-val').text()) || 0;

        let diskon_dalam_uang = (diskonBarang / 100) * hargaBarang;
        let harga_setelah_diskon = hargaBarang - diskon_dalam_uang - diskonBarangRp;
        let total = harga_setelah_diskon * newJumlah;

        let dpp = Math.round(total / (1 + ppnBarang / 100));
        let total_ppn = total - dpp;
        let gtotal = total;

        row.find('td:eq(10)').text(format_ribuan(gtotal));
        row.find('.dpp-val').text(dpp);        // update nilai DPP
        row.find('.ppn-val').text(total_ppn);  // update nilai PPN

        grandTotal = grandTotal - oldTotal + gtotal;
        grandTotalDpp = grandTotalDpp - oldDpp + dpp;
        grandTotalPpn = grandTotalPpn - oldPpn + total_ppn;

        $('#grand_total').text(format_ribuan(grandTotal));
        $('#grand_total_dpp').text(format_ribuan(grandTotalDpp));
        $('#grand_total_ppn').text(format_ribuan(grandTotalPpn));
    });
// =============================== End Of Input Barang To Table =========================================
// ================================== Edit Barang ============================================
    $('#transaksi_table').on('click', '.edit-row', function () {
        const row = $(this).closest('tr');
        const id = $(this).data('id');

        const jumlah = parseFloat(row.find('td:eq(6)').text()) || 0;
        const diskon = parseFloat(row.find('td:eq(7)').text()) || 0;
        const diskon_rp = parseFloat(row.find('td:eq(8)').text()) || 0;
        const ppn_trans = parseFloat(row.find('td:eq(9)').text()) || 0;

        $.ajax({
            url: "{{ route('update_products') }}",
            type: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                id: id,
                product: { jumlah, diskon, diskon_rp, ppn_trans }
            },
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: response.message,
                    showConfirmButton: false,
                    timer: 1500
                });
            },
            error: function (xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan saat menyimpan data.',
                });
            }
        });
    });
// =============================== End Of Edit Barang =========================================
// ================================== Delete Barang ============================================
    // ### Detele Table
    $('#transaksi_table').on('click', '.delete-row', function() {
        const button = $(this); // ✅ simpan referensi tombol
        const id = $(this).data('id');
        $.ajax({
            url: "{{ route('delete_product_baris') }}",
            type: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                id: id,
            },
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: response.message,
                    showConfirmButton: false,
                    timer: 1500
                });
                let row = button.closest('tr');
                let total_text = row.find('td:eq(10)').text();
                let total = parseFloat(hapus_format(total_text)) || 0;

                let dpp = parseFloat(row.find('.dpp-val').text()) || 0;
                let total_ppn = parseFloat(row.find('.ppn-val').text()) || 0;

                grandTotal -= total;
                grandTotalDpp -= dpp;
                grandTotalPpn -= total_ppn;

                row.remove();
                updateRowNumbers();

                $('#grand_total').text(format_ribuan(grandTotal));
                $('#grand_total_dpp').text(format_ribuan(grandTotalDpp));
                $('#grand_total_ppn').text(format_ribuan(grandTotalPpn));
            },
            error: function (xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan saat menyimpan data.',
                });
            }
        });
    });
// =============================== End Of Delete Barang =========================================
// =================================== Submit Barang To DB XXX ==============================================
    $('#save_table_transaksi').on('click', function () {
        const kode_user = $("#kode_user_trans").val();
        const products = [];
        let is_valid = true; // Untuk memeriksa validasi secara keseluruhan

        // Loop melalui setiap baris di tabel
        $('#transaksi_table tbody tr').each(function () {
            const kd_barang = $(this).find('td:eq(1)').text(); // KD Barang
            const nama = $(this).find('td:eq(2)').text();      // Nama Barang
            const harga = $(this).find('td:eq(3)').text();     // Harga Barang
            const unit = $(this).find('td:eq(4)').text();      // Unit Barang
            const satuan = $(this).find('td:eq(5)').text();    // Satuan Barang
            const jumlah = $(this).find('td:eq(6)').text();    // Jumlah (editable)
            const diskon = $(this).find('td:eq(7)').text();    // Diskon (editable)
            const diskon_rp = $(this).find('td:eq(8)').text();
            const ppn_trans = $(this).find('td:eq(9)').text();
            const total_text = $(this).find('td:eq(10)').text();     // Total
            const total = hapus_format(total_text);
            // Validasi jumlah: tidak boleh kosong, harus angka, dan lebih besar dari 0
            if (!jumlah || isNaN(jumlah) || parseFloat(jumlah) <= 0) {
                is_valid = false;
                Swal.fire({
                    icon: 'warning',
                    title: 'Jumlah Tidak Valid',
                    text: 'Jumlah harus berupa angka dan lebih besar dari 0 di salah satu baris!',
                    showConfirmButton: false,
                    timer: 2000 // Durasi tampil dalam milidetik
                });
                return false; // Hentikan loop jika tidak valid
            }

            // Validasi diskon: harus angka (boleh 0)
            if (diskon === "" || diskon.trim() === "" || isNaN(diskon)) {
                is_valid = false;
                Swal.fire({
                    icon: 'warning',
                    title: 'Diskon Tidak Valid',
                    text: 'Diskon harus berupa angka, bisa 0, dan tidak boleh kosong!',
                    showConfirmButton: false,
                    timer: 2000 // Durasi tampil dalam milidetik
                });
                return false; // Hentikan loop jika tidak valid
            }

            // Validasi diskon: harus angka (boleh 0)
            if (diskon_rp === "" || diskon_rp.trim() === "" || isNaN(diskon_rp)) {
                is_valid = false;
                Swal.fire({
                    icon: 'warning',
                    title: 'Diskon Tidak Valid',
                    text: 'Diskon harus berupa angka, bisa 0, dan tidak boleh kosong!',
                    showConfirmButton: false,
                    timer: 2000 // Durasi tampil dalam milidetik
                });
                return false; // Hentikan loop jika tidak valid
            }

            if (ppn_trans === "" || ppn_trans.trim() === "" || isNaN(ppn_trans)) {
                is_valid = false;
                Swal.fire({
                    icon: 'warning',
                    title: 'PPN Tidak Valid',
                    text: 'PPN harus berupa angka, bisa 0, dan tidak boleh kosong!',
                    showConfirmButton: false,
                    timer: 2000 // Durasi tampil dalam milidetik
                });
                return false; // Hentikan loop jika tidak valid
            }

            if (kode_user === "") {
                is_valid = false;
                Swal.fire({
                    icon: 'warning',
                    title: 'Kode User Tidak Valid',
                    text: 'Kode User tidak boleh kosong!',
                    showConfirmButton: false,
                    timer: 2000 // Durasi tampil dalam milidetik
                });
                return false; // Hentikan loop jika tidak valid
            }

            // Masukkan ke array hanya jika KD Barang ada
            if (kd_barang) {
                products.push({
                    kd_barang,
                    nama,
                    harga,
                    unit,
                    satuan,
                    jumlah: parseFloat(jumlah), // Pastikan formatnya angka
                    diskon: parseFloat(diskon), // Pastikan formatnya angka
                    diskon_rp: parseFloat(diskon_rp),
                    ppn_trans: parseFloat(ppn_trans),
                    total: parseFloat(total) // Bersihkan format jika ada titik
                });
            }
        });

        // Pastikan validasi lolos sebelum mengirim data ke server
        if (!is_valid) {
            return; // Hentikan eksekusi jika validasi gagal
        }

        // Kirim data ke server jika ada produk
        if (products.length > 0) {
            save_to_database(products,kode_user);
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Save Failed',
                text: 'Tidak Ada Data Disimpan',
                showConfirmButton: false,
                timer: 2000 // Durasi tampil dalam milidetik
            });
        }
    });

    function save_to_database(products,kode_user) {
        $('#loading_modal').modal('show');
        setTimeout(function () {
            $.ajax({
                url: '{{ route('save_products') }}',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    products: products,
                    kode_user:kode_user
                },
                success: function (response) {
                    $('#loading_modal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Save Successful',
                        html: `
                            <p>Data Berhasil Disimpan dengan Nomor Invoice: <b>${response.invoice_number}</b></p>
                            <div style="margin-top:20px;">
                                <button id="btn-ok" class="swal2-confirm swal2-styled" style="background-color:#3085d6;">OK</button>
                                <button id="btn-pdf" class="swal2-confirm swal2-styled" style="background-color:#6c757d;">Show PDF</button>
                                <button id="btn-struk" class="swal2-confirm swal2-styled" style="background-color:#28a745;">Print Struk</button>
                            </div>
                        `,
                        showConfirmButton: false, // matikan tombol default
                        didOpen: () => {
                            // Event listener untuk tombol OK
                            document.getElementById('btn-ok').addEventListener('click', () => {
                                success_call();
                                Swal.close();
                                // ### Redirect Hal Edit
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
                            });

                            // Event listener untuk tombol Print PDF
                            document.getElementById('btn-pdf').addEventListener('click', () => {
                                window.open('{{ route("generate_pdf", ":invoice_number") }}'.replace(':invoice_number', response.invoice_number), '_blank');
                                success_call();
                                Swal.close();
                                // ### Redirect Hal Edit
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
                            });

                            // Event listener untuk tombol Print Struk
                            document.getElementById('btn-struk').addEventListener('click', () => {
                                Swal.close();
                                let nilai_total_modal = $('#grand_total').text().trim();
                                // let nilaiBersih = unformatRupiah(nilai_total_modal);
                                $("#total_harga_modal").val(nilai_total_modal);
                                // function unformatRupiah(str) {
                                //     if(!str) return 0;
                                //     // hapus semua titik (ribuan), ganti koma dengan titik
                                //     let cleaned = str.replace(/\./g, '').replace(',', '.');
                                //     return parseFloat(cleaned);
                                // }
                                $('#payment_modal').data('invoice', response.invoice_number);
                                $('#payment_modal').modal('show');
                                // setTimeout(function () {
                                //     save_faktur(response.invoice_number);
                                // }, 1200);
                            });
                        }
                    });
                    // Swal.fire({
                    //     icon: 'success',
                    //     title: 'Save Successful',
                    //     text: 'Data Berhasil Disimpan dengan Nomor Invoice: ' + response.invoice_number,
                    //     showConfirmButton: true, // Tampilkan tombol OK
                    //     confirmButtonText: 'OK', // Ubah teks tombol jika diperlukan
                    //     showDenyButton: true, // Show Deny button for Print PDF
                    //     denyButtonText: 'Print PDF',
                    // }).then((result) => {
                    //     if (result.isConfirmed) {
                    //         // Callback setelah tombol OK ditekan
                    //         success_call();
                    //     }   else if (result.isDenied) {
                    //         window.open('{{ route("generate_pdf", ":invoice_number") }}'.replace(':invoice_number', response.invoice_number), '_blank');
                    //             success_call();
                    //         }
                    // });
                },
                error: function (xhr, status, error) {
                    console.log("Status: " + status);  // Menampilkan status HTTP
                    console.log("Error: " + error);  // Menampilkan error message
                    console.log(xhr.responseText);
                    $('#loading_modal').modal('hide');
                    alert('Failed to save data.');
                }
            });
        }, 1200);
    }
    function success_call(){
        $('#transaksi_table tbody').empty();
        if(user_role_select != 'customer'){
            $('#select_user_trans').val(null).trigger('change');
            $("#kode_user_trans").val('');
            $("#nama_user_trans").val('');
        }
        $('#grand_total').text(0);
        grandTotal = 0;
        $('#grand_total_dpp').text(0);
        grandTotalDpp = 0;
        $('#grand_total_ppn').text(0);
        grandTotalPpn = 0;
    }
// ================================= End Of Submit Barang To DB =========================================
// ==================================== Model Transaksi Cetak ============================================
    // Ganti tampilan sesuai metode pembayaran
    $("#paymentMethod").on("change", function(){
        let method = $(this).val();
        if(method === "cash"){
        $("#bankName").val('');
        $("#cashAmount").val('');
        $("#changeAmount").val('');
        $("#cashSection").removeClass("d-none");
        $("#transferSection").addClass("d-none");
        } else if(method === "transfer"){
        $("#bankName").val('');
        $("#cashAmount").val('');
        $("#changeAmount").val('');
        $("#transferSection").removeClass("d-none");
        $("#cashSection").addClass("d-none");
        } else if(method === "bon"){
        $("#bankName").val('');
        $("#cashAmount").val('');
        $("#changeAmount").val('');
        $("#transferSection").addClass("d-none");
        $("#cashSection").addClass("d-none");
        } else {
        $("#cashSection, #transferSection").addClass("d-none");
        }
    });

    // Hitung kembalian otomatis
    $("#cashAmount").on("input", function(){
        let total = hapus_format($("#total_harga_modal").val());
        let bayar = parseFloat($(this).val());
        let kembali = bayar - total;
        $("#changeAmount").val(kembali >= 0 ? kembali : 0);
    });

    // Validasi saat cetak
    $("#cetak_faktur").on("click", function(){
        let method = $("#paymentMethod").val();
        let total = hapus_format($("#total_harga_modal").val());
        let nama_bank = $("#bankName").val();
        let jumlah_bayar = $("#cashAmount").val();
        let jumlah_kembalian = $("#changeAmount").val();

        if(method === "cash"){
        let bayar = parseFloat($("#cashAmount").val());
        if(!bayar || bayar < total){
            alert("Bayaran kurang! Harap isi nominal dengan benar.");
            return;
        }
        }
        else if(method === "transfer"){
        let bank = $("#bankName").val().trim();
        if(bank === ""){
            alert("Harap isi nama bank untuk transfer.");
            return;
        }
        }
        else if (method === "bon"){

        }
        else {
        alert("Pilih metode pembayaran dulu.");
        return;
        }
        // ✅ Ambil invoice dari data modal
        let invoice_number = $("#payment_modal").data("invoice");
        $('#payment_modal').modal('hide');
        $('body').removeClass('modal-open').css('overflow', 'auto').css('padding-right', '');
        $('.modal-backdrop').remove();
        $('#loading_modal').modal('show');
        // Panggil fungsi simpan
        setTimeout(function () {
            save_faktur(invoice_number, method, nama_bank, jumlah_bayar, jumlah_kembalian);
        }, 1200);
    });

    $("#cancel_btn_cetak").on("click", function(){
        $('#payment_modal').modal('hide');
        $('body').removeClass('modal-open').css('overflow', 'auto').css('padding-right', '');
        $('.modal-backdrop').remove();
        success_call();
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
    });
// ============================== End of Model Transaksi Cetak ========================================
// ==================================== Submit to Faktur ============================================
    function save_faktur(invoice_number, method, nama_bank, jumlah_bayar, jumlah_kembalian) {
        $.ajax({
            url: '{{ route("save_faktur") }}',
            type: 'POST',
            data: {
                invoice_number: invoice_number,
                method: method,
                nama_bank: nama_bank,
                jumlah_bayar: jumlah_bayar,
                jumlah_kembalian: jumlah_kembalian,
                _token: '{{ csrf_token() }}'
            },
            success: function(res) {
                // $('#payment_modal').modal('hide');
                // $('.modal-backdrop').remove();
                console.log("Faktur berhasil:", res.no_faktur);
                // Cetak ke RawBT
                window.location.href = "rawbt:base64," + res.struk_text;
                $('#loading_modal').modal('hide');
                // let encodedStruk = encodeURIComponent(res.struk_text);
                // window.location.href = "rawbt://print?text=" + encodedStruk;
                setTimeout(function () {
                    Swal.fire({
                        icon: 'success',
                        title: 'Cetak Copy 2',
                        text: 'Data Berhasil Dicetak dengan Nomor Faktur: ' + res.no_faktur,
                        showConfirmButton: true,
                        confirmButtonText: 'OK',
                    }).then(() => {
                        window.location.href = "rawbt:base64," + res.struk_text2;
                        success_call();
                        // ### Redirect Hal Faktur
                        $.ajax({
                            url: '{{ route('index_faktur') }}',
                            type: 'GET',
                            success: function(response) {
                                $('.master-page').html(response);
                            },
                            error: function() {
                                $('.master-page').html('<p>Error loading form.</p>');
                            }
                        });
                    });
                }, 1200);
            },
            error: function(xhr) {
                console.error("Status:", xhr.status);
                console.error("Response Text:", xhr.responseText);
                console.error("Error:", xhr);

                // Kalau Laravel kirim JSON error, bisa parse biar rapi
                try {
                    let json = JSON.parse(xhr.responseText);
                    console.error("Laravel error message:", json.message);
                    console.error("Laravel error trace:", json);
                } catch(e) {
                    console.warn("Bukan JSON, tampilkan raw text di atas.");
                }

                alert('Gagal membuat faktur. Lihat console browser untuk detail.');
            }
        });
    }
// ================================= End Of Submit to Faktur =========================================
// ============================== Number Formating =====================================
    function hapus_format(angka) {
        // Menghapus titik pemisah ribuan
        return parseFloat(angka.replace(/\./g, ""));
    }

    function format_ribuan(angka) {
        // Pastikan angka memiliki 2 digit desimal
        let fixed = parseFloat(angka).toFixed(2); // Contoh: "1000.00"
        // Pisahkan integer dan desimal
        let parts = fixed.split("."); // Contoh: ["1000", "00"]
        // Format bagian integer dengan titik ribuan
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        // Gabungkan kembali integer dan desimal
        return parts.join(",");
    }
// ============================== End Of Number Formating =====================================
});
</script>
