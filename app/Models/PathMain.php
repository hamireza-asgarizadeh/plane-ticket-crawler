<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PathMain extends Model
{
    protected $fillable = [
        'destination',
        'origin',
        'kilometer',
        'info',
    ];
}
