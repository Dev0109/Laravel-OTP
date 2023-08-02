<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScooterStatus extends Model
{
    use SoftDeletes;

    public $table = 'scooter_statuses';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function scooters()
    {
        return $this->hasMany(Scooter::class, 'status_id', 'id');
    }
}
