<?php

namespace App\Imports;

use App\Scooter;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ScootersImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Scooter([
            'name' => $row['name'],
            'phone' => $row['phone'],
            'barcode' => $row['barcode'],
            'termen' => $row['termen'],
            'signature_name' => $row['signature_name'],
            'signature_file_path' => $row['signature_file_path'],
            'problem' => $row['problem'],
            'solved' => $row['solved'],
            'price' => $row['price'],
            'status_id' => 1,
            'created_at' => $row['created_at'] ? Carbon::parse(Date::excelToDateTimeObject($row['created_at'])) : null,
            'updated_at' => $row['updated_at'] ? Carbon::parse(Date::excelToDateTimeObject($row['updated_at'])) : null,
        ]);
    }
}
