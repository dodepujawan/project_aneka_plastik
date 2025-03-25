<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use mPDF;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\HargaNotif;
use Barryvdh\DomPDF\Facade as PDF;
use App\Events\PdfDone;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class ConvertPdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $userId;
    public $idNotifikasi;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userId, $idNotifikasi)
    {
        $this->userId = $userId;
        $this->idNotifikasi = $idNotifikasi;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(){
        $userId = $this->userId;
        $idNotifikasi = $this->idNotifikasi;
        // Ambil semua data barang langsung tanpa chunk
        $transactions = DB::table('mbarang')
            ->select(['KD_STOK', 'NAMA_BRG', 'KEMASAN', 'HJ1'])
            ->get();

        // Buat file HTML sementara
        $htmlPath = storage_path("app/pdftemp$userId.html");

        // Bersihkan file lama jika ada
        if (file_exists($htmlPath)) {
            unlink($htmlPath);
        }

        // Render seluruh data ke satu file HTML
        Log::info("Membuat file HTML...");
        $htmlContent = view('pdf.harga_online', compact('transactions'))->render();

        if (file_put_contents($htmlPath, $htmlContent) === false) {
            Log::error("Gagal menulis file HTML.");
            return response()->json(['error' => 'Gagal menulis file HTML'], 500);
        }

        Log::info("File HTML berhasil dibuat: $htmlPath");

        // Panggil Puppeteer dengan Node.js
        $pdfPath = storage_path("app/listharga$userId.pdf");
        $command = "node " . base_path('generate_pdf.js') . " \"$htmlPath\" \"$pdfPath\"";

        Log::info("Menjalankan command: $command");
        $output = shell_exec("$command 2>&1");

        Log::info("Output command: $output");

        // Cek apakah PDF berhasil dibuat
        if (!file_exists($pdfPath)) {
            Log::error("Gagal membuat PDF: $pdfPath");
            return response()->json(['error' => 'Gagal membuat PDF'], 500);
        }

        Log::info("PDF berhasil dibuat: $pdfPath");

        File::delete($htmlPath);
        // Update Status
        $id = $idNotifikasi;
        HargaNotif::where('id', $id)
            ->update([
                'status' => 'completed',
                'file_path' => $pdfPath,
            ]);

        $notifikasi = HargaNotif::find($id);

        Log::info('Notifikasi'.json_encode($notifikasi));

        event(new PdfDone($userId));

        Log::info("Event PdfDone dikirim ke user: $userId");
    }
    // public function handle()
    // {
    //     $total = DB::table('mbarang')->count();

    // // Process in chunks of 500 records
    // $chunkSize = 500;
    // $userId = Auth::user()->user_id;
    // $pdf = PDF::loadHTML('');
    // $pdf = \Barryvdh\DomPDF\Facade::loadHTML('');

    // for ($i = 0; $i < $total; $i += $chunkSize) {
    //     $transaction = DB::table('mbarang')
    //         ->select(['KD_STOK', 'NAMA_BRG', 'KEMASAN', 'HJ1'])
    //         ->skip($i)
    //         ->take($chunkSize)
    //         ->get();

    //     $html = view('pdf.harga_online', compact('transaction'))->render();
    //     $pdf->getDomPDF()->loadHtml($html);
    // }

    // $date_now = Carbon::now()->format('d-m-Y');
    // $filePath = 'list_harga_' . $userId . '_' . $date_now . '.pdf';
    // $fullPath = storage_path('app/' . $filePath);

    // // Simpan file ke storage/app
    // Storage::put($filePath, $pdf->output());

    // // Update status notifikasi
    // $id = $this->notifikasiId;
    // HargaNotif::where('id', $id)
    //     ->update([
    //         'status' => 'completed',
    //         'file_path' => $filePath,
    //     ]);
    // }
    // public function handle()
    // {

    //     $total = DB::table('mbarang')->count();

    //     // Process in chunks of 500 records
    //     $chunkSize = 500;
    //     // $mpdf = new \Mpdf\Mpdf();
    //     $mpdf = new \Mpdf\Mpdf([
    //         'timeout' => 0, // Menonaktifkan timeout di MPDF (jika ada)
    //     ]);
    //     $userId = Auth::user()->user_id;
    //     for ($i = 0; $i < $total; $i += $chunkSize) {
    //         $transaction = DB::table('mbarang')
    //             ->select(['KD_STOK', 'NAMA_BRG', 'KEMASAN', 'HJ1'])
    //             ->skip($i)
    //             ->take($chunkSize)
    //             ->get();

    //         $html = view('pdf.harga_online', compact('transaction'))->render();
    //         $mpdf->WriteHTML($html);
    //     }

    //     $date_now = Carbon::now()->format('d-m-Y');
    //     // $mpdf = new \Mpdf\Mpdf();
    //     // $mpdf->WriteHTML($html);

    //     $filePath = 'list_harga_' . $userId . '_' . $date_now . '.pdf';
    //     $fullPath = storage_path('app/' . $filePath);

    //     // Simpan file ke storage/app
    //     $mpdf->Output($fullPath, \Mpdf\Output\Destination::FILE);

    //     $id = $this->notifikasiId;
    //     HargaNotif::where('id', $id)
    //         ->update([
    //             'status' => 'completed',
    //             'file_path' => $filePath,
    //         ]);
    // }
}
