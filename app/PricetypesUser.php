<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \DateTimeInterface;

class PricetypesUser extends Model
{
    public $table = 'pricetypes_user';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'userId',
        'pricetypes'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
