<?php

namespace App\Http\Requests;

use App\Pricing;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdatePricemanageRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('scooter_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'price_id' => [
                'required',
            ],
        ];
    }
}
