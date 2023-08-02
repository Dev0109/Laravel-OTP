<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactPeople extends Model
{
    //
    public $table = 'contact_people';

    protected $fillable = [
        'user',
        'company_id',
        'firstname',
        'secondname',
        'phone',
        'mobile',
        'email',
        'job_description'
    ];
}
