<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoPajak extends Model
{
    use HasFactory;
    protected $table = 'po_pajak';

    protected $fillable = ['ppn'];
}
