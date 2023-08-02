<?php

namespace App\Http\Requests;

use App\ScooterStatus;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyScooterStatusRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('Scooter_status_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:scooter_statuses,id',
        ];
    }
}
