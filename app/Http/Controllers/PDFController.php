<?php

namespace App\Http\Controllers;

use App\Models\Transactions;
use App\Models\HargaNotif;
use mPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Jobs\ConvertPdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class PDFController extends Controller
{
    public function generate_pdf($invoice_number){
        $transaction = DB::table('po_userby as a')
            ->leftJoin('po_online as b', DB::raw('a.no_invoice COLLATE utf8mb4_unicode_ci'), '=', 'b.no_invoice')
            ->leftJoin('mcustomer as c', DB::raw('a.user_kode COLLATE utf8mb4_unicode_ci'), '=', 'c.CUSTOMER')
            ->leftJoin('users as d', DB::raw('a.user_id COLLATE utf8mb4_unicode_ci'), '=', 'd.user_id')
            ->select([
                'a.no_invoice',
                'a.user_id',
                DB::raw('DATE_FORMAT(a.created_at, "%d-%m-%Y") AS created_at'),
                'a.user_kode',
                'b.kd_brg',
                'b.nama_brg',
                'c.NAMACUST as nama_cust',
                'd.name',
                DB::raw('CAST(b.qty_order AS UNSIGNED) AS qty_order'),
                DB::raw('CAST(b.qty_unit AS UNSIGNED) AS qty_unit'),
                'b.satuan',
                DB::raw('CAST(b.harga AS UNSIGNED) AS harga'),
                DB::raw('CAST(b.disc AS UNSIGNED) AS disc'),
                DB::raw('CAST(b.total AS UNSIGNED) AS total'),
                'b.rcabang'
            ])
            ->where('b.status_po', 0)
            ->where('a.no_invoice', $invoice_number)
            ->get();

        if (!$transaction) {
            return redirect()->route('error_page')->with('message', 'Invoice not found');
        }

        $html = view('pdf.po_online', compact('transaction'))->render();

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($html);
        $mpdf->Output('PO_online_' . $invoice_number . '.pdf', 'I');
    }
    public function generate_pdf_approved($invoice_number){
        $transaction = DB::table('po_userby as a')
            ->leftJoin('po_online as b', DB::raw('a.no_invoice COLLATE utf8mb4_unicode_ci'), '=', 'b.no_invoice')
            ->leftJoin('mcustomer as c', DB::raw('a.user_kode COLLATE utf8mb4_unicode_ci'), '=', 'c.CUSTOMER')
            ->leftJoin('users as d', DB::raw('a.user_id COLLATE utf8mb4_unicode_ci'), '=', 'd.user_id')
            ->select([
                'a.no_invoice',
                'a.user_id',
                DB::raw('DATE_FORMAT(a.created_at, "%d-%m-%Y") AS created_at'),
                'a.user_kode',
                'b.kd_brg',
                'b.nama_brg',
                'c.NAMACUST as nama_cust',
                'd.name',
                DB::raw('CAST(b.qty_order AS UNSIGNED) AS qty_order'),
                DB::raw('CAST(b.qty_unit AS UNSIGNED) AS qty_unit'),
                DB::raw('CAST(b.qty_sup AS UNSIGNED) AS qty_sup'),
                'b.satuan',
                DB::raw('CAST(b.harga AS UNSIGNED) AS harga'),
                DB::raw('CAST(b.disc AS UNSIGNED) AS disc'),
                DB::raw('CAST(b.total AS UNSIGNED) AS total'),
                'b.rcabang'
            ])
            ->where('b.status_po', 1)
            ->where('a.no_invoice', $invoice_number)
            ->get();

        if (!$transaction) {
            return redirect()->route('error_page')->with('message', 'Invoice not found');
        }

        $html = view('pdf.po_online_app', compact('transaction'))->render();

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($html);
        $mpdf->Output('PO_online_' . $invoice_number . '.pdf', 'I');
    }

    public function generate_list_harga_pdf(){
        $userId = Auth::user()->user_id;
        $idNotifikasi = HargaNotif::insertGetId([
            'user' => $userId,
            'status' => 'running',
        ]);

        ConvertPdf::dispatch($idNotifikasi);
        return response()->json(['message' => 'Proses sedang berjalan di background']);
        // return back();
    }

    public function generate_list_harga_pdf_node(){
        $total = DB::table('mbarang')->count();
        $chunkSize = 500;
        $userId = Auth::user()->user_id;

        // Buat file HTML sementara
        $htmlPath = storage_path("app/pdftemp$userId.html");
        $htmlContent = '';

        for ($i = 0; $i < $total; $i += $chunkSize) {
            $transaction = DB::table('mbarang')
                ->select(['KD_STOK', 'NAMA_BRG', 'KEMASAN', 'HJ1'])
                ->skip($i)
                ->take($chunkSize)
                ->get();

            $htmlContent .= view('pdf.harga_online', compact('transaction'))->render();
        }

        // Simpan file HTML
        if (File::put($htmlPath, $htmlContent) === false) {
            Log::error("Gagal menulis file HTML: $htmlPath");
            return response()->json(['error' => 'Gagal menulis file HTML'], 500);
        }

        // Panggil Puppeteer dengan Node.js
        $pdfPath = storage_path("app/listharga$userId.pdf");
        $command = "node " . base_path('generate_pdf.js') . " \"$htmlPath\" \"$pdfPath\"";
        $output = shell_exec("$command 2>&1");

        Log::info("Output command: $output");

        // Cek apakah PDF berhasil dibuat
        if (!File::exists($pdfPath)) {
            Log::error("Gagal membuat PDF: $pdfPath");
            return response()->json(['error' => 'Gagal membuat PDF'], 500);
        }

        // Hapus file HTML sementara
        File::delete($htmlPath);

        // Kirim PDF ke browser dan paksa pengunduhan
        // return response()->download($pdfPath, 'listhargaAD0002.pdf')->deleteFileAfterSend(true);
        return response()->download($pdfPath, 'listhargaAD0002.pdf');
    }
}
