<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrisLokasiSales extends Model
{
    use HasFactory;
    protected $table = 'qris_lokasi_sales';

    protected $fillable = [
        'user_id',
        'customer_id',
    ];
}
