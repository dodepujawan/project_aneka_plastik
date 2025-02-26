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

class ConvertPdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $notifikasiId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($notifikasiId)
    {
        $this->notifikasiId = $notifikasiId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $total = DB::table('mbarang')->count();

        // Process in chunks of 500 records
        $chunkSize = 500;
        $mpdf = new \Mpdf\Mpdf();
        $userId = Auth::user()->user_id;
        for ($i = 0; $i < $total; $i += $chunkSize) {
            $transaction = DB::table('mbarang')
                ->select(['KD_STOK', 'NAMA_BRG', 'KEMASAN', 'HJ1'])
                ->skip($i)
                ->take($chunkSize)
                ->get();

            $html = view('pdf.harga_online', compact('transaction'))->render();
            $mpdf->WriteHTML($html);
        }

        $html = view('pdf.harga_online', compact('transaction'))->render();

        $date_now = Carbon::now()->format('d-m-Y');
        // $mpdf = new \Mpdf\Mpdf();
        // $mpdf->WriteHTML($html);

        $filePath = 'list_harga_' . $userId . '_' . $date_now . '.pdf';
        $fullPath = storage_path('app/' . $filePath);

        // Simpan file ke storage/app
        $mpdf->Output($fullPath, \Mpdf\Output\Destination::FILE);

        $id = $this->notifikasiId;
        HargaNotif::where('id', $id)
            ->update([
                'status' => 'completed',
                'file_path' => $filePath,
            ]);
    }
}
