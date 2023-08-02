<?php

namespace App\Http\Requests;

use App\Scooter;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateScooterRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('scooter_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
                'unique:scooters,barcode,' . request()->route('scooter')->id,
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
            'solved'    => [
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
