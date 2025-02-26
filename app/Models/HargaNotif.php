<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HargaNotif extends Model
{
    use HasFactory;
    protected $table = 'pdf_status';
    protected $fillable = ['user', 'description', 'status', 'file_path'];
}
