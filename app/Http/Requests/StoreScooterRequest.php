<?php

namespace App\Http\Requests;

use App\Scooter;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreScooterRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('scooter_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name'    => [
                'required',
            ],
            'phone'    => [
                'required',
            ],
            'barcode'    => [
                'string',
                'required',
                'unique:scooters',
            ],
            'model'    => [
                'string',
                'required',
            ],
            'termen'    => [
                'string',
                'required',
            ],
            'problem'    => [
                'required',
            ],
            'price'    => [
                'string',
                'required',
            ],
            'status_id'    => [
                'required',
            ]
        ];
    }
}
