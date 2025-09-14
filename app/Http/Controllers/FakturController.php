<?php

namespace App\Http\Controllers;

use App\Models\Transactions;
use App\Models\Transusers;
use App\Models\Faktur;
use App\Models\FakturUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FakturController extends Controller
{
    public function index(){
        return view('faktur.index_faktur');
    }

    public function filter_no_faktur(Request $request){
        if (!Auth::check()) {
            return response()->json(['message' => 'Silakan login terlebih dahulu'], 401);
        }

        $start = $request->input('start');   // posisi awal data
        $length = $request->input('length'); // berapa baris per halaman
        $draw = $request->input('draw');     // digunakan untuk menjaga sinkronisasi DataTables

        $query = DB::table('faktur_userby as a')
            ->join('faktur_online as b', 'a.no_faktur', '=', 'b.no_faktur')
            ->select(
                'a.no_faktur',
                DB::raw('DATE(a.created_at) as created_at'),
                'a.user_id',
                'a.user_kode',
                'b.history_inv',
                DB::raw('SUM(b.total) as total')
            )
            // ->where('b.status_po', '!=', 0)
            // ->where('b.qty_sup', '!=', 0)
            // ->whereNotNull('b.no_invoice')
            ->groupBy('a.no_faktur', 'a.created_at', 'a.user_id', 'a.user_kode', 'b.history_inv')
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
                    $q->where('a.no_faktur', 'like', '%' . $searchText . '%');
                    $q->orWhere('b.history_inv', 'like', '%' . $searchText . '%');

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

    // public function get_faktur_det(Request $request){
    //     $no_faktur = $request->input('no_faktur');
    //     $role = auth()->user()->roles;
    //     $query = DB::table('faktur_online')
    //         ->select([
    //             'no_faktur',
    //             'kd_brg',
    //             'nama_brg',
    //             'harga',
    //             'qty_unit',
    //             'satuan',
    //             'qty_order',
    //             'disc',
    //             'ndisc',
    //             'ndisc',
    //             'ppn',
    //             'total',
    //             'created_at'
    //         ])
    //         ->where('no_faktur', $no_faktur)
    //         ->get();

    //     $grandTotal = $query->sum('total');

    //     // Default Hide Button
    //     $hidePrint = false; // default: tampilkan tombol

    //     if (strtolower($role) !== 'admin' && $query->isNotEmpty()) {
    //         $createdAt = Carbon::parse($query[0]->created_at);
    //         $now = Carbon::now();
    //         $hoursDiff = $createdAt->diffInHours($now);

    //         if ($hoursDiff > 24) {
    //             $hidePrint = true; // tombol di-hide kalau bukan admin & sudah 24 jam
    //         }
    //     }

    //     return response()->json([
    //         'data' => $query,
    //         'grand_total' => $grandTotal,
    //         'hide_print' => $hidePrint
    //     ]);
    // }

    public function get_faktur_to_table(Request $request){
        $no_invoice = $request->no_invoice;

        $data = DB::table('faktur_userby as a')
        ->leftJoin('faktur_online as b', 'a.no_faktur', '=', 'b.no_faktur')
        ->leftJoin('mcustomer as c', 'a.user_kode', '=', 'c.CUSTOMER')
        ->select(
            'a.no_faktur',
            'a.created_at',
            'a.user_kode',
            'a.pembayaran',
            'a.nominal_bayar',
            'a.kembalian',
            'a.nama_bank',
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
        ->where('a.no_faktur', $no_invoice)
        ->get();

        return response()->json(['data' => $data]);
    }

    // ###  Update Faktur
    public function update_faktur(Request $request){
        $products = $request->input('products');
        $noFaktur = $request->input('value_invo');
        $kodeUser = $request->input('kode_user');
        $noInvoice = Faktur::where('no_faktur', $noFaktur)->value('history_inv');
        $method = $request->input('method');
        $namaBank = $request->input('nama_bank');
        $jumlahBayar = $request->input('jumlah_bayar');
        $jumlahKembalian = $request->input('jumlah_kembalian');

        $rcabang = Auth::user()->rcabang;
        $roles_user = Auth::user()->roles;
        $user_kode = Auth::user()->user_kode;
        $user_id = Auth::user()->user_id;
        $user_name = Auth::user()->name;

        if ($roles_user == 'customer') {
            $statusRolesCheck = FakturUser::where('no_faktur', $noFaktur)
                ->where('user_kode', $user_kode)
                ->exists();

            if (!$statusRolesCheck) {
                return response()->json([
                    'error' => 'Anda tidak dapat mengupdate produk karena status_po Anda tidak memenuhi syarat, Tolong Refresh.'
                ], 403);
            }
        }

        DB::beginTransaction();

        try {
            // Lock dulu data fakturUser biar tidak ada race condition
            $lockedFakturUser = FakturUser::where('no_faktur', $noFaktur)
                ->lockForUpdate()
                ->first();

            if (!$lockedFakturUser) {
                DB::rollBack();
                return response()->json([
                    'error' => 'Data tidak ditemukan atau tidak bisa dikunci.'
                ], 404);
            }

            // Lock dulu data transusers biar tidak ada race condition
            $lockedTransUser = Transusers::where('no_invoice', $noInvoice)
                ->lockForUpdate()
                ->first();

            if (!$lockedTransUser) {
                DB::rollBack();
                return response()->json([
                    'error' => 'Data tidak ditemukan atau tidak bisa dikunci.'
                ], 404);
            }

            // Simpan data penting sebelum delete
            $user_id_prev = $lockedTransUser->user_id;
            $user_kode_prev = $lockedTransUser->user_kode;
            $user_name_prev = $lockedTransUser->nama_cust;
            $created_at_transusers = $lockedTransUser->created_at;

            $user_id_faktur = $lockedFakturUser->user_id;
            $user_kode_faktur = $lockedFakturUser->user_kode;
            $user_name_faktur = $lockedFakturUser->nama_cust;
            $created_at_faktur = $lockedFakturUser->created_at;

            $transCreatedAt = Transactions::where('no_invoice', $noInvoice)
                ->pluck('created_at')->first();

            $fakturCreatedAt = Faktur::where('no_faktur', $noFaktur)
                ->pluck('created_at')->first();

            // Hapus data lama
            FakturUser::where('no_faktur', $noFaktur)->delete();
            Faktur::where('no_faktur', $noFaktur)->delete();
            Transusers::where('no_invoice', $noInvoice)->delete();
            Transactions::where('no_invoice', $noInvoice)->delete();

            // Simpan data baru Transaksi
            Transusers::create([
                'no_invoice' => $noInvoice,
                'user_id' => $user_id_prev,
                'nama_cust' => $user_name_prev,
                'user_kode' => $kodeUser,
                'created_at' => $created_at_transusers,
                'updated_at' => Carbon::now(),
            ]);

            foreach ($products as $product) {
                $cleaned_total = str_replace(',', '.', str_replace('.', '', $product['total']));
                $total_net = (float) $cleaned_total;
                // Hitung DPP & PPN mundur (sesuai gaya Excel klien)
                $ppn = $product['ppn_trans']; // contoh: 10
                $dpp = round($total_net / (1 + ($ppn / 100)));
                $ppn_rupiah = $total_net - $dpp;

                $diskon_rupiah = ($product['diskon'] / 100) * $product['harga'];
                Transactions::create([
                    'no_invoice' => $noInvoice,
                    'kd_brg' => $product['kd_barang'],
                    'nama_brg' => $product['nama'],
                    'harga' => $product['harga'],
                    'qty_unit' => $product['unit'],
                    'satuan' => $product['satuan'],
                    'qty_order' => $product['jumlah'],
                    'ppn' => $product['ppn_trans'],
                    'rppn' => $ppn_rupiah,
                    'dpp' => $total_net - $ppn_rupiah,
                    'disc' => $product['diskon'],
                    'rdisc' => $diskon_rupiah,
                    'ndisc' => $product['diskon_rp'],
                    'ttldisc' => ($diskon_rupiah + $product['diskon_rp']) * $product['jumlah'],
                    'ttl_gross' => $product['jumlah'] * $product['harga'],
                    'total' => $cleaned_total,
                    'rcabang' => $rcabang,
                    'status_po' => 0,
                    'status_faktur' => 1,
                    'created_at' => $transCreatedAt,
                    'updated_at' => Carbon::now(),
                ]);
            }

            // Simpan data baru Faktur
            FakturUser::create([
                'no_faktur' => $noFaktur,
                'user_id' => $user_id_prev,
                'nama_cust' => $user_name_prev,
                'user_kode' => $kodeUser,
                'pembayaran' => $method,
                'nominal_bayar' => $jumlahBayar,
                'kembalian' => $jumlahKembalian,
                'nama_bank' => $namaBank,
                'created_at' => $created_at_faktur,
                'updated_at' => Carbon::now(),
            ]);

            foreach ($products as $product) {
                $cleaned_total = str_replace(',', '.', str_replace('.', '', $product['total']));
                $total_net = (float) $cleaned_total;
                // Hitung DPP & PPN mundur (sesuai gaya Excel klien)
                $ppn = $product['ppn_trans']; // contoh: 10
                $dpp = round($total_net / (1 + ($ppn / 100)));
                $ppn_rupiah = $total_net - $dpp;

                $diskon_rupiah = ($product['diskon'] / 100) * $product['harga'];
                Faktur::create([
                    'no_faktur' => $noFaktur,
                    'kd_brg' => $product['kd_barang'],
                    'nama_brg' => $product['nama'],
                    'harga' => $product['harga'],
                    'qty_unit' => $product['unit'],
                    'satuan' => $product['satuan'],
                    'qty_order' => $product['jumlah'],
                    'ppn' => $product['ppn_trans'],
                    'rppn' => $ppn_rupiah,
                    'dpp' => $total_net - $ppn_rupiah,
                    'disc' => $product['diskon'],
                    'rdisc' => $diskon_rupiah,
                    'ndisc' => $product['diskon_rp'],
                    'ttldisc' => ($diskon_rupiah + $product['diskon_rp']) * $product['jumlah'],
                    'ttl_gross' => $product['jumlah'] * $product['harga'],
                    'total' => $cleaned_total,
                    'rcabang' => $rcabang,
                    'status_po' => 0,
                    'history_inv' => $noInvoice,
                    'created_at' => $fakturCreatedAt,
                    'updated_at' => Carbon::now(),
                ]);
            }

            DB::commit();

            // Ambil ulang data untuk struk
            $items = Faktur::where('no_faktur', $noFaktur)->get();

            // Mulai string ESC/POS
            $esc = "\x1B@\n"; // Initialize printer

            // Header toko
            $esc .= "COPY 1\n";
            $esc .= "TOKO ANEKA PLASTIK\n";
            $esc .= "Jl. Hasanuddin No.51 Singaraja\n";
            $esc .= "-----------------------------\n";
            $esc .= "No Faktur : {$noFaktur}\n";
            $esc .= "Tanggal   : " . $items->first()->created_at->format('d-m-Y H:i') . "\n";
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
                    $diskonText .= '-Rp' . number_format($ndisc, 0, ',', '.') . ' ';
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
                $esc .= "Bayar  : " . number_format($jumlahBayar, 0, ',', '.') . "\n";
                $esc .= "Kembali  : " . number_format($jumlahKembalian, 0, ',', '.') . "\n";
                $esc .= "Metode : Cash\n";
            } elseif ($method === 'transfer') {
                $esc .= "Bayar  : " . number_format($grandTotal, 0, ',', '.') . "\n";
                $esc .= "Kembali  : 0\n";
                $esc .= "Metode : Transfer\n";
            } else { // bon
                $esc .= "Bayar  : 0\n";
                $esc .= "Kembali  : 0\n";
                $esc .= "Metode : Bon\n";
            }

            $esc .= "Terima kasih\n\n";

            // Potong kertas (kalau printer support)
            // $esc .= "\x1D\x56\x41";

            // ### COPY 2 ###
            $esc .= "COPY 2\n";
            $esc .= "TOKO ANEKA PLASTIK\n";
            $esc .= "Jl. Hasanuddin No.51 Singaraja\n";
            $esc .= "-----------------------------\n";
            $esc .= "No Faktur : {$noFaktur}\n";
            $esc .= "Tanggal   : " . $items->first()->created_at->format('d-m-Y H:i') . "\n";
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
                    $diskonText .= '-Rp' . number_format($ndisc, 0, ',', '.') . ' ';
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
                $esc .= "Bayar  : " . number_format($jumlahBayar, 0, ',', '.') . "\n";
                $esc .= "Kembali  : " . number_format($jumlahKembalian, 0, ',', '.') . "\n";
                $esc .= "Metode : Cash\n";
            } elseif ($method === 'transfer') {
                $esc .= "Bayar  : " . number_format($grandTotal, 0, ',', '.') . "\n";
                $esc .= "Kembali  : 0\n";
                $esc .= "Metode : Transfer\n";
            } else { // bon
                $esc .= "Bayar  : 0\n";
                $esc .= "Kembali  : 0\n";
                $esc .= "Metode : Bon\n";
            }

            $esc .= "Terima kasih\n\n";

            // Potong kertas (kalau printer support)
            // $esc .= "\x1D\x56\x41";

            // Encode ke base64 supaya RawBT bisa baca
            $base64 = base64_encode($esc);

            return response()->json([
                'message' => 'Products updated successfully!',
                'struk_text' => $base64
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'Failed to update products: ' . $e->getMessage()], 500);
        }
    }

    public function delete_faktur($no_faktur){
        try {
            // Ambil data faktur dulu
            $faktur = Faktur::where('no_faktur', $no_faktur)->first();

            if (!$faktur) {
                return response()->json(['status' => 'error', 'message' => 'Faktur tidak ditemukan'], 404);
            }

            // Ambil nilai history_inv dari faktur
            $historyInv = $faktur->history_inv;

            // Hapus Transactions & Transusers berdasarkan history_inv
            if ($historyInv) {
                Transactions::where('no_invoice', $historyInv)->delete();
                Transusers::where('no_invoice', $historyInv)->delete();
            }

            // Hapus FakturUser & Faktur berdasarkan no_faktur
            FakturUser::where('no_faktur', $no_faktur)->delete();
            $faktur->delete();

            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
