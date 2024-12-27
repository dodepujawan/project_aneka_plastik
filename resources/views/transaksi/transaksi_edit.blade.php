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
    max-height: 500px; /* Sesuaikan tinggi maksimum sesuai kebutuhan */
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
</style>
<div id="master_table_edit_field">
    <h1>Halaman Edit PO</h1>
    <div class="mt-3 table-container table-responsive">
        <table id="transaksi_table_edit_field" class="display table table-bordered mb-2">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No PO</th>
                    <th>Tgl PO</th>
                    <th>Total PO</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <table id="transaksi_table_edit_field_admin" class="display table table-bordered mb-2">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No PO</th>
                    <th>Customer Kode</th>
                    <th>Customer</th>
                    <th>Tgl PO</th>
                    <th>Total PO</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<div class="master_transaksi_field" style="display: none">
    <div class="col-lg-12 alert alert-warning" id="mssakit_warning" role="alert" style="margin-top: 10px; opacity: 0.8;">
        <h5 style="font-weight: bold;">Mode Edit Data PO</h5>
    </div>
    <div class="container master_transaksi_edit">
        <div class="product-info">
            <input type="hidden" value="" id="kd_barang" readonly>

            <!-- Informasi Barang -->
            <div class="row">
                <div class="col-md-4 info-item">
                    <h5>Nama Barang: <span id="nama_barang_edit">-</span></h5>
                </div>
                <div class="col-md-4 info-item">
                    <h5>Unit Qty: <span id="unit_barang_edit">-</span></h5>
                </div>
                <div class="col-md-4 info-item">
                    <h5>Satuan Barang: <span id="satuan_barang_edit">-</span></h5>
                </div>
            </div>

            <!-- Garis Pembatas -->
            <div class="underline"></div>

            <!-- Informasi Harga dan Stok -->
            <div class="row mt-3">
                <div class="col-md-6 info-item">
                    <h5>Harga Barang: <span id="harga_barang_edit">-</span></h5>
                </div>
                <div class="col-md-6 info-item">
                    <h5>Stok Barang: <span id="stok_barang_edit">-</span></h5>
                </div>
            </div>
        </div>
    </div>
    <form action="" class="row mt-3">
        <div class="col-lg-5 col-md-12 mb-3">
            <div class="d-flex align-items-center">
            <button type="button" id="clear_select_edit" class="btn btn-secondary btn-sm me-2 mr-2">
                <i class="fa fa-eraser" aria-hidden="true"></i>
            </button>
            <select name="select_barang_edit" id="select_barang_edit" class="form-control">
                <option></option>
                <!-- Options untuk select dropdown -->
            </select>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
            <input type="number" id="jumlah_trans_edit" class="form-control" placeholder="Jumlah barang">
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
            <div class="d-flex align-items-center">
                @php
                    $user = Auth::user();
                    $allowed_roles = ['customer'];
                    $is_customer = in_array($user->roles, $allowed_roles);
                @endphp
            <input type="number" id="diskon_barang_edit" class="form-control" placeholder="Diskon barang dalam %" {{ $is_customer ? 'readonly' : '' }}>
            <button type="submit" class="btn btn-success btn-sm ms-2 ml-2">
                <i class="fa fa-check" aria-hidden="true"></i>
            </button>
            </div>
        </div>
    </form>
    <div class="mt-3 table-container table-responsive">
        <table id="transaksi_table_edit" class="display table table-bordered mb-2">
            <thead>
                <tr>
                    <th>No</th>
                    <th>KD Barang</th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Unit</th>
                    <th>Satuan</th>
                    <th>Jumlah</th>
                    <th>Diskon</th>
                    <th>Total</th>
                    <th>del</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="8" class="text-right"><strong>Grand Total:</strong></td>
                    <td id="grand_total_edit">0</td>
                    {{-- <td id="grand_total_edit_mirror">0</td> --}}
                </tr>
            </tfoot>
            <tbody>
                <!-- Data akan diisi oleh DataTables -->
            </tbody>
        </table>
    </div>
    <div class="button-container" style="display: flex; justify-content: flex-end; gap: 10px;">
        <button type="submit" class="btn btn-primary mt-2 mb-2" id="save_table_transaksi_edit"><i class="fas fa-save"> Proses</i></button>
        <button type="submit" class="btn btn-info mt-2 mb-2" id="reset_table_transaksi_edit"><i class="fas fa-sync-alt"> Reset</i></button>
        <button type="submit" class="btn btn-warning mt-2 mb-2" id="return_table_transaksi_edit"><i class="fas fa-undo"> Kembali</i></button>
    </div>
</div>
{{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script> --}}

<script>
$(document).ready(function(){
// ===================================== Show Table PO ==============================================
    let user_role_select = @json(Auth::user()->roles);
    let user_kode_select = @json(Auth::user()->user_kode);
    let user_nama_select = @json(Auth::user()->name);
    // console.log('kode:' + user_role_select);
    // console.log('nama:' + user_nama_select);
    if(user_role_select == 'customer'){
        $("#transaksi_table_edit_field").show();
        $("#transaksi_table_edit_field_admin").hide();
        show_table_po();
    }else{
        $("#transaksi_table_edit_field").hide();
        $("#transaksi_table_edit_field_admin").show();
        show_table_po_admin();
    }
    function show_table_po(){
        if ($.fn.dataTable.isDataTable('#transaksi_table_edit_field')) {
            $('#transaksi_table_edit_field').DataTable().destroy();
        }
        const table = $('#transaksi_table_edit_field').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: '{{ route('get_edit_transaksi_data') }}',
                type: 'GET',
            },
            columns: [
                {
                    data: null,
                    name: 'no',
                    render: (data, type, row, meta) => meta.row + 1, // Nomor otomatis
                },
                { data: 'no_invoice', name: 'no_invoice' },
                { data: 'created_at', name: 'created_at' },
                {
                    data: 'total',
                    name: 'total',
                    render: $.fn.dataTable.render.number(',', '.', 2, 'Rp ') // Format angka jadi Rupiah
                },
                {
                    data: 'no_invoice',
                    orderable: false,
                    render: (data, type, row) => {
                        return `
                            <div style="display: flex; justify-content: center; gap: 0.5rem;">
                                <button class="btn btn-sm btn-primary edit-btn" data-no-invoice="${row.no_invoice}" style="margin-right: 0;">
                                    <i class="fa fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-sm btn-danger delete-btn" data-no-invoice="${row.no_invoice}">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                            </div>
                        `;
                    },
                },
            ],
        });
    }

    // ### Versi Admin ###
    function show_table_po_admin(){
        if ($.fn.dataTable.isDataTable('#transaksi_table_edit_field_admin')) {
            $('#transaksi_table_edit_field_admin').DataTable().destroy();
        }
        const table = $('#transaksi_table_edit_field_admin').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: '{{ route('get_edit_transaksi_data_admin') }}',
                type: 'GET',
            },
            columns: [
                {
                    data: null,
                    name: 'no',
                    render: (data, type, row, meta) => meta.row + 1, // Nomor otomatis
                },
                { data: 'no_invoice', name: 'no_invoice' },
                { data: 'user_kode', name: 'user_kode' },
                { data: 'nama_cust', name: 'nama_cust' },
                { data: 'created_at', name: 'created_at' },
                {
                    data: 'total',
                    name: 'total',
                    render: $.fn.dataTable.render.number(',', '.', 2, 'Rp ') // Format angka jadi Rupiah
                },
                {
                    data: 'no_invoice',
                    orderable: false,
                    render: (data, type, row) => {
                        return `
                            <div style="display: flex; justify-content: center; gap: 0.5rem;">
                                <button class="btn btn-sm btn-primary edit-btn" data-no-invoice="${row.no_invoice}" style="margin-right: 0;">
                                    <i class="fa fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-sm btn-danger delete-btn" data-no-invoice="${row.no_invoice}">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                            </div>
                        `;
                    },
                },
            ],
        });
    }
// ================================= End Of Show Table PO ===========================================
// ================================= Click Edit Button ===========================================
    let grandTotal = 0;
    // console.log(grandTotal);
    $(document).on('click', '#transaksi_table_edit_field .edit-btn', function () {
        let no_invoice = $(this).data('no-invoice');
        ajax_click_edit_button(no_invoice);
    });
    $(document).on('click', '#transaksi_table_edit_field_admin .edit-btn', function () {
        let no_invoice = $(this).data('no-invoice');
        ajax_click_edit_button(no_invoice);
    });
    function ajax_click_edit_button(no_invoice){
        $.ajax({
            url: '{{ route('get_edit_transaksi_to_table') }}', // Ganti dengan route yang sesuai
            type: 'GET',
            data: {
                no_invoice: no_invoice // Mengirimkan no_invoice ke server
            },
            success: function (response) {
                $('#transaksi_table_edit tbody').empty();
                const get_no_nvoice = response.data[0].no_invoice;
                $('#save_table_transaksi_edit').val(get_no_nvoice);
                $('#reset_table_transaksi_edit').val(get_no_nvoice);
                let user_role_diskon = @json(Auth::user()->roles);
                // let grandTotal = 0;

                response.data.forEach((item, index) => {
                    let total = item.total; // Menggunakan nilai total yang sudah ada dari database
                    grandTotal += total;// Menambahkan ke grand total
                    // console.log('TEST: ' + item.kd_brg); // Cek item.kd_brg
                    let total_order = format_ribuan(item.total);
                    $('#transaksi_table_edit tbody').append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.kd_brg}</td>
                            <td>${item.nama_brg}</td>
                            <td>${item.harga}</td>
                            <td>${item.qty_unit}</td>
                            <td>${item.satuan}</td>
                            <td class="editable" contenteditable="true">${item.qty_order}</td>
                            <td class="editable" ${user_role_diskon === 'customer' ? '' : 'contenteditable="true"'}>${item.disc}</td>
                            <td>${total_order}</td>
                            <td><button type="button" class="btn btn-danger btn-sm delete-row"><i class="fa fa-trash" aria-hidden="true"></i></button></td>
                        </tr>
                    `);
                });

                $('#grand_total_edit').text(format_ribuan(grandTotal));
                $('#master_table_edit_field').hide();
                $('.master_transaksi_field').show();
                select2_call();
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error: ', status, error);
            }
        });
    }
// ================================= End Of Click Edit Button ===========================================
// ================================= Click Delete Button ===========================================
    $(document).on('click', '#transaksi_table_edit_field .delete-btn', function () {
        let no_invoice = $(this).data('no-invoice');
        let row = $(this).closest('tr');  // Mendapatkan baris terdekat dari tombol delete
        Swal.fire({
            title: 'Anda yakin?',
            text: `Hapus Data dengan No. Invoice ${no_invoice} ?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route('delete_products') }}',
                    type: 'POST',
                    data: {
                        value_invo: no_invoice,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        $('#transaksi_table_edit_field').DataTable().row(row).remove().draw(false);
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: `Data dengan No. Invoice ${no_invoice} berhasil dihapus.`,
                            showConfirmButton: false,
                            timer: 2000 // Durasi tampil dalam milidetik
                        });
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: `Error: ${xhr.responseJSON.error}`
                        });
                    }
                });
            }
        });
    });
// ================================= End Of Click Delete Button ===========================================
// ================================= Select Barang ===========================================
    function select2_call(){
        $('#select_barang_edit').select2({
            // tags: true,
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
                            return {
                                id: barang.id,
                                text: barang.kd_barang + ' / ' + barang.nama_barang + ' / ' + barang.satuan,
                                kd_barang: barang.kd_barang,
                                nama: barang.nama_barang,
                                kemasan: barang.satuan,
                                harga: barang.harga,
                                unit:barang.q_unit,
                                stok: barang.stok
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
    }
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
                    (barang.kemasan ? ' / ' + barang.kemasan : '') +
                '</div>' +
            '</div>'
        );
        return $barang;
    }

    // ### Event listener saat item dipilih
    $('#select_barang_edit').on('select2:select', function(e) {
        var data = e.params.data; // Data yang dipilih
        $('#kd_barang').val(data.kd_barang);
        $('#nama_barang_edit').text(data.nama);
        $('#unit_barang_edit').text(data.unit);
        $('#satuan_barang_edit').text(data.kemasan);
        $('#harga_barang_edit').text(format_ribuan(data.harga));
        $('#stok_barang_edit').text(format_ribuan(data.stok));
    });

    // ### Clear Selected Barangs
    $('#clear_select_edit').on('click', function() {
        $('#select_barang_edit').val(null).trigger('change'); // Kosongkan pilihan
        $('#kd_barang').val("").trigger('change');
        $('#nama_barang_edit').text('-').trigger('change');
        $('#unit_barang_edit').text('-').trigger('change');
        $('#satuan_barang_edit').text('-').trigger('change');
        $('#harga_barang_edit').text('-').trigger('change');
        $('#stok_barang_edit').text('-').trigger('change');
    });

    $(document).on('select2:open', () => {
        document.querySelector('.select2-search__field').focus();
    });

    // === fungsi enter next di form ===
    // ### Mencegah submit ketika Enter kecuali pada tombol submit (fungsi enter jadi next)
    $('form').on('keydown', 'input, select', function(e) {
        if (e.keyCode == 13) {
            e.preventDefault(); // Mencegah submit

            // Pindah ke elemen input atau select berikutnya
            var focusable = $('form').find('input, select, button').filter(':visible');
            var nextIndex = focusable.index(this) + 1;
            if (nextIndex < focusable.length) {
                focusable.eq(nextIndex).focus();
            } else {
                // Jika sudah sampai di elemen terakhir (submit button), submit form
                $('form').submit();
            }
        }
    });

    // ### Tangani default enter di select2
    $('#select_barang_edit').on('select2:select', function(e) {
        // Pindahkan fokus ke input selanjutnya ketika item dipilih
        var focusable = $('form').find('input, select, button').filter(':visible');
        var nextIndex = focusable.index(this) + 1;
        if (nextIndex < focusable.length) {
            focusable.eq(nextIndex).focus();
        }
    });
        // Mencegah Enter pada select2 agar tidak trigger submit
    $('#select_barang_edit').on('keypress', function(e) {
        if (e.keyCode == 13) {
            e.preventDefault(); // Mencegah select2 dari trigger submit ketika tekan Enter
        }
    });
        // === end of fungsi enter next di form ===
// ================================= End Of Select Barang ===========================================
// ================================= Input Barang To Table ===========================================
    // let grandTotal = 0;
    $('form').on('submit', function(event) {
        event.preventDefault();

        let kdBarang = $('#kd_barang').val();
        let namaBarang = $('#nama_barang_edit').text();
        let hargaBarangText = $('#harga_barang_edit').text();
        let hargaBarang = parseFloat(hapus_format(hargaBarangText)) || 0;
        let unitBarang = $('#unit_barang_edit').text();
        let satuanBarang = $('#satuan_barang_edit').text();
        let jumlahTrans = parseFloat($('#jumlah_trans_edit').val()) || 0;
        let diskonBarang = parseFloat($('#diskon_barang_edit').val()) || 0;
            // Pengecekan untuk nilai kosong
        if (!kdBarang || !namaBarang || hargaBarang === 0 || jumlahTrans === 0) {
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
        // rumus diskon
        let diskon_dalam_uang = (diskonBarang / 100) * hargaBarang;
        let total = (hargaBarang - diskon_dalam_uang) * jumlahTrans;
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
                <td class="editable" contenteditable="true">${jumlahTrans}</td>
                <td class="editable" ${user_role_diskon === 'customer' ? '' : 'contenteditable="true"'}>${diskonBarang}</td>
                <td>${format_ribuan(total)}</td>
                <td><button type="button" class="btn btn-danger btn-sm delete-row"><i class="fa fa-trash" aria-hidden="true"></i></button></td>
            </tr>
        `;
        $('#transaksi_table_edit tbody').append(newRow);
        updateRowNumbers();

        grandTotal += total;
        $('#grand_total_edit').text(format_ribuan(grandTotal));

        this.reset();
        $('#select_barang_edit').val(null).trigger('change');
        $('#nama_barang_edit').text('-');
        $('#harga_barang_edit').text('-');
        $('#stok_barang_edit').text('-');
        $('#unit_barang_edit').text('-');
        $('#satuan_barang_edit').text('-');

        setTimeout(function() {
            $('#select_barang_edit').select2('open');
            document.querySelector('.select2-search__field').focus();
        }, 0);

    });

    // ### Detele Table
    $('#transaksi_table_edit').on('click', '.delete-row', function() {
        let row = $(this).closest('tr');
        let total_text = row.find('td:eq(8)').text();
        let total =  parseFloat(hapus_format(total_text)) || 0;
        row.remove();
        updateRowNumbers();

        grandTotal -= total;
        $('#grand_total_edit').text(format_ribuan(grandTotal));
    });

    function updateRowNumbers() {
        $('#transaksi_table_edit tbody tr').each(function(index) {
            $(this).find('.row-number').text(index + 1);
        });
    }

    // ### Editing Baris Table
    $('#transaksi_table_edit').on('input', '.editable', function() {
        let row = $(this).closest('tr');
        let hargaBarang = parseFloat(row.find('td:eq(3)').text()) || 0;
        let newJumlah = parseFloat(row.find('td:eq(6)').text()) || 0;
        let diskonBarang = parseFloat(row.find('td:eq(7)').text()) || 0;
        let oldTotal_text = row.find('td:eq(8)').text();
        let oldTotal = parseFloat(hapus_format(oldTotal_text))|| 0;

        // rumus diskon
        let diskon_dalam_uang = (diskonBarang / 100) * hargaBarang;
        // Hitung total baru dan update baris
        let total = (hargaBarang - diskon_dalam_uang) * newJumlah;
        // row.find('td:eq(8)').text(total.toFixed(2)); -> untuk dapat .00
        row.find('td:eq(8)').text(format_ribuan(total));

        // Update grand total
        grandTotal = grandTotal - oldTotal + total;
        $('#grand_total_edit').text(format_ribuan(grandTotal));
    });
// =============================== End Of Input Barang To Table =========================================
// =================================== Update Barang To DB ==============================================
    $('#save_table_transaksi_edit').on('click', function () {
        const products = [];
        let value_invo = $(this).val();
        let is_valid = true; // Untuk memeriksa validasi secara keseluruhan

        // Loop melalui setiap baris di tabel
        $('#transaksi_table_edit tbody tr').each(function () {
            const kd_barang = $(this).find('td:eq(1)').text(); // KD Barang
            const nama = $(this).find('td:eq(2)').text();      // Nama Barang
            const harga = $(this).find('td:eq(3)').text();     // Harga Barang
            const unit = $(this).find('td:eq(4)').text();      // Unit Barang
            const satuan = $(this).find('td:eq(5)').text();    // Satuan Barang
            const jumlah = $(this).find('td:eq(6)').text();    // Jumlah (editable)
            const diskon = $(this).find('td:eq(7)').text();    // Diskon (editable)
            const total_text = $(this).find('td:eq(8)').text();     // Total
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
            save_to_database_edit(products,value_invo);
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

    function save_to_database_edit(products,value_invo) {
        $.ajax({
            url: '{{ route('update_products') }}', // Endpoint Laravel
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'), // CSRF Token
                products: products,
                value_invo: value_invo
            },
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Update Successul',
                    text: 'Data Berhasil Diupdate',
                    showConfirmButton: false,
                    timer: 2000 // Durasi tampil dalam milidetik
                });
                // $('#transaksi_table_edit tbody').empty();
                // $('#grand_total_edit').text(0);
            },
            error: function (xhr, status, error) {
                console.log("Status: " + status);  // Menampilkan status HTTP
                console.log("Error: " + error);  // Menampilkan error message
                console.log(xhr.responseText);
                alert('Failed to save data.');
            }
        });
    }

// ================================= End Of Update Barang To DB =========================================
// ==================================== Reset Tabel PO ============================================
    $('#reset_table_transaksi_edit').on('click', function(){
        let value_invo = $(this).val();
        grandTotal = 0;
        ajax_click_edit_button(value_invo);
    });
// ================================= End Of Reset Tabel PO =========================================
// ================================= Return Tabel PO =========================================
    $('#return_table_transaksi_edit').on('click', function(){
        grandTotal = 0;
        $('#transaksi_table_edit tbody').empty();
        $('.master_transaksi_field').hide();
        $('#transaksi_table_edit_field').DataTable().destroy();
        $('#transaksi_table_edit_field tbody').empty();
        $('#master_table_edit_field').show();
        if(user_role_select == 'customer'){
            $("#transaksi_table_edit_field").show();
            $("#transaksi_table_edit_field_admin").hide();
            show_table_po();
        }else{
            $("#transaksi_table_edit_field").hide();
            $("#transaksi_table_edit_field_admin").show();
            show_table_po_admin();
        }
    });
// ============================== End Of Return Tabel PO =====================================
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
