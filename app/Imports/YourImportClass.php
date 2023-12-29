<?php

namespace App\Imports;

use App\Models\Path;
use Maatwebsite\Excel\Concerns\ToModel;

class YourImportClass implements ToModel
{
    public function model(array $row)
    {
        return new Path([
            'path' => $row[4],
        ]);
    }
}
