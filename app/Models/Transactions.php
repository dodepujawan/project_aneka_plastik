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
        'ppn',
        'rppn',
        'hsppn',
        'disc',
        'rdisc',
        'ndisc',
        'ttldisc',
        'total',
        'rcabang',
        'status_po',
        'created_at',
        'updated_at',
    ];
}
