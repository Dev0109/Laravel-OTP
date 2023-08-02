<?php

namespace App\Imports;

use App\Price;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Session;

class PriceImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Price([
            'itemcode' => $row['itemcode'],
            'description' => $row['primary_description'],
            'description2' => $row['secondary_description'],
            'price' => (float)$row['price'],
            'pricetype_id' => Session::get('pricetype_id'),
            'id_model' => $row['id_model'] !== '' ? $row['id_model'] : null,
            'image' => $row['image']
        ]);
    }
}
