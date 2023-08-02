<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LangImport implements ToArray, WithHeadingRow
{

    public function array(array $rows)
    {
        // Process the rows array here and return the final data structure
        return $rows;
    }
}
