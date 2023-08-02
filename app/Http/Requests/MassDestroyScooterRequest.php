<?php

namespace App\Http\Requests;

use App\Scooter;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyScooterRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('scooter_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:scooters,id',
        ];
    }
}
