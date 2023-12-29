<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Path extends Model
{
    protected $fillable = [
        'path',
        'km',
        'checked',
    ];
}
