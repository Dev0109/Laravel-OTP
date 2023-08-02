<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

class Unit extends Model
{
    use SoftDeletes;

    public $table = 'units';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'pid',
        'name',
        'layout',
        'indoor',
        'ex1',
        'ex2',
        'airflow',
        'pressure',
        'Tfin',
        'Trin',
        'Hfin',
        'Hrin',
        'modelId',
        'priceId',
        'price',
        'delivery_time',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
