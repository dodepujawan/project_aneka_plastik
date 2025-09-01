<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FakturUser extends Model
{
    use HasFactory;
    protected $table = 'faktur_userby';
    protected $fillable = [
        'no_faktur',
        'user_id',
        'nama_cust',
        'user_kode',
        'no_invoice',
        'created_at',
        'updated_at',
        'pembayaran',
        'nominal_bayar',
        'kembalian',
        'nama_bank'
    ];
}
