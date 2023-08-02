<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

class Project extends Model
{
    use SoftDeletes;

    public $table = 'project';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user',
        'company',
        'contact',
        'name',
        'description',
        'reference',
        'pdf',
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
        'status'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
