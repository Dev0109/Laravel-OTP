<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

class Price extends Model
{
    use SoftDeletes;

    public $table = 'prices';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'itemcode',
        'description',
        'description2',
        'price',
        'pricetype_id',
        'id_model',
        'image',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function status()
    {
        return $this->belongsTo(ScooterStatus::class, 'status_id');
    }
}
