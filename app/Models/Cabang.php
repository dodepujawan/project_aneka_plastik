<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    use HasFactory;
    protected $fillable = [
        'lokal_id',
        'cabang_id',
        'nama',
        'alamat',
        'telp',
    ];
}
