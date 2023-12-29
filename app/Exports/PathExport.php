<?php

namespace App\Exports;

use App\Models\Path;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;

class PathExport implements FromCollection
{
    public function collection()
    {
        return Path::all();
    }
}
