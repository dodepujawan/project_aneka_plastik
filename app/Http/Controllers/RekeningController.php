<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use App\Models\Rekening;
use Illuminate\Support\Facades\Auth;

class RekeningController extends Controller
{
    public function rekening()
    {
        return view('rekening.rekening_edit');
    }

    public function rekening_tambah()
    {
        return view('rekening.rekening');
    }

    public function generate_rekening_id(Request $request){

        $lastRekening = DB::table('bank_ol')
            ->orderBy('kode_bank', 'desc')
            ->first();

        if ($lastRekening) {
            // 1. Ambil Data user_id dari Objek, 2.strlen menghitung panjang string, substr memotong string berarti disini dipotong 2 karena nilai strlen role =2 _> substr('AD0005', 2) maka didapat nilai 0005. lalu int mendapat nilai integer disini berarti bernilai 5
            $lastNumber = (int) substr($lastRekening->kode_bank, strlen('BK'));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $newRekeningId = 'BK' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        return response()->json(['kode_bank' => $newRekeningId]);
    }

     // Method untuk internal use (tanpa Request) - YANG INI DIPAKAI
    private function generateRekeningIdInternal()
    {
        $lastRekening = DB::table('bank_ol')
            ->orderBy('kode_bank', 'desc')
            ->first();

        if ($lastRekening) {
            $lastNumber = (int) substr($lastRekening->kode_bank, 2); // Mulai dari index 2
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'BK' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function rekening_register(Request $request)
    {
        $result = [];

        // Start database transaction
        DB::beginTransaction();

        try {
            // Validate the request data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'rekening_number' => 'required|integer|min:5',
            ]);

            // Panggil metode generateUserId untuk mendapatkan user_id
            $rekening_id = $this->generateRekeningIdInternal();

            $rekening = Rekening::create([
                'kode_bank' => $rekening_id,
                'nama_bank' => $request->name,
                'no_rekening' =>$request->rekening_number,
            ]);

            DB::commit();
            $result['pesan'] = 'Register Berhasil. Rekening Anda sudah Aktif.';
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

    public function filter_rekening(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Silakan login terlebih dahulu'], 401);
        }

        $user = Auth::user();

        $allowedRoles = ['admin'];
        if (!in_array($user->roles, $allowedRoles)) {
            return response()->json(['message' => 'Akses ditolak'], 403);
        }

        // Jika pengguna memiliki peran yang sesuai, lanjutkan dengan query
        // Jika tidak ada penambahan spesifik perti CAST untuk date pakai ini -> $query = DB::table('users');
        $query = DB::table('bank_ol')
        ->select([
            'id',
            'kode_bank',
            'nama_bank',
            'no_rekening',
            'created_at'
        ]);

        $rekenings = $query->get();

        return response()->json([
            'data' => $rekenings
        ]);
    }

    public function edit_list_rekening($id){
        // Fetch user data
        $rekening = Rekening::find($id);// Assuming session has user id
        return response()->json($rekening);
    }

    public function update_list_rekening(Request $request){
        $result = [];
        DB::beginTransaction();
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'rekening_id' => 'required',
                'rekening_name' => 'required|string|max:255',
                'rekening_number' => 'required|string|min:5',
            ]);

            $rekening = Rekening::where('kode_bank', $request->input('rekening_id'))->first();

            if (!$rekening) {
                return response()->json(['pesan' => 'Rekening not found.'], 404);
            }

            $rekening->nama_bank = $request->rekening_name;
            $rekening->no_rekening = $request->rekening_number;
            $rekening->save();

            DB::commit();
            $result['pesan'] = 'Update Berhasil.';
            $result['rekening_id_baru'] = $rekening->rekening_id; // Tambahkan user_id baru jika berubah (untuk keperluan debugu, kalo tidak perlu bisa dihapus)
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            $result['pesan'] = 'Validation Error: ' . implode(', ', Arr::flatten($e->errors()));
        } catch (\Exception $e) {
            DB::rollback();
            $result['pesan'] = 'Error: ' . $e->getMessage();
        }
        return response()->json($result);
    }

    public function delete_list_rekening($id){
        $rekening = Rekening::find($id);

        if ($rekening){
            $rekening->delete();
            return response()->json(['success' => 'Rekening berhasil dihapus']);
        }else{
            return response()->json(['error' => 'Rekening tidak ditemukan'], 404);
        }
    }

}
