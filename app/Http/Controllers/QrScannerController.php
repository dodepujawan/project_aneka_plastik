<?php

namespace App\Http\Controllers;

use App\Models\Transactions;
use App\Models\Transusers;
use App\Models\User;
use App\Models\QrisLokasiSales;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class QrScannerController extends Controller
{
    public function index_qris(){
        return view('qris.index_qris');
    }

    public function cek_user_qr(Request $request){
        $kode = $request->input('kode');

        $user = User::where('user_id', $kode)->first();

        if ($user) {
            return response()->json([
                'status' => 'ok',
                'user_id' => $user->user_id,
                'name' => $user->name,
            ]);
        }

        return response()->json(['status' => 'not_found']);
    }
    public function simpan_kode_qris(Request $request){
        $request->validate([
            'cust_id' => 'required',
        ]);

        DB::table('qris_lokasi_sales')->insert([
            'user_id' => Auth::user()->user_id,
            'customer_id' => $request->cust_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Kode Customer berhasil disimpan.']);
    }
    public function qris_list(Request $request){
        $data = DB::table('qris_lokasi_sales as a')
        ->leftJoin('users as b', 'a.user_id', '=', 'b.user_id')
        ->leftJoin('users as c', 'a.customer_id', '=', 'c.user_id')
        ->select(
            'a.id',
            'a.user_id',
            'b.name as sales_name',
            'a.customer_id',
            'c.name as customer_name',
            DB::raw('DATE_FORMAT(a.created_at, "%d-%m-%Y %H:%i") as created_at')
        )
        ->get();

        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $data->count(),
            'recordsFiltered' => $data->count(),
            'data' => $data,
        ]);
    }
    public function delete_qris($id){
        $user = Auth::user(); // pastikan user sudah login (gunakan sanctum/session)
        $qris = QrisLokasiSales::find($id);

        if ($user->role === 'customer') {
            return response()->json(['message' => 'Maaf anda belum bisa menjalankan perintah ini.'], 404);
        }

        if (!$qris) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }

        if ($user->role === 'staff') {
            // Hitung selisih jam dari created_at
            $createdAt = Carbon::parse($qris->created_at)->timezone('Asia/Makassar');
            $now = Carbon::now('Asia/Makassar');

            if ($now->diffInHours($createdAt) > 24) {
                return response()->json(['message' => 'Tidak bisa menghapus data yang lebih dari 24 jam.'], 403);
            }
        }

        $qris->delete();

        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}
