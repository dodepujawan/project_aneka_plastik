<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class CabangController extends Controller
{
    public function index()
    {
        return view('cabang.cabang');
    }

    public function store(Request $request)
    {
        $result = [];
        DB::beginTransaction();

        try {
            $validatedData = $request->validate([
                'nama_cabang' => 'required|string|max:255',
                'alamat_cabang' => 'required|string|max:255',
                'telp_cabang' => 'required|string|max:255',
            ]);

            $role = 'TAP';
            $lastCabang = DB::table('cabangs')
                ->where('cabang_id', 'LIKE', $role . '%')
                ->orderBy('cabang_id', 'desc')
                ->lockForUpdate() // Lock rows for update to avoid race condition
                ->first();

            if ($lastCabang) {
                $lastNumber = (int) substr($lastCabang->cabang_id, strlen($role));
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }

            $newCabangId = $role . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

            Cabang::create([
                'lokal_id' => $request->lokal_id,
                'cabang_id' => $newCabangId,
                'nama' => $request->nama_cabang,
                'alamat' => $request->alamat_cabang,
                'telp' => $request->telp_cabang,
            ]);

            // Commit the transaction
            DB::commit();

            $result['pesan'] = 'Register Berhasil. Cabang Baru sudah Aktif.';
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Rollback the transaction in case of validation error
            DB::rollback();
            $result['pesan'] = 'Validation Error: ' . implode(', ', Arr::flatten($e->errors()));
        } catch (\Exception $e) {
            // Rollback the transaction in case of general error
            DB::rollback();
            $result['pesan'] = 'Error: ' . $e->getMessage();
        }

        return response()->json($result);
    }

    public function generate_cabang_id(Request $request)
    {
        $role = 'TAP';

        $lastUser = DB::table('cabangs')
            ->where('cabang_id', 'LIKE', $role . '%')
            ->orderBy('cabang_id', 'desc')
            ->lockForUpdate() // Mengunci baris yang sedang dibaca(mencegah update dengan 2 kode yang sama)
            ->first();

        if ($lastUser) {
            // 1. Ambil Data user_id dari Objek, 2.strlen menghitung panjang string, substr memotong string berarti disini dipotong 2 karena nilai strlen role =2 _> substr('AD0005', 2) maka didapat nilai 0005. lalu int mendapat nilai integer disini berarti bernilai 5
            $lastNumber = (int) substr($lastUser->cabang_id, strlen($role));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $newCabangId = $role . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        return response()->json(['cabang_id' => $newCabangId]);
    }

    public function getcabang()
    {
        $cabang = Cabang::select('cabang_id', 'nama')->get();

        return response()->json($cabang);
    }

    public function get_cabang_datatables()
    {
        $cabangs = Cabang::all();

        return response()->json($cabangs);
    }

    // ### Function List Cabang
    public function index_list_cabang(){
        return view('cabang.listcabang');
    }

    public function edit_list_cabang($id){
        $user = Cabang::find($id);
        return response()->json($user);
    }

    public function update_cabang(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'id_cabang' => 'required', // Pastikan ID cabang ada
            'nama_cabang' => 'required|string|max:255',
            'alamat_cabang' => 'required|string|max:255',
            'telp_cabang' => 'required|string|max:15',
        ]);

        try {
            // Cari cabang berdasarkan ID
            // $cabang = Cabang::findOrFail($request->id_cabang);
            $cabang = Cabang::where('cabang_id', $request->input('id_cabang'))->first();

            if (!$cabang) {
                return response()->json(['pesan' => 'User not found.'], 404);
            }

            // Update data cabang
            $cabang->nama = $request->nama_cabang;
            $cabang->alamat = $request->alamat_cabang;
            $cabang->telp = $request->telp_cabang;
            $cabang->lokal_id = $request->id_lokal;
            $cabang->save();

            return response()->json(['status' => 'success', 'message' => 'Cabang updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function delete_list_cabang($id){
        $user = Cabang::find($id);

        if ($user){
            $user->delete();
            return response()->json(['success' => 'User berhasil dihapus']);
        }else{
            return response()->json(['error' => 'User tidak ditemukan'], 404);
        }
    }

}
