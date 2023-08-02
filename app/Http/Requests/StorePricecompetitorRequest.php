<?php

namespace App\Http\Requests;

use App\Pricecompetitor;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StorePricecompetitorRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('scooter_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'required',
            ],
        ];
    }
}
