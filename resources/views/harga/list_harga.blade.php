<style>
    .table-responsive {
        overflow: visible;
    }
    #listHargaTable {
        width: 100% !important;
    }
</style>
<div id="formtable_harga">
    <h5>Tabel List Harga</h5>
    <div class="row mb-3">
        <div class="col-md-3 mt-2">
            <input type="text" id="searchBoxHarga" class="form-control" placeholder="Search">
        </div>
        <div class="col-md-3 mt-2">
            <button id="filterBtnHarga" class="btn btn-primary">Filter</button>
        </div>
    </div>
    <div class="table-responsive">
        <table id="listHargaTable" class="display table table-bordered mb-2">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Stok</th>
                    <th>Nama Barang</th>
                    <th>Satuan</th>
                    <th>Isi</th>
                    <th>Harga</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data akan diisi oleh DataTables -->
            </tbody>
        </table>
    </div>
</div>
<script>
$(document).ready(function(){
    transaksi_table_harga_field();
    function transaksi_table_harga_field() {
        if ($.fn.dataTable.isDataTable('#listHargaTable')) {
            $('#listHargaTable').DataTable().clear().destroy();
        }
        table = $('#listHargaTable').DataTable({
            ajax: {
                url: '{{ route("filter_list_harga") }}',
                data: function(d) {
                    d.searchText = $('#searchBoxHarga').val();
                },
                dataSrc: function(json) {
                    console.log('Server Response:', json);
                    return json.data;
                }
            },
            columns:[
                { data: 'no', name: 'no' },
                { data: 'kd_stok', name: 'kd_stok' },
                { data: 'NAMA_BRG', name: 'NAMA_BRG' },
                { data: 'satuan', name: 'satuan' },
                {
                data: 'isi',
                name: 'isi',
                    render: function(data, type, row) {
                        // Format angka: hapus .000 di belakang dan tambahkan titik ribuan
                        let number = parseFloat(data).toFixed(3); // Pastikan 3 digit di belakang koma
                        number = number.replace('.000', ''); // Hapus .000 di belakang
                        number = number.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Tambahkan titik ribuan
                        return number;
                    }
                },
                {
                data: 'hj1', // Kolom hj1
                name: 'HJ1',
                render: function(data, type, row) {
                    if (type === 'display' || type === 'filter') {
                        return data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    }
                    return data; // Kembalikan data asli untuk sorting atau operasi lain
                }
                }
            ],
            processing: true, // mengaktifkan fitur datatbles search
            serverSide: true, // mengaktifkan fitur datatbles search
            searching: false,
            paging: true,
            info: false,
            scrollY: '100vh',
            scrollCollapse: true,
            scrollX: true,
            fixedHeader: {
                header: true,
                footer: false
            }
        });
            $('#filterBtnHarga').on('click', function() {
                table.ajax.reload();
            });
    }
});
</script>
