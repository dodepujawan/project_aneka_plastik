<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transusers extends Model
{
    use HasFactory;
    protected $table = 'po_userby';
    // public $timestamps = false;
    protected $fillable = [
        'no_invoice',
        'user_id',
        'nama_cust',
        'user_kode',
        'created_at',
        'updated_at',
    ];
}
