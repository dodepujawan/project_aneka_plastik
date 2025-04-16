<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('po_online', function (Blueprint $table) {
            $table->decimal('ppn', 10, 2)->nullable()->after('disc1'); // Ganti 'nama_kolom_terakhir' sesuai kolom terakhir di tabel kamu
            $table->decimal('rppn', 15, 2)->nullable()->after('ppn');
            $table->decimal('hsppn', 15, 2)->nullable()->after('rppn');
            $table->decimal('disc', 5, 2)->nullable()->after('hsppn'); // diskon persen
            $table->decimal('rdisc', 15, 2)->nullable()->after('disc'); // rupiah diskon
            $table->decimal('ndisc', 15, 2)->nullable()->after('rdisc'); // diskon nilai tetap (non persen)
            $table->decimal('ttldisc', 15, 2)->nullable()->after('ndisc'); // total diskon akhir
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('po_online', function (Blueprint $table) {
            $table->dropColumn(['ppn', 'rppn', 'hsppn', 'disc', 'rdisc', 'ndisc', 'ttldisc']);
        });
    }
};
