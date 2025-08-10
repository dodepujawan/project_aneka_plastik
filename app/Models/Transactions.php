<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    use HasFactory;
    protected $table = 'po_online';
    // public $timestamps = false;
    protected $fillable = [
        'no_invoice',
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
        'status_faktur',
        'history_inv',
        'created_at',
        'updated_at',
    ];
}
