<?php

namespace App\Http\Requests;

use App\ScooterStatus;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreScooterStatusRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('scooter_status_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
