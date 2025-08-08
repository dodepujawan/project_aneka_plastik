<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faktur extends Model
{
    use HasFactory;
    protected $table = 'faktur_online';
    protected $fillable = [
        'no_faktur',
        'kd_brg',
        'nama_brg',
        'qty_order',
        'qty_sup',
        'qty_unit',
        'satuan',
        'harga',
        'ttl_gross',
        'ppn',
        'rppn',
        'dpp',
        'disc',
        'rdisc',
        'ndisc',
        'ttldisc',
        'total',
        'rcabang',
        'status_po',
        'history_inv',
        'created_at',
        'updated_at',
    ];
}
