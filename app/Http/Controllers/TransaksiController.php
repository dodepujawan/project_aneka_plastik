<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transactions;
use App\Models\Transusers;
use App\Models\Faktur;
use App\Models\FakturUser;
use App\Models\Cabang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index(){
        return view('transaksi.index_transaksi');
    }

    // public function get_barangs(Request $request){
    //     $search = $request->input('q'); // Ambil query pencarian
    //     $query = DB::table('mbarang as a')
    //                 ->select(
    //                     'a.id',
    //                     'a.KD_STOK as kd_barang',
    //                     'a.NAMA_BRG as nama_barang',
    //                     'a.SATUAN as satuan',
    //                     'a.HJ1 as harga',
    //                     DB::raw("CAST(SUBSTRING_INDEX(b.isi, ',', 1) AS UNSIGNED) as q_unit"),
    //                     DB::raw("CAST(SUBSTRING_INDEX(a.STOKUNIT, ',', 1) AS UNSIGNED) as stok")
    //                 )
    //                 ->leftJoin('mharga as b', 'a.KD_STOK', '=', 'b.kd_stok');
    //     if ($search) {
    //         $query->where('a.KD_STOK', 'LIKE', "%{$search}%")
    //             ->orWhere('a.NAMA_BRG', 'LIKE', "%{$search}%");
    //     }
    //     $barangs = $query->get(); // Eksekusi query
    //     return response()->json($barangs);
    // }

    public function get_users(Request $request){
        $search = $request->input('q'); // Ambil query pencarian
        $query = DB::table('mcustomer as a')
                    ->select(
                        'id',
                        'CUSTOMER as kd_customer',
                        'NAMACUST as nama_cust',
                    );
        if ($search) {
            $query->where('CUSTOMER', 'LIKE', "%{$search}%")
                ->orWhere('NAMACUST', 'LIKE', "%{$search}%");
        }
        $users = $query->get(); // Eksekusi query
        return response()->json($users);
    }

    public function generate_kode_po(Request $request){
        $user = auth()->user();

        // Ambil langsung user_id (misal AD0001 / ST0001)
        $userCode = $user->user_id;

        // Bulan dan tahun (MMYY)
        $monthYear = now()->format('my'); // contoh: 0825

        // Cari kode terakhir untuk user + bulan ini
        $lastOrder = Transusers::where('no_invoice', 'like', "PO-{$userCode}{$monthYear}%")
            ->latest('id')
            ->first();

        if ($lastOrder) {
            $lastNumber = (int) substr($lastOrder->no_invoice, -5);
            $nextNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '00001';
        }

        // Format akhir: PO-AD0001082500002
        $kodePo = "PO-{$userCode}{$monthYear}{$nextNumber}";

        return response()->json([
            'kode_po' => $kodePo
        ]);
    }

    public function get_kode_gudang(Request $request){
        $gudangs = Cabang::select('cabang_id', 'lokal_id', 'nama')->get();
        return response()->json($gudangs);
    }

    public function get_barangs(Request $request){
        $search = $request->input('q'); // Ambil query pencarian
        $query = DB::table('mbarang as a')
                    ->select(
                        'id',
                        'KD_STOK as kd_barang',
                        'NAMA_BRG as nama_barang',
                    );
        if ($search) {
            $query->where('KD_STOK', 'LIKE', "%{$search}%")
                ->orWhere('NAMA_BRG', 'LIKE', "%{$search}%");
        }
        $barangs = $query->get(); // Eksekusi query
        return response()->json($barangs);
    }

    public function save_products(Request $request){
        $invoiceNumber = $request->input('nomor_po');
        $kode_user     = $request->input('kode_user');
        $rcabang       = $request->input('select_gudang');
        $product       = $request->input('product');

        $user_id   = Auth::user()->user_id;
        $user_name = Auth::user()->name;

        DB::beginTransaction();

        try {
            // 1️⃣ Buat Transusers hanya kalau belum ada
            $transUser = Transusers::where('no_invoice', $invoiceNumber)->first();

            if (!$transUser) {
                Transusers::create([
                    'no_invoice' => $invoiceNumber,
                    'user_id'    => $user_id,
                    'nama_cust'  => $user_name,
                    'user_kode'  => $kode_user,
                ]);
            }

            // 2️⃣ Ambil nilai dari frontend (sudah dihitung di JS)
            $harga      = floatval($product['harga']);
            $jumlah     = floatval($product['jumlah']);
            $diskon     = floatval($product['diskon']);
            $diskon_rp  = floatval($product['diskon_rp']);
            $total_net  = floatval($product['total']);
            $ppn        = floatval($product['ppn_trans']);

            // 3️⃣ Hitung nilai turunan ringan (kalau perlu disimpan)
            $diskon_rupiah = ($diskon / 100) * $harga;
            $dpp        = round($total_net / (1 + ($ppn / 100)), 2);
            $ppn_rp     = $total_net - $dpp;

            // 4️⃣ Simpan transaksi
            $newTransaction = Transactions::create([
                'no_invoice'    => $invoiceNumber,
                'kd_brg'        => $product['kd_barang'],
                'nama_brg'      => $product['nama'],
                'harga'         => $harga,
                'qty_order'     => $jumlah,
                'qty_unit'      => $product['unit'],
                'satuan'        => $product['satuan'],
                'disc'          => $diskon,
                'rdisc'         => $diskon_rupiah,
                'ndisc'         => $diskon_rp,
                'ttldisc'       => ($diskon_rupiah + $diskon_rp) * $jumlah,
                'ppn'           => $ppn,
                'rppn'          => $ppn_rp,
                'dpp'           => $dpp,
                'ttl_gross'     => $harga * $jumlah,
                'total'         => $total_net,
                'rcabang'       => $rcabang,
                'status_po'     => 0,
                'status_faktur' => 0,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Product saved successfully!',
                'invoice_number' => $invoiceNumber,
                'id' => $newTransaction->id,
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Failed to save product: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update_products(Request $request){
        $productData = $request->input('product');
        $id = $request->input('id'); // id row dari tabel transactions

        try {
            $trans = Transactions::findOrFail($id);

            // Ambil data lama sebagai referensi
            $harga = floatval($trans->harga);
            $jumlah = floatval($productData['jumlah']);
            $diskon = floatval($productData['diskon']);
            $diskon_rp_input = floatval($productData['diskon_rp']);
            $ppn = floatval($productData['ppn_trans']);

            // Hitung ulang semua nilai turunan
            $diskon_rupiah = ($diskon / 100) * $harga;
            $harga_setelah_diskon = $harga - $diskon_rupiah - $diskon_rp_input;
            $total_net = $harga_setelah_diskon * $jumlah;

            // PPN & DPP
            $dpp = round($total_net / (1 + ($ppn / 100)), 2);
            $ppn_rp = $total_net - $dpp;

            // Update hanya field yang boleh berubah
            $trans->update([
                'qty_order' => $jumlah,
                'disc' => $diskon,
                'rdisc' => $diskon_rupiah,
                'ndisc' => $diskon_rp_input,
                'ttldisc' => ($diskon_rupiah + $diskon_rp_input) * $jumlah,
                'ppn' => $ppn,
                'rppn' => $ppn_rp,
                'dpp' => $dpp,
                'ttl_gross' => $harga * $jumlah,
                'total' => $total_net,
            ]);

            return response()->json([
                'message' => 'Produk berhasil diupdate!',
                'recalculated' => [
                    'total' => $total_net,
                    'ppn_rp' => $ppn_rp,
                    'dpp' => $dpp,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal update produk: ' . $e->getMessage()
            ], 500);
        }
    }

    public function delete_product_baris(Request $request){
        $id = $request->id;
        $item = Transactions::find($id);

        if (!$item) {
            return response()->json(['message' => 'Item tidak ditemukan.'], 404);
        }

        $nomorPo = $item->no_invoice;

        // Hapus baris item
        $item->delete();

        // Cek apakah masih ada item dengan nomor PO ini
        $sisaItem = Transactions::where('no_invoice', $nomorPo)->count();

        if ($sisaItem === 0) {
            // Hapus header transusers juga
            Transusers::where('no_invoice', $nomorPo)->delete();
            return response()->json([
                'message' => 'Semua item dihapus. Data transaksi juga dihapus.'
            ]);
        }

        return response()->json([
            'message' => 'Item berhasil dihapus.'
        ]);
    }

    public function get_barang_satuan(Request $request){
        $kd_barang = $request->input('kd_barang');
        $kodeGudang = $request->input('kode_gudang');

        if ($kodeGudang === 'GUD001') {
            $harga = 'mharga';
            $barang = 'mbarang';
        } elseif ($kodeGudang === 'GUD002') {
            $harga = 'mharga_gudang';
            $barang = 'mbarang_gudang';
        } else {
            // default fallback (optional)
            $harga = 'mharga';
            $barang = 'mbarang';
        }

        $data = DB::table($harga . ' as a')
            ->leftJoin($barang . ' as b', 'b.KD_STOK', '=', 'a.kd_stok')
            ->select(
                'a.kd_stok',
                'a.satuan',
                'a.hj1',
                'a.isi',
                'b.NAMA_BRG',
                DB::raw('b.STOKUNIT as stok_gudang')
            )
            ->where('a.kd_stok', $kd_barang)
            ->orderBy('a.isi', 'ASC')
            ->get();

        return response()->json($data);
    }

    public function get_barang_selected(Request $request){
        $kd_barang = $request->input('kd_barang');
        $satuan_barang = $request->input('satuan_barang');
        $kodeGudang = $request->input('kode_gudang');

        if ($kodeGudang === 'GUD001') {
            $harga = 'mharga';
            $barang = 'mbarang';
        } elseif ($kodeGudang === 'GUD002') {
            $harga = 'mharga_gudang';
            $barang = 'mbarang_gudang';
        } else {
            // default fallback (optional)
            $harga = 'mharga';
            $barang = 'mbarang';
        }

        $data = DB::table($harga . ' as a')
            ->leftJoin($barang . ' as b', 'b.KD_STOK', '=', 'a.kd_stok')
            // ->select('a.kd_stok', 'a.satuan', 'a.hj1', 'a.isi', 'b.NAMA_BRG', DB::raw("b.`$kodeGudang` as stok_gudang"))
            ->select('a.kd_stok', 'a.satuan', 'a.hj1', 'a.isi', 'b.NAMA_BRG', 'b.STOKUNIT as stok_gudang')
            ->where('a.kd_stok', $kd_barang)
            ->where('a.satuan', $satuan_barang)
            ->first();

        return response()->json($data);
    }

    // ### Membuka Halaman Edit Transaksi
    public function index_edit(){
        return view('transaksi.transaksi_edit');
    }

    public function get_edit_transaksi_data(Request $request){
        $userKode = Auth::user()->user_kode;

        $start = $request->start ?? 0;
        $length = $request->length ?? 10;
        $searchValue = $request->search['value'] ?? '';

        // Base query untuk ambil data
        $query = DB::table('po_userby as a')
            ->leftJoin('po_online as b', 'a.no_invoice', '=', 'b.no_invoice')
            ->select(
                'a.no_invoice',
                DB::raw('DATE(a.created_at) as created_at'),
                DB::raw('SUM(b.total) as total')
            )
            ->where('a.user_kode', $userKode)
            ->where('b.status_po', 0)
            ->where('b.status_faktur', 0)
            ->groupBy('a.no_invoice', 'a.created_at');

        // ==============================
        // Total records tanpa filter
        $totalRecords = DB::table('po_userby as a')
            ->leftJoin('po_online as b', 'a.no_invoice', '=', 'b.no_invoice')
            ->where('a.user_kode', $userKode)
            ->where('b.status_po', 0)
            ->where('b.status_faktur', 0)
            ->distinct('a.no_invoice')
            ->count('a.no_invoice');

        // ==============================
        // Filter search
        if (!empty($searchValue)) {
            $query->having(function ($q) use ($searchValue) {
                $q->orHaving('a.no_invoice', 'like', "%$searchValue%")
                ->orHaving(DB::raw('DATE(a.created_at)'), 'like', "%$searchValue%");
            });
        }

        // ==============================
        // Total records setelah filter
        $recordsFiltered = $query->get()->count();

        // ==============================
        // Ambil data dengan pagination
        $data = $query->orderBy('a.created_at', 'desc')
                    ->skip($start)
                    ->take($length)
                    ->get();

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    public function get_edit_transaksi_data_admin(Request $request){
        $user = Auth::user();

        $columns = [
            0 => 'a.id',
            1 => 'a.no_invoice',
            2 => 'a.user_kode',
            3 => 'c.NAMACUST',
            4 => 'a.created_at',
            5 => 'total'
        ];

        $start = $request->start ?? 0;
        $length = $request->length ?? 10;
        $orderColumn = $columns[$request->order[0]['column']] ?? 'a.created_at';
        $orderDir = $request->order[0]['dir'] ?? 'desc';
        $searchValue = $request->search['value'] ?? null;

        // Base query
        $query = DB::table('po_userby as a')
            ->leftJoin('po_online as b', 'a.no_invoice', '=', 'b.no_invoice')
            ->leftJoin('mcustomer as c', 'a.user_kode', '=', 'c.CUSTOMER')
            ->select(
                'a.id',
                'a.no_invoice',
                'a.user_id',
                'a.user_kode',
                'c.NAMACUST as nama_cust',
                DB::raw('DATE(a.created_at) as created_at'),
                DB::raw('SUM(b.total) as total')
            )
            ->where('b.status_po', 0)
            ->where('b.status_faktur', 0)
            ->groupBy('a.id','a.no_invoice', 'a.created_at', 'a.user_kode', 'c.NAMACUST', 'a.user_id');

        if ($user->roles != 'admin') {
            $query->where('a.user_id', $user->user_id);
        }

        // ==============================
        // Total records tanpa filter
        $totalRecords = DB::table('po_userby as a')
            ->leftJoin('po_online as b', 'a.no_invoice', '=', 'b.no_invoice')
            ->where('b.status_po', 0)
            ->where('b.status_faktur', 0)
            ->when($user->roles != 'admin', function($q) use ($user) {
                $q->where('a.user_id', $user->user_id);
            })
            ->distinct('a.id')
            ->count('a.id');

        // ==============================
        // Filter search
        if ($searchValue) {
            $query->having(function($q) use ($searchValue) {
                $q->orHaving('a.no_invoice', 'like', "%$searchValue%")
                ->orHaving('a.user_kode', 'like', "%$searchValue%")
                ->orHaving('nama_cust', 'like', "%$searchValue%");
            });
        }

        // ==============================
        // Total records setelah filter
        $recordsFiltered = $query->get()->count();

        // ==============================
        // Ambil data untuk page sekarang
        $data = $query->orderBy($orderColumn, $orderDir)
                    ->skip($start)
                    ->take($length)
                    ->get();

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    public function filter_no_po(Request $request){
        if (!Auth::check()) {
            return response()->json(['message' => 'Silakan login terlebih dahulu'], 401);
        }

        $query = DB::table('po_userby as a')
            ->join('po_online as b', function($join) {
                $join->on(DB::raw('CONVERT(a.no_invoice USING utf8mb4) COLLATE utf8mb4_general_ci'),
                        '=',
                        DB::raw('CONVERT(b.no_invoice USING utf8mb4) COLLATE utf8mb4_general_ci'));
            })
            ->leftJoin('mcustomer as c', function($join) {
                $join->on(DB::raw('CONVERT(a.user_kode USING latin1) COLLATE latin1_general_ci'),
                        '=',
                        DB::raw('CONVERT(c.CUSTOMER USING latin1) COLLATE latin1_general_ci'));
            })
            ->leftJoin('users as d', function($join) {
                $join->on(DB::raw('CONVERT(a.user_id USING utf8mb4) COLLATE utf8mb4_general_ci'),
                        '=',
                        DB::raw('CONVERT(d.user_id USING utf8mb4) COLLATE utf8mb4_general_ci'));
            })
            ->select(
                'a.no_invoice',
                DB::raw('DATE(a.created_at) as created_at'),
                'a.user_id',
                'a.user_kode',
                DB::raw('c.NAMACUST as nama_cust'),
                'b.history_inv',
                'd.name',
                DB::raw('SUM(b.total) as total')
            )
            ->groupBy(
                'a.no_invoice',
                'a.created_at',
                'a.user_id',
                'a.user_kode',
                'c.NAMACUST',
                'b.history_inv',
                'd.name'
            );

        // Filter role sebelum DataTables
        $user = Auth::user();
        if ($user->roles === 'customer') {
            $query->where('a.user_kode', $user->user_kode);
        } elseif ($user->roles === 'staff') {
            $query->where('a.user_id', $user->user_id);
        }

        // Kirim langsung builder ke DataTables
        return DataTables::of($query)
            ->filter(function ($query) use ($request, $user) {
                if ($request->startDate) {
                    $query->whereDate('a.created_at', '>=', $request->startDate);
                }
                if ($request->endDate) {
                    $query->whereDate('a.created_at', '<=', $request->endDate);
                }
                if ($request->searchText) {
                    $search = $request->searchText;
                    $query->where(function ($q) use ($search, $user) {
                        $q->where('a.no_invoice', 'like', "%$search%")
                        ->orWhere('b.history_inv', 'like', "%$search%");
                        if (in_array($user->roles, ['staff', 'admin'])) {
                            $q->orWhere('a.user_kode', 'like', "%$search%")
                            ->orWhere('a.user_id', 'like', "%$search%")
                            ->orWhere('c.NAMACUST', 'like', "%$search%")
                            ->orWhere('d.name', 'like', "%$search%");
                        }
                    });
                }
            })
            ->addIndexColumn() // otomatis tambahkan kolom nomor urut DT_RowIndex
            ->make(true);
    }

    public function get_edit_transaksi_to_table(Request $request){
        $no_invoice = $request->no_invoice;

        $data = DB::table('po_userby as a')
        ->leftJoin('po_online as b', 'a.no_invoice', '=', 'b.no_invoice')
        ->leftJoin('mcustomer as c', 'a.user_kode', '=', 'c.CUSTOMER')
        ->select(
            'a.no_invoice',
            'a.created_at',
            'a.user_kode',
            'b.id',
            'b.kd_brg',
            'b.nama_brg',
            'c.NAMACUST as nama_cust',
            DB::raw('CAST(b.qty_order AS UNSIGNED) AS qty_order'),
            DB::raw('CAST(b.qty_unit AS UNSIGNED) AS qty_unit'),
            'b.satuan',
            DB::raw('CAST(b.harga AS UNSIGNED) AS harga'),
            DB::raw('CAST(b.disc AS UNSIGNED) AS disc'),
            DB::raw('CAST(b.ndisc AS UNSIGNED) AS ndisc'),
            DB::raw('CAST(b.ppn AS UNSIGNED) AS ppn'),
            DB::raw('CAST(b.rppn AS UNSIGNED) AS rppn'),
            DB::raw('CAST(b.dpp AS UNSIGNED) AS dpp'),
            DB::raw('CAST(b.total AS UNSIGNED) AS total'),
            'b.rcabang'
        )
        // ->where('a.user_id', Auth::user()->user_id)
        ->where('b.status_po', 0)
        ->where('a.no_invoice', $no_invoice)
        ->get();

        return response()->json(['data' => $data]);
    }

    public function delete_products(Request $request){
        // Validasi input
        $request->validate([
            'value_invo' => 'required|string'
        ]);

        $roles_user = Auth::user()->roles;
        $user_kode = Auth::user()->user_kode;
        $noInvoice = $request->input('value_invo'); // Ambil no_invoice dari request

        if ($roles_user == 'customer') {
            // Periksa apakah Transusers memiliki nilai sesuai kondisi
            $statusRolesCheck = Transusers::where('no_invoice', $noInvoice)
                ->where('user_kode', $user_kode)
                ->exists();

            if (!$statusRolesCheck) {
                return response()->json([
                    'error' => 'Anda tidak dapat menghapus produk karena status_po Anda tidak memenuhi syarat, Tolong Refresh.'], 403);
            }
        }

        // Memastikan Semua Nilai status_po = 0
        $statusPoCheck = Transactions::where('no_invoice', $noInvoice)
                                  ->where('status_po', '!=', 0)
                                  ->exists();
        if ($statusPoCheck) {
            return response()->json(['error' => 'Tidak dapat melanjutkan, terdapat transaksi dengan status_po selain 0'], 400);
        }

        // Mulai transaksi
        DB::beginTransaction();

        try {
            // Periksa apakah data ada di Transactions
            $exists = Transactions::where('no_invoice', $noInvoice)->exists();

            if (!$exists) {
                return response()->json(['error' => 'Invoice not found'], 404);
            }

            // Hapus data transaksi dan transuser berdasarkan no_invoice
            Transusers::where('no_invoice', $noInvoice)->delete();
            Transactions::where('no_invoice', $noInvoice)->delete();

            // Commit transaksi jika berhasil
            DB::commit();

            return response()->json(['message' => 'Products deleted successfully!'], 200);
        } catch (\Exception $e) {
            // Rollback transaksi jika ada error
            DB::rollBack();

            // Kembalikan pesan error
            return response()->json(['error' => 'Failed to delete products: ' . $e->getMessage()], 500);
        }
    }

    public function save_faktur(Request $request){
        $invoiceNumber = $request->input('invoice_number');
        $method = $request->input('method');
        $namaBank = $request->input('nama_bank');
        $jumlahBayar = $request->input('jumlah_bayar');
        $jumlahKembalian = $request->input('jumlah_kembalian');

        DB::beginTransaction();
        try {
            // Ambil data transaksi dari no_invoice
            $transaksiList = Transactions::where('no_invoice', $invoiceNumber)->get();

            if ($transaksiList->isEmpty()) {
                return response()->json(['error' => 'Transaksi tidak ditemukan'], 404);
            }

            // Buat nomor faktur (format: FAK-<mY><5digit>)
            $currentMonthYear = Carbon::now()->format('m') . substr(Carbon::now()->format('Y'), 2, 2);
            $lastFaktur = Faktur::where('no_faktur', 'like', '%'.$currentMonthYear.'%')
                                ->orderBy('no_faktur', 'desc')
                                ->lockForUpdate()
                                ->first();

            $nextFakturNumber = $lastFaktur ? (int) substr($lastFaktur->no_faktur, -5) + 1 : 1;
            $fakturNumber = 'FAK-' . $currentMonthYear . str_pad($nextFakturNumber, 5, '0', STR_PAD_LEFT);

            // Simpan ke tabel faktur_online
            foreach ($transaksiList as $t) {
                Faktur::create([
                    'no_faktur' => $fakturNumber,
                    'kd_brg' => $t->kd_brg,
                    'nama_brg' => $t->nama_brg,
                    'qty_order' => $t->qty_order,
                    'qty_unit' => $t->qty_unit,
                    'satuan' => $t->satuan,
                    'harga' => $t->harga,
                    'ttl_gross' => $t->ttl_gross,
                    'ppn' => $t->ppn,
                    'rppn' => $t->rppn,
                    'dpp' => $t->dpp,
                    'disc' => $t->disc,
                    'rdisc' => $t->rdisc,
                    'ndisc' => $t->ndisc,
                    'ttldisc' => $t->ttldisc,
                    'total' => $t->total,
                    'rcabang' => $t->rcabang,
                    'history_inv' => $invoiceNumber,
                ]);
            }

            // Simpan ke tabel faktur_userby
            $transUser = Transusers::where('no_invoice', $invoiceNumber)->first();
            if ($transUser) {
                FakturUser::create([
                    'no_faktur' => $fakturNumber,
                    'user_id' => $transUser->user_id,
                    'nama_cust' => $transUser->nama_cust,
                    'user_kode' => $transUser->user_kode,
                    'no_invoice' => $invoiceNumber,
                    'pembayaran' => $method,
                    'nominal_bayar' => $jumlahBayar,
                    'kembalian' => $jumlahKembalian,
                    'nama_bank' => $namaBank,
                ]);
            }

            // Update status_faktur di transactions
            Transactions::where('no_invoice', $invoiceNumber)->update([
                'status_faktur' => 1
            ]);

            DB::commit();

            // Ambil ulang data untuk struk
            $items = Faktur::where('no_faktur', $fakturNumber)->get();
            $sales = User::where('user_id', $transUser->user_id)->pluck('name')->first();
            $customer = User::where('user_kode', $transUser->user_kode)->pluck('name')->first();
            // $customer = User::whereRaw("user_kode = ?", [$kodeUser])->pluck('name')->first();

            // Mulai string ESC/POS
            $esc = "\x1B@\n"; // Initialize printer

            // Header toko
            $esc .= "COPY 1\n";
            $esc .= "TOKO ANEKA PLASTIK\n";
            $esc .= "Jl. Hasanuddin No.51 Singaraja\n";
            $esc .= "-----------------------------\n";
            $esc .= "No Faktur : {$fakturNumber}\n";
            $esc .= "Tanggal   : " . $items->first()->created_at->format('d-m-Y H:i') . "\n";
            // function cutting name
            $maxWidth = 32;
            function wrapLabel($label, $text, $maxWidth) {
                $labelLength = strlen($label);
                $lines = [];
                // Split teks menjadi kata-kata
                $words = explode(' ', $text);
                $currentLine = '';
                foreach ($words as $word) {
                    // Cek jika menambah kata ini melebihi batas
                    if (strlen($currentLine . ' ' . $word) > $maxWidth - $labelLength) {
                        // Baris penuh, simpan dulu
                        $lines[] = $currentLine;
                        $currentLine = $word;
                    } else {
                        // Tambahkan kata ke baris
                        $currentLine = ($currentLine === '' ? $word : $currentLine . ' ' . $word);
                    }
                }
                // Masukkan baris terakhir
                if ($currentLine !== '') {
                    $lines[] = $currentLine;
                }
                // Gabungkan semua baris dengan indent untuk baris kedua dst
                $result = $label . array_shift($lines) . "\n"; // baris pertama dengan label
                foreach ($lines as $line) {
                    $result .= str_repeat(' ', $labelLength) . $line . "\n"; // baris selanjutnya rata kiri dengan teks label
                }
                return $result;
            }
            $esc .= wrapLabel("Sales : ", $sales, $maxWidth);
            $esc .= wrapLabel("Customer : ", $customer, $maxWidth);
            $esc .= "-----------------------------\n";

            // Detail barang
            foreach ($items as $i) {
                $nama  = substr($i->nama_brg, 0, 32);
                $qty   = $i->qty_order;
                $disc  = $i->disc;
                $ndisc = $i->ndisc;
                $harga = $i->harga;
                $total = $i->total;

                // Baris 1: Nama barang
                $esc .= $nama . "\n";

                // Format harga x qty
                $hargaQty = number_format($harga, 0, ',', '.') . " x " . number_format($qty, 0, ',', '.');

                // Diskon text (kalau ada)
                $diskonText = '';
                if ($disc > 0) {
                    $diskonText .= '-' . number_format($disc, 0, ',', '.') . '% ';
                }
                if ($ndisc > 0) {
                    $totalNdisc = $ndisc * $qty;
                    $diskonText .= '-' . number_format($totalNdisc, 0, ',', '.') . ' ';
                }

                if ($diskonText !== '') {
                    // Ada diskon → harga x qty di baris 2, diskon + total di baris 3
                    $esc .= $hargaQty . "\n";
                    $esc .= sprintf("%-15s %15s\n", $diskonText, number_format($total, 0, ',', '.'));
                } else {
                    // Tidak ada diskon → harga x qty + total sejajar di baris 2
                    $esc .= sprintf("%-20s %10s\n", $hargaQty, number_format($total, 0, ',', '.'));
                }
            }

            $esc .= "-----------------------------\n";

            // Grand Total, DPP, PPN rata kanan
            $grandTotal = $items->sum('total');
            $dpp = $items->sum('dpp');
            $ppn = $items->sum('rppn');

            $esc .= sprintf("%30s\n", "Grand Total: " . number_format($grandTotal, 0, ',', '.'));
            $esc .= sprintf("%30s\n", "DPP: " . number_format($dpp, 0, ',', '.'));
            $esc .= sprintf("%30s\n", "PPN: " . number_format($ppn, 0, ',', '.'));

            $esc .= "-----------------------------\n";
            // Tambahkan info pembayaran
            if ($method === 'cash') {
                $esc .= sprintf("%30s\n", "Bayar  : " . number_format($jumlahBayar, 0, ',', '.'));
                $esc .= sprintf("%30s\n", "Kembali  : " . number_format($jumlahKembalian, 0, ',', '.'));
                $esc .= sprintf("%30s\n", "Metode : Cash");
            } elseif ($method === 'transfer') {
                $esc .= sprintf("%30s\n", "Bayar  : " . number_format($grandTotal, 0, ',', '.'));
                $esc .= sprintf("%30s\n", "Kembali  : 0");
                $esc .= sprintf("%30s\n", "Metode : Transfer");
            } else { // bon
                $esc .= sprintf("%30s\n", "Bayar  : 0");
                $esc .= sprintf("%30s\n", "Kembali  : 0");
                $esc .= sprintf("%30s\n", "Metode : Bon");
            }

            $esc .= "Terima kasih\n\n";

            // Potong kertas (kalau printer support)
            // $esc .= "\x1D\x56\x41";

            // ### COPY 2 ###
            $esc2 = "\x1B@\n"; // Initialize printer
            $esc2 .= "COPY 2\n";
            $esc2 .= "TOKO ANEKA PLASTIK\n";
            $esc2 .= "Jl. Hasanuddin No.51 Singaraja\n";
            $esc2 .= "-----------------------------\n";
            $esc2 .= "No Faktur : {$fakturNumber}\n";
            $esc2 .= "Tanggal   : " . $items->first()->created_at->format('d-m-Y H:i') . "\n";
            $esc2 .= wrapLabel("Sales : ", $sales, $maxWidth);
            $esc2 .= wrapLabel("Customer : ", $customer, $maxWidth);
            $esc2 .= "-----------------------------\n";

            // Detail barang
            foreach ($items as $i) {
                $nama  = substr($i->nama_brg, 0, 32);
                $qty   = $i->qty_order;
                $disc  = $i->disc;
                $ndisc = $i->ndisc;
                $harga = $i->harga;
                $total = $i->total;

                // Baris 1: Nama barang
                $esc2 .= $nama . "\n";

                // Format harga x qty
                $hargaQty = number_format($harga, 0, ',', '.') . " x " . number_format($qty, 0, ',', '.');

                // Diskon text (kalau ada)
                $diskonText = '';
                if ($disc > 0) {
                    $diskonText .= '-' . number_format($disc, 0, ',', '.') . '% ';
                }
                if ($ndisc > 0) {
                    $totalNdisc = $ndisc * $qty;
                    $diskonText .= '-' . number_format($totalNdisc, 0, ',', '.') . ' ';
                }

                if ($diskonText !== '') {
                    // Ada diskon → harga x qty di baris 2, diskon + total di baris 3
                    $esc2 .= $hargaQty . "\n";
                    $esc2 .= sprintf("%-15s %15s\n", $diskonText, number_format($total, 0, ',', '.'));
                } else {
                    // Tidak ada diskon → harga x qty + total sejajar di baris 2
                    $esc2 .= sprintf("%-20s %10s\n", $hargaQty, number_format($total, 0, ',', '.'));
                }
            }

            $esc2 .= "-----------------------------\n";

            // Grand Total, DPP, PPN rata kanan
            $grandTotal = $items->sum('total');
            $dpp = $items->sum('dpp');
            $ppn = $items->sum('rppn');

            $esc2 .= sprintf("%30s\n", "Grand Total: " . number_format($grandTotal, 0, ',', '.'));
            $esc2 .= sprintf("%30s\n", "DPP: " . number_format($dpp, 0, ',', '.'));
            $esc2 .= sprintf("%30s\n", "PPN: " . number_format($ppn, 0, ',', '.'));

            $esc2 .= "-----------------------------\n";
            // Tambahkan info pembayaran
            if ($method === 'cash') {
                $esc2 .= sprintf("%30s\n", "Bayar  : " . number_format($jumlahBayar, 0, ',', '.'));
                $esc2 .= sprintf("%30s\n", "Kembali  : " . number_format($jumlahKembalian, 0, ',', '.'));
                $esc2 .= sprintf("%30s\n", "Metode : Cash");
            } elseif ($method === 'transfer') {
                $esc2 .= sprintf("%30s\n", "Bayar  : " . number_format($grandTotal, 0, ',', '.'));
                $esc2 .= sprintf("%30s\n", "Kembali  : 0");
                $esc2 .= sprintf("%30s\n", "Metode : Transfer");
            } else { // bon
                $esc2 .= sprintf("%30s\n", "Bayar  : 0");
                $esc2 .= sprintf("%30s\n", "Kembali  : 0");
                $esc2 .= sprintf("%30s\n", "Metode : Bon");
            }

            $esc2 .= "Terima kasih\n\n";

            // Potong kertas (kalau printer support)
            // $esc .= "\x1D\x56\x41";

            // Encode ke base64 supaya RawBT bisa baca
            $base64 = base64_encode($esc);
            $base65 = base64_encode($esc2);

            return response()->json([
                'message' => 'Faktur berhasil dibuat',
                'no_faktur' => $fakturNumber,
                'struk_text' => $base64,
                'struk_text2' => $base65,
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    // ### Halaman Transaksi Approved
    public function approved(){
        return view('transaksi.approved_transaksi');
    }

    public function filter_approved_invoice(Request $request){
        if (!Auth::check()) {
            return response()->json(['message' => 'Silakan login terlebih dahulu'], 401);
        }

        $query = DB::table('po_userby as a')
            ->leftJoin('po_online as b', 'a.no_invoice', '=', 'b.no_invoice')
            ->select(
                'a.no_invoice',
                DB::raw('DATE(a.created_at) as created_at'),
                'a.user_id',
                'a.user_kode',
                DB::raw('SUM(b.total) as total')
            )
            // ->where('a.user_id', Auth::user()->user_id)
            // ->where('a.user_kode', Auth::user()->user_kode)
            ->where('b.status_po', '=', 1)
            ->groupBy('a.no_invoice', 'a.created_at', 'a.user_id', 'a.user_kode')
            ->orderBy('a.created_at', 'desc');

            $userRole = Auth::user()->roles;
            if ($userRole === 'customer') {
                $query->where('a.user_kode', Auth::user()->user_kode);
            } elseif ($userRole === 'staff') {
                $query->where('a.user_id', Auth::user()->user_id);
            } elseif ($userRole === 'admin') {
                // Tidak ada filter tambahan untuk admin, karena admin bisa melihat semua data
            } else {
                return response()->json(['message' => 'Anda tidak memiliki izin untuk mengakses data ini'], 403);
            }

            if ($request->has('startDate') && $request->startDate) {
                $query->where('a.created_at', '>=', $request->startDate);
            }

            if ($request->has('endDate') && $request->endDate) {
                $query->where('a.created_at', '<=', $request->endDate);
            }

            // if ($request->has('searchText') && $request->searchText) {
            //     $query->where(function($q) use ($request) {
            //         $q->where('a.no_invoice', 'like', '%' . $request->searchText . '%');
            //         $userRole = Auth::user()->roles;
            //         if ($userRole === 'staff') {
            //             $q->where('a.user_kode', Auth::user()->user_kode);
            //         } elseif ($userRole === 'admin') {
            //             $q->where('a.user_kode', Auth::user()->user_kode)
            //             ->orWhere('a.user_id', Auth::user()->user_id);
            //         }
            //     });
            // }
            if ($request->has('searchText') && $request->searchText) {
                $query->where(function($q) use ($request) {
                    $userRole = Auth::user()->roles;
                    $searchText = $request->searchText;

                    // Cari berdasarkan no_invoice
                    $q->where('a.no_invoice', 'like', '%' . $searchText . '%');

                    // Tambahkan filter berdasarkan role
                    if ($userRole === 'staff') {
                        // Staff hanya bisa melihat data dengan user_kode mereka
                        // $q->where('a.user_kode', Auth::user()->user_kode);
                        $q->orWhere('a.user_kode', 'like', '%' . $searchText . '%');
                    } elseif ($userRole === 'admin') {
                        // Admin bisa melihat data dengan user_kode atau user_id mereka
                        $q->orWhere('a.user_kode', 'like', '%' . $searchText . '%')
                          ->orWhere('a.user_id', 'like', '%' . $searchText . '%');
                    }
                });
            }

        $order = $query->get();

        return response()->json([
            'data' => $order
        ]);
    }

    public function get_po_approved_det(Request $request){
        $no_invoice = $request->input('no_invoice');
        $query = DB::table('po_online')
            ->select([
                'no_invoice',
                'kd_brg',
                'nama_brg',
                'harga',
                'qty_unit',
                'satuan',
                'qty_order',
                'qty_sup',
                'disc',
                'ndisc',
                'ndisc',
                'ppn',
                'total'
            ])
            ->where('status_po', 1)
            ->where('no_invoice', $no_invoice)
            ->get();

        $grandTotal = $query->sum('total');

        return response()->json([
            'data' => $query,
            'grand_total' => $grandTotal
        ]);
    }

    public function save_products_approved(Request $request){
        // Ambil data produk dari request
        $products = $request->input('products');
        $no_po_approve = $request->input('no_po_approve');

        // Ambil bulan dan tahun saat ini, format 'mY' misalnya '0225' untuk Februari 2025
        $currentMonthYear = Carbon::now()->format('m') . substr(Carbon::now()->format('Y'), 2, 2);

        // Ambil rcabang dari pengguna yang sedang login
        $kode_user = Transusers::where('no_invoice', $no_po_approve)->value('user_kode');
        $user_id = Transusers::where('no_invoice', $no_po_approve)->value('user_id');
        $user_name = Transusers::where('no_invoice', $no_po_approve)->value('nama_cust');
        $rcabang = Transactions::where('no_invoice', $no_po_approve)->pluck('rcabang')->first();

        // Mulai transaksi
        DB::beginTransaction();

        try {
            // Update Status PO ke 2 yang berarti tidak muncul lagi di aproved maupun di edit
            DB::table('po_online')
                ->where('no_invoice', $no_po_approve)
                ->update(['status_po' => 2]);

            // Ambil nomor urut terakhir untuk transaksi berdasarkan bulan dan tahun yang sama
            $lastInvoice = Transusers::where('no_invoice', 'like', '%'.$currentMonthYear.'%')
                                        ->orderBy('no_invoice', 'desc')
                                        ->lockForUpdate()
                                        ->first();

            // Tentukan nomor urut berdasarkan nomor invoice terakhir
            $nextInvoiceNumber = $lastInvoice ? (int) substr($lastInvoice->no_invoice, -5) + 1 : 1;

            // Format nomor invoice dengan menambahkan prefix dan nomor urut
            $invoiceNumber = 'POL-' . $currentMonthYear . str_pad($nextInvoiceNumber, 5, '0', STR_PAD_LEFT);

            Transusers::create([
                'no_invoice' => $invoiceNumber,
                'user_id' => $user_id,
                'nama_cust' => $user_name,
                'user_kode' => $kode_user,
            ]);

            // Proses menyimpan data produk
            foreach ($products as $product) {
                // menghilangkan titik di total
                // $cleaned_total = intval(str_replace('.', '', explode(',', $product['total'])[0]));
                $total_net = $product['total'];
                $ppn = $product['ppn_trans']; // contoh: 10
                $dpp = round($total_net / (1 + ($ppn / 100)));
                $ppn_rupiah = $total_net - $dpp;
                $diskon_rupiah = ($product['diskon'] / 100) * $product['harga'];
                Transactions::create([
                    'no_invoice' => $invoiceNumber,  // Menyimpan nomor invoice
                    'kd_brg' => $product['kd_barang'],
                    'nama_brg' => $product['nama'],
                    'harga' => $product['harga'],
                    'qty_unit' => $product['unit'],
                    'satuan' => $product['satuan'],
                    'qty_order' => $product['jumlah_order'] - $product['jumlah'],
                    'ppn' => $product['ppn_trans'],
                    'rppn' => $ppn_rupiah,
                    'dpp' => $total_net - $ppn_rupiah,
                    'disc' => $product['diskon'],
                    'rdisc' => $diskon_rupiah,
                    'ndisc' => $product['diskon_rp'],
                    'ttldisc' => round(($diskon_rupiah + $product['diskon_rp']) * $product['jumlah']),
                    'ttl_gross' => $product['jumlah'] * $product['harga'],
                    'total' => $product['total'],
                    'rcabang' => $rcabang,  // Menyimpan rcabang dari pengguna yang login
                    'status_po' => 0,
                    'status_faktur' => 0,
                    'history_inv' => $no_po_approve,
                ]);
            }

            // Commit transaksi jika berhasil
            DB::commit();

            return response()->json(['message' => 'Products saved successfully!','invoice_number' => $invoiceNumber,], 200);
        } catch (\Exception $e) {
            // Rollback transaksi jika ada error
            DB::rollBack();

            // Kembalikan pesan error
            return response()->json(['error' => 'Failed to save products: ' . $e->getMessage()], 500);
        }
    }

// =================================== Transaksi Success ======================================
    public function success_transaksi(){
        return view('transaksi.success_transaksi');
    }

    public function filter_success_invoice(Request $request){
        if (!Auth::check()) {
            return response()->json(['message' => 'Silakan login terlebih dahulu'], 401);
        }

        $start = $request->input('start');   // posisi awal data
        $length = $request->input('length'); // berapa baris per halaman
        $draw = $request->input('draw');     // digunakan untuk menjaga sinkronisasi DataTables

        $query = DB::table('po_userby as a')
            ->join('po_online as b', 'a.no_invoice', '=', 'b.no_invoice')
            ->select(
                'a.no_invoice',
                DB::raw('DATE(a.created_at) as created_at'),
                'a.user_id',
                'a.user_kode',
                DB::raw('SUM(b.total) as total')
            )
            ->where('b.status_po', '!=', 0)
            ->where('b.qty_sup', '!=', 0)
            // ->whereNotNull('b.no_invoice')
            ->groupBy('a.no_invoice', 'a.created_at', 'a.user_id', 'a.user_kode')
            ->orderBy('a.created_at', 'desc');

            $userRole = Auth::user()->roles;
            if ($userRole === 'customer') {
                $query->where('a.user_kode', Auth::user()->user_kode);
            } elseif ($userRole === 'staff') {
                $query->where('a.user_id', Auth::user()->user_id);
            } elseif ($userRole === 'admin') {
                // Tidak ada filter tambahan untuk admin, karena admin bisa melihat semua data
            } else {
                return response()->json(['message' => 'Anda tidak memiliki izin untuk mengakses data ini'], 403);
            }

            if ($request->has('startDate') && $request->startDate) {
                $query->where('a.created_at', '>=', $request->startDate);
            }

            if ($request->has('endDate') && $request->endDate) {
                $query->where('a.created_at', '<=', $request->endDate);
            }

            if ($request->has('searchText') && $request->searchText) {
                $query->where(function($q) use ($request) {
                    $userRole = Auth::user()->roles;
                    $searchText = $request->searchText;

                    // Cari berdasarkan no_invoice
                    $q->where('a.no_invoice', 'like', '%' . $searchText . '%');

                    // Tambahkan filter berdasarkan role
                    if ($userRole === 'staff') {
                        // Staff hanya bisa melihat data dengan user_kode mereka
                        // $q->where('a.user_kode', Auth::user()->user_kode);
                        $q->orWhere('a.user_kode', 'like', '%' . $searchText . '%');
                    } elseif ($userRole === 'admin') {
                        // Admin bisa melihat data dengan user_kode atau user_id mereka
                        $q->orWhere('a.user_kode', 'like', '%' . $searchText . '%')
                          ->orWhere('a.user_id', 'like', '%' . $searchText . '%');
                    }
                });
            }

        $totalFiltered = $query->count(); // sebelum paginate, total hasil filter

        $data = $query->offset($start)
                    ->limit($length)
                    ->get();

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalFiltered, // jumlah semua data yang difilter
            'recordsFiltered' => $totalFiltered,
            'data' => $data
        ]);

    }

    public function get_po_success_det(Request $request){
        $no_invoice = $request->input('no_invoice');
        $query = DB::table('po_online')
            ->select([
                'no_invoice',
                'kd_brg',
                'nama_brg',
                'harga',
                'qty_unit',
                'satuan',
                'qty_order',
                'qty_sup',
                'disc',
                'ndisc',
                'ndisc',
                'ppn',
                'total'
            ])
            ->where('no_invoice', $no_invoice)
            ->get();

        $grandTotal = $query->sum('total');

        return response()->json([
            'data' => $query,
            'grand_total' => $grandTotal
        ]);
    }
// =================================== End Of Transaksi Success ======================================
}

// Jaga Jaga Vesi Update Lama Tanpa Lock
// public function update_products(Request $request){
//     // Ambil data dari request
//     $products = $request->input('products');
//     $noInvoice = $request->input('value_invo'); // Ambil no_invoice dari request
//     $kodeUser = $request->input('kode_user');

//     // Ambil rcabang dari pengguna yang sedang login
//     $rcabang = Auth::user()->rcabang;
//     $roles_user = Auth::user()->roles;
//     $user_kode = Auth::user()->user_kode;
//     // jika ingin mengisi Transusers dengan data sesuai yang login
//     $user_id = Auth::user()->user_id;
//     $user_name = Auth::user()->name;

//     // Memastikan jika user login customer user_kode harus sama mencegah update data yng diubah admin
//     if ($roles_user == 'customer') {
//         // Periksa apakah Transusers memiliki nilai sesuai kondisi
//         $statusRolesCheck = Transusers::where('no_invoice', $noInvoice)
//             ->where('user_kode', $user_kode)
//             ->exists();

//         if (!$statusRolesCheck) {
//             return response()->json([
//                 'error' => 'Anda tidak dapat mengupdate produk karena status_po Anda tidak memenuhi syarat, Tolong Refresh.'], 403);
//         }
//     }

//     // Memastikan Semua Nilai status_po = 0
//     $statusPoCheck = Transactions::where('no_invoice', $noInvoice)
//                               ->where('status_po', '!=', 0)
//                               ->exists();
//     if ($statusPoCheck) {
//         return response()->json(['error' => 'Tidak dapat melanjutkan, terdapat transaksi dengan status_po selain 0'], 400);
//     }

//     // Untuk Mengambil data user_id dan nama_cust dari Transusers berdasarkan no_invoice sebelumnya
//     $previousUserData = Transusers::where('no_invoice', $noInvoice)->first();

//     if ($previousUserData) {
//         $user_id_prev = $previousUserData->user_id;
//         $user_name_prev = $previousUserData->nama_cust;
//     }

//     // Simpan nilai created_at lama
//     $created_at_transusers = $previousUserData->created_at; // Untuk tabel Transusers
//     $transactionsCreatedAt = Transactions::where('no_invoice', $noInvoice)->pluck('created_at')->first();

//     // Mulai transaksi
//     DB::beginTransaction();

//     try {
//         Transusers::where('no_invoice', $noInvoice)->delete();
//         Transactions::where('no_invoice', $noInvoice)->delete();

//         // Simpan data baru ke tabel Transusers
//         Transusers::create([
//             'no_invoice' => $noInvoice,
//             'user_id' => $user_id_prev,
//             'nama_cust' => $user_name_prev,
//             'user_kode' => $kodeUser,
//             'created_at' => $created_at_transusers,
//             'updated_at' => Carbon::now(),
//         ]);

//         foreach ($products as $product) {
//             // menghilangkan titik di total
//             $cleaned_total = str_replace(',', '.', str_replace('.', '', $product['total']));
//             Transactions::create([
//                 'no_invoice' => $noInvoice, // Menggunakan nomor invoice lama
//                 'kd_brg' => $product['kd_barang'],
//                 'nama_brg' => $product['nama'],
//                 'harga' => $product['harga'],
//                 'qty_unit' => $product['unit'],
//                 'satuan' => $product['satuan'],
//                 'qty_order' => $product['jumlah'],
//                 'disc' => $product['diskon'],
//                 'total' => $cleaned_total,
//                 'rcabang' => $rcabang, // Menyimpan rcabang dari pengguna yang login
//                 'status_po' => 0,
//                 'created_at' => $transactionsCreatedAt,
//                 'updated_at' => Carbon::now(),
//             ]);
//         }

//         // Commit transaksi jika berhasil
//         DB::commit();

//         return response()->json(['message' => 'Products updated successfully!'], 200);
//     } catch (\Exception $e) {
//         // Rollback transaksi jika ada error
//         DB::rollBack();

//         // Kembalikan pesan error
//         return response()->json(['error' => 'Failed to update products: ' . $e->getMessage()], 500);
//     }
// }


// ### Fungsi Save Product Lama
// public function save_products(Request $request){
//         // Ambil data produk dari request
//         $products = $request->input('products');
//         $kode_user = $request->input('kode_user');

//         // Ambil bulan dan tahun saat ini, format 'mY' misalnya '0225' untuk Februari 2025
//         $currentMonthYear = Carbon::now()->format('m') . substr(Carbon::now()->format('Y'), 2, 2);

//         // Ambil rcabang dari pengguna yang sedang login
//         $rcabang = Auth::user()->rcabang;
//         $user_id = Auth::user()->user_id;
//         $user_name = Auth::user()->name;

//         // Mulai transaksi
//         DB::beginTransaction();

//         try {
//             // Ambil nomor urut terakhir untuk transaksi berdasarkan bulan dan tahun yang sama
//             $lastInvoice = Transusers::where('no_invoice', 'like', '%'.$currentMonthYear.'%')
//                                         ->orderBy('no_invoice', 'desc')
//                                         ->lockForUpdate()
//                                         ->first();

//             // Tentukan nomor urut berdasarkan nomor invoice terakhir
//             $nextInvoiceNumber = $lastInvoice ? (int) substr($lastInvoice->no_invoice, -5) + 1 : 1;

//             // Format nomor invoice dengan menambahkan prefix dan nomor urut
//             $invoiceNumber = 'POL-' . $currentMonthYear . str_pad($nextInvoiceNumber, 5, '0', STR_PAD_LEFT);

//             Transusers::create([
//                 'no_invoice' => $invoiceNumber,
//                 'user_id' => $user_id,
//                 'nama_cust' => $user_name,
//                 'user_kode' => $kode_user,
//             ]);

//             // Proses menyimpan data produk
//             foreach ($products as $product) {
//                 // menghilangkan titik di total
//                 // $cleaned_total = intval(str_replace('.', '', explode(',', $product['total'])[0]));
//                 $total_net = $product['total'];
//                 $ppn = $product['ppn_trans']; // contoh: 10
//                 $dpp = round($total_net / (1 + ($ppn / 100)));
//                 $ppn_rupiah = $total_net - $dpp;
//                 $diskon_rupiah = ($product['diskon'] / 100) * $product['harga'];
//                 Transactions::create([
//                     'no_invoice' => $invoiceNumber,  // Menyimpan nomor invoice
//                     'kd_brg' => $product['kd_barang'],
//                     'nama_brg' => $product['nama'],
//                     'harga' => $product['harga'],
//                     'qty_unit' => $product['unit'],
//                     'satuan' => $product['satuan'],
//                     'qty_order' => $product['jumlah'],
//                     'ppn' => $product['ppn_trans'],
//                     'rppn' => $ppn_rupiah,
//                     'dpp' => $total_net - $ppn_rupiah,
//                     'disc' => $product['diskon'],
//                     'rdisc' => $diskon_rupiah,
//                     'ndisc' => $product['diskon_rp'],
//                     'ttldisc' => ($diskon_rupiah + $product['diskon_rp']) * $product['jumlah'],
//                     'ttl_gross' => $product['jumlah'] * $product['harga'],
//                     'total' => $product['total'],
//                     'rcabang' => $rcabang,  // Menyimpan rcabang dari pengguna yang login
//                     'status_po' => 0,
//                     'status_faktur' => 0,
//                 ]);
//             }

//             // Commit transaksi jika berhasil
//             DB::commit();

//             return response()->json(['message' => 'Products saved successfully!','invoice_number' => $invoiceNumber,], 200);
//         } catch (\Exception $e) {
//             // Rollback transaksi jika ada error
//             DB::rollBack();

//             // Kembalikan pesan error
//             return response()->json(['error' => 'Failed to save products: ' . $e->getMessage()], 500);
//         }
//     }


// ### Fungsi Update Product Lama
 // ###  Update Transaksi
    // public function update_products(Request $request){
    //     $products = $request->input('products');
    //     $noInvoice = $request->input('value_invo');
    //     $kodeUser = $request->input('kode_user');

    //     $rcabang = Auth::user()->rcabang;
    //     $roles_user = Auth::user()->roles;
    //     $user_kode = Auth::user()->user_kode;
    //     $user_id = Auth::user()->user_id;
    //     $user_name = Auth::user()->name;

    //     if ($roles_user == 'customer') {
    //         $statusRolesCheck = Transusers::where('no_invoice', $noInvoice)
    //             ->where('user_kode', $user_kode)
    //             ->exists();

    //         if (!$statusRolesCheck) {
    //             return response()->json([
    //                 'error' => 'Anda tidak dapat mengupdate produk karena status_po Anda tidak memenuhi syarat, Tolong Refresh.'
    //             ], 403);
    //         }
    //     }

    //     $statusPoCheck = Transactions::where('no_invoice', $noInvoice)
    //         ->where('status_po', '!=', 0)
    //         ->exists();
    //     if ($statusPoCheck) {
    //         return response()->json([
    //             'error' => 'Tidak dapat melanjutkan, terdapat transaksi dengan status_po selain 0'
    //         ], 400);
    //     }

    //     DB::beginTransaction();

    //     try {
    //         // Lock dulu data transusers biar tidak ada race condition
    //         $lockedTransUser = Transusers::where('no_invoice', $noInvoice)
    //             ->lockForUpdate()
    //             ->first();

    //         if (!$lockedTransUser) {
    //             DB::rollBack();
    //             return response()->json([
    //                 'error' => 'Data tidak ditemukan atau tidak bisa dikunci.'
    //             ], 404);
    //         }

    //         // Simpan data penting sebelum delete
    //         $user_id_prev = $lockedTransUser->user_id;
    //         $user_name_prev = $lockedTransUser->nama_cust;
    //         $created_at_transusers = $lockedTransUser->created_at;

    //         $transactionsCreatedAt = Transactions::where('no_invoice', $noInvoice)
    //             ->pluck('created_at')->first();

    //         // Hapus data lama
    //         Transusers::where('no_invoice', $noInvoice)->delete();
    //         Transactions::where('no_invoice', $noInvoice)->delete();

    //         // Simpan data baru
    //         Transusers::create([
    //             'no_invoice' => $noInvoice,
    //             'user_id' => $user_id_prev,
    //             'nama_cust' => $user_name_prev,
    //             'user_kode' => $kodeUser,
    //             'created_at' => $created_at_transusers,
    //             'updated_at' => Carbon::now(),
    //         ]);

    //         foreach ($products as $product) {
    //             $cleaned_total = str_replace(',', '.', str_replace('.', '', $product['total']));
    //             $total_net = (float) $cleaned_total;
    //             // Hitung DPP & PPN mundur (sesuai gaya Excel klien)
    //             $ppn = $product['ppn_trans']; // contoh: 10
    //             $dpp = round($total_net / (1 + ($ppn / 100)));
    //             $ppn_rupiah = $total_net - $dpp;

    //             $diskon_rupiah = ($product['diskon'] / 100) * $product['harga'];
    //             Transactions::create([
    //                 'no_invoice' => $noInvoice,
    //                 'kd_brg' => $product['kd_barang'],
    //                 'nama_brg' => $product['nama'],
    //                 'harga' => $product['harga'],
    //                 'qty_unit' => $product['unit'],
    //                 'satuan' => $product['satuan'],
    //                 'qty_order' => $product['jumlah'],
    //                 'ppn' => $product['ppn_trans'],
    //                 'rppn' => $ppn_rupiah,
    //                 'dpp' => $total_net - $ppn_rupiah,
    //                 'disc' => $product['diskon'],
    //                 'rdisc' => $diskon_rupiah,
    //                 'ndisc' => $product['diskon_rp'],
    //                 'ttldisc' => ($diskon_rupiah + $product['diskon_rp']) * $product['jumlah'],
    //                 'ttl_gross' => $product['jumlah'] * $product['harga'],
    //                 'total' => $cleaned_total,
    //                 'rcabang' => $rcabang,
    //                 'status_po' => 0,
    //                 'status_faktur' => 0,
    //                 'created_at' => $transactionsCreatedAt,
    //                 'updated_at' => Carbon::now(),
    //             ]);
    //         }

    //         DB::commit();

    //         return response()->json(['message' => 'Products updated successfully!'], 200);
    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         return response()->json(['error' => 'Failed to update products: ' . $e->getMessage()], 500);
    //     }
    // }
