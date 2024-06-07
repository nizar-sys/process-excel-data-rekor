<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessedData extends Model
{
    use HasFactory;



    protected $fillable =[
        'tanggal_transaksi',
        'keterangan',
        'debit',
        'kredit',
        'saldo',
    ];
    
}
