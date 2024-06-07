<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rekor extends Model
{
    use HasFactory;
    protected $table = 'Rekors';
    protected $fillable = [
        'POSTAT',
        'PORECO',
        'PODTVL',
        'POREFN',
        'PODTPO',
        'POTCRO',
        'PODESC',
        'POAMNT',
        'file_path',
    ];


}


