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
</style>
<div class="container master_customer_select">
    <div class="row">
        <div class="form-group col-lg-4 col-md-12 col-sm-12 mb-3">
            <input type="text" name="kode_user_trans" id="kode_user_trans" class="form-control" placeholder="Kode User" required="" readonly>
        </div>
        <div class="form-group col-lg-4 col-md-12 col-sm-12 mb-3">
            <input type="text" name="nama_user_trans" id="nama_user_trans" class="form-control" placeholder="Nama User" required="" readonly>
        </div>
        <div class="form-group col-lg-4 col-md-12 col-sm-12 mb-3">
            <select name="select_user_trans" id="select_user_trans" class="form-control">
                <option value="">Pilih User</option>
            </select>
        </div>
    </div>
</div>

<div class="container master_transaksi">
    <h1>Halaman Input PO</h1>
    <div class="product-info">
        <input type="hidden" value="" id="kd_barang" readonly>
        <!-- Informasi Barang -->
        <div class="row">
            <div class="col-md-4 info-item">
                <h5>Nama: <span id="nama_barang">-</span></h5>
            </div>
            <div class="col-md-4 info-item">
                <h5>Harga: <span id="harga_barang">-</span></h5>
            </div>
            <div class="col-md-4 info-item">
                <h5>Qty: <span id="unit_barang">-</span></h5>
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
<form action="" class="row mt-3">
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
    <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
        <select name="select_barang_satuan" id="select_barang_satuan" class="form-control">
            <option value="">Pilih Satuan</option>
        </select>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
        <input type="number" id="jumlah_trans" class="form-control" placeholder="Jumlah barang">
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12 mb-3">
        <div class="d-flex align-items-center">
            @php
                $user = Auth::user();
                $allowed_roles = ['customer'];
                $is_customer = in_array($user->roles, $allowed_roles);
            @endphp
        <input type="number" id="diskon_barang" class="form-control" placeholder="Diskon %" {{ $is_customer ? 'readonly' : '' }}>
        <button type="submit" class="btn btn-success btn-sm ms-2 ml-2">
            {{-- <i class="fa fa-check" aria-hidden="true"></i> --}}Simpan
        </button>
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
                <td id="grand_total">0</td>
            </tr>
        </tfoot>
        <tbody>
            <!-- Data akan diisi oleh DataTables -->
        </tbody>
    </table>
</div>
<button type="submit" class="btn btn-primary mt-2" id="save_table_transaksi" style="float: right;"><i class="fas fa-save"> Proses</i></button>
{{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script> --}}

<script>
$(document).ready(function(){
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
            placeholder: 'Pilih User',
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
        if (kd_barang) {
            $.ajax({
                url: '{{ route("get_barang_satuan") }}',
                type: 'GET',
                data: { kd_barang: kd_barang },
                success: function (response) {
                    // alert(response[0].satuan);
                    $('#select_barang_satuan').empty();
                    response.forEach(function (item) {
                        console.log('Satuan:', item.satuan);
                        $('#select_barang_satuan').append(
                            `<option value="${item.satuan}">${item.satuan}</option>`
                        );
                    });
                    $('#nama_barang').text(response[0].NAMA_BRG);
                    let data_harga = response[0].hj1;
                    $('#harga_barang').text(format_ribuan(data_harga));
                    // menghapus nilani decimal dari dbase
                    let isi = response[0].isi.replace(/,/g, '');
                    let formatted_value = isi.replace(/\./g, ''); // Menghapus titik
                    let final_value = parseInt(formatted_value / 1000);
                    $('#unit_barang').text(final_value);
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
        if (satuan_barang) {
            $.ajax({
                url: '{{ route("get_barang_selected") }}',
                type: 'GET',
                data: { satuan_barang: satuan_barang, kd_barang: kd_barang },
                success: function(response) {
                    let data_harga = response.hj1;
                    $('#harga_barang').text(format_ribuan(data_harga));
                    let isi = response.isi.replace(/,/g, '');
                    let formatted_value = isi.replace(/\./g, ''); // Menghapus titik
                    let final_value = parseInt(formatted_value / 1000);
                    $('#unit_barang').text(final_value);
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
// ================================= Input Barang To Table ===========================================
    let grandTotal = 0;
    $('form').on('submit', function(event) {
        event.preventDefault();

        let kdBarang = $('#kd_barang').val();
        let namaBarang = $('#nama_barang').text();
        let hargaBarangText = $('#harga_barang').text();
        let hargaBarang = parseFloat(hapus_format(hargaBarangText)) || 0;
        let unitBarang = $('#unit_barang').text();
        let satuanBarang = $('#select_barang_satuan').val();
        let jumlahTrans = parseFloat($('#jumlah_trans').val()) || 0;
        let diskonBarang = parseFloat($('#diskon_barang').val()) || 0;
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
        $('#transaksi_table tbody').append(newRow);
        updateRowNumbers();

        grandTotal += total;
        $('#grand_total').text(format_ribuan(grandTotal));

        this.reset();
        $('#select_barang').val(null).trigger('change');
        $('#nama_barang').text('-');
        $('#harga_barang').text('-');
        $('#unit_barang').text('-');
        $('#select_barang_satuan').empty();
        $('#select_barang_satuan').append('<option value="">Pilih Satuan</option>');

        setTimeout(function() {
            $('#select_barang').select2('open');
            document.querySelector('.select2-search__field').focus();
        }, 0);

    });

    // ### Detele Table
    $('#transaksi_table').on('click', '.delete-row', function() {
        let row = $(this).closest('tr');
        let total_text = row.find('td:eq(8)').text();
        let total =  parseFloat(hapus_format(total_text)) || 0;
        row.remove();
        updateRowNumbers();

        grandTotal -= total;
        $('#grand_total').text(format_ribuan(grandTotal));
    });

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
        $('#grand_total').text(format_ribuan(grandTotal));
    });
// =============================== End Of Input Barang To Table =========================================
// =================================== Submit Barang To DB ==============================================
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
                        text: 'Data Berhasil Disimpan dengan Nomor Invoice: ' + response.invoice_number,
                        showConfirmButton: true, // Tampilkan tombol OK
                        confirmButtonText: 'OK', // Ubah teks tombol jika diperlukan
                    }).then(() => {
                        // Callback setelah tombol OK ditekan
                        $('#transaksi_table tbody').empty();
                        $('#grand_total').text(0);
                        grandTotal = 0;
                    });
                },
                error: function (xhr, status, error) {
                    console.log("Status: " + status);  // Menampilkan status HTTP
                    console.log("Error: " + error);  // Menampilkan error message
                    console.log(xhr.responseText);
                    $('#loading_modal').modal('hide');
                    alert('Failed to save data.');
                }
            });
        }, 2000);
    }

// ================================= End Of Submit Barang To DB =========================================
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
