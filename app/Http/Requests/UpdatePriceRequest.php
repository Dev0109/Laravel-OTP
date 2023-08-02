<?php

namespace App\Http\Requests;

use App\Price;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdatePriceRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('scooter_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'itemcode' => [
                'string',
                'required',
            ],
            'description' => [
                'string',
                'required',
            ],
            'price' => [
                'required',
            ],
            'pricetype_id' => [
                'required',
            ],
        ];
    }
}
