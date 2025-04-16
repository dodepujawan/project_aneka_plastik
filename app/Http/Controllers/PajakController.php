<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PoPajak;

class PajakController extends Controller
{
    public function get_pajak()
    {
        $pajak = PoPajak::latest()->first();

        return response()->json([
            'status' => 'success',
            'data' => $pajak
        ]);
    }

    public function update_pajak(Request $request)
    {
        $request->validate([
            'ppn' => 'required|numeric|min:0'
        ]);

        $pajak = PoPajak::create([
            'ppn' => $request->ppn
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pajak berhasil diperbarui.',
            'data' => $pajak
        ]);
    }
}
