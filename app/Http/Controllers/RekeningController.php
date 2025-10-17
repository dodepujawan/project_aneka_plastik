<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use App\Models\Rekening;

class RekeningController extends Controller
{
    public function rekening()
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

}
